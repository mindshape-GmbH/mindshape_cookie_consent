<?php
namespace Mindshape\MindshapeCookieConsent\Domain\Repository;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use DateTime;
use DateTimeZone;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\ParameterType;
use Exception;
use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
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
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \DateTime $date
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByMonth(Configuration $configuration, DateTime $date): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setLanguageUid($configuration->getLanguageUid());

        try {
            $query->matching(
                $query->logicalAnd([
                    $query->equals('configuration', $configuration->getUid()),
                    $query->greaterThan('dateBegin', $date->modify('first day of this month 00:00:00')->format('c')),
                    $query->lessThan('dateEnd', $date->modify('last day of this month 23:59:59')->format('c')),
                ])
            );
        } catch (InvalidQueryException $exception) {
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
        $tableName = GeneralUtility::makeInstance(DataMapper::class)->convertClassNameToTableName($this->objectType);
        $languageField = $GLOBALS['TCA'][$tableName]['ctrl']['languageField'];

        $queryBuilder = DatabaseUtility::queryBuilder();
        $queryBuilder
            ->select('date_begin')
            ->from($tableName)
            ->where(
                $queryBuilder->expr()->eq($languageField, $queryBuilder->createNamedParameter($languageUid, ParameterType::INTEGER))
            )
            ->orderBy('date_begin', 'DESC')
            ->groupBy('DATE_FORMAT(date_begin, "%Y-%m")');

        try {
            $dateRecords = DatabaseUtility::databaseConnection()->fetchAll(
                preg_replace('/GROUP BY `(.*?)`/i', 'GROUP BY $1', $queryBuilder->getSQL()),
                $queryBuilder->getParameters()
            );
        } catch (DBALException $exception) {
            $dateRecords = [];
        }

        $dates = [];

        foreach ($dateRecords as $dateRecord) {
            try {
                $date = new DateTime($dateRecord['date_begin'], new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
                $date->modify('first day of this month 00:00:00');

                $dates[] = $date;
            } catch (Exception $exception) {
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
        $query->getQuerySettings()->setLanguageUid($configuration->getLanguageUid());

        $query->matching(
            $query->equals('configuration', $configuration->getUid())
        );

        return $query->execute();
    }
}
