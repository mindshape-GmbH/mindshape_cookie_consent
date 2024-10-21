<?php

namespace Mindshape\MindshapeCookieConsent\Domain\Repository;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2024 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use DateMalformedStringException;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\ParameterType;
use Exception;
use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Repository
 */
abstract class AbstractStatisticRepository extends Repository
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper
     */
    protected DataMapper $dataMapper;

    /**
     * @var array
     */
    protected $defaultOrderings = [
        'dateBegin' => QueryInterface::ORDER_DESCENDING,
    ];

    public function initializeObject(): void
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);

        $this->setDefaultQuerySettings($querySettings);

        $this->dataMapper = GeneralUtility::makeInstance(DataMapper::class);
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \DateTime $date
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByMonth(Configuration $configuration, DateTime $date): QueryResultInterface
    {
        $query = $this->createQuery();
        $languageAspect = $query->getQuerySettings()->getLanguageAspect();

        $query->getQuerySettings()
            ->setLanguageAspect(
                new LanguageAspect(
                    $configuration->getLanguageUid(),
                    $languageAspect->getContentId(),
                    $languageAspect->getOverlayType(),
                    $languageAspect->getFallbackChain()
                )
            );

        try {
            $query->matching(
                $query->logicalAnd(
                    $query->equals('configuration', $configuration->getUid()),
                    $query->greaterThan('dateBegin', $date->modify('first day of this month 00:00:00')->format('c')),
                    $query->lessThan('dateEnd', $date->modify('last day of this month 23:59:59')->format('c')),
                )
            );
        } catch (InvalidQueryException|DateMalformedStringException) {
            // ignore
        }

        return $query->execute();
    }

    /**
     * @param int $languageUid
     * @return \DateTime[]
     */
    public function findAvailableMonths(int $languageUid = 0): array
    {
        $tableName = $this->dataMapper->convertClassNameToTableName($this->objectType);
        $languageField = $GLOBALS['TCA'][$tableName]['ctrl']['languageField'];

        $queryBuilder = DatabaseUtility::queryBuilder();
        $queryBuilder
            ->select('date_begin')
            ->from($tableName)
            ->where(
                $queryBuilder->expr()->eq(
                    $languageField,
                    $queryBuilder->createNamedParameter($languageUid, ParameterType::INTEGER)
                )
            )
            ->orderBy('date_begin', 'DESC')
            ->groupBy('DATE_FORMAT(date_begin, "%Y-%m")');

        try {
            $dateRecords = DatabaseUtility::databaseConnection()->fetchAllAssociative(
                preg_replace('/GROUP BY `(.*?)`/i', 'GROUP BY $1', $queryBuilder->getSQL()),
                $queryBuilder->getParameters()
            );
        } catch (DBALException) {
            $dateRecords = [];
        }

        $dates = [];

        foreach ($dateRecords as $dateRecord) {
            try {
                $date = new DateTime($dateRecord['date_begin'], new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
                $date->modify('first day of this month 00:00:00');

                $dates[] = $date;
            } catch (Exception) {
                // ignore
            }
        }

        return $dates;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllByConfiguration(Configuration $configuration): QueryResultInterface
    {
        $query = $this->createQuery();
        $languageAspect = $query->getQuerySettings()->getLanguageAspect();

        $query->getQuerySettings()
            ->setLanguageAspect(
                new LanguageAspect(
                    $configuration->getLanguageUid(),
                    $languageAspect->getContentId(),
                    $languageAspect->getOverlayType(),
                    $languageAspect->getFallbackChain()
                )
            );

        $query->matching(
            $query->equals('configuration', $configuration->getUid())
        );

        return $query->execute();
    }
}
