<?php
namespace Mindshape\MindshapeCookieConsent\Domain\Repository;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use DateTime;
use DateTimeZone;
use Exception;
use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Model\Consent;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption;
use Mindshape\MindshapeCookieConsent\Domain\Model\StatisticOption;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Repository
 */
class StatisticOptionRepository extends AbstractStatisticRepository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'dateBegin' => QueryInterface::ORDER_DESCENDING,
        'cookieOption' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Consent $consent
     */
    public function updateStatistic(Configuration $configuration, Consent $consent): void
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setLanguageUid($configuration->getLanguageUid());
        $statisticOptions = [];

        try {
            $currentTime = new DateTime();
            $currentTime->setTimezone(new DateTimeZone('UTC'));
        } catch (Exception $exception) {
            $currentTime = null;
        }

        $cookieOptions = [];
        $cookieOptions[] = null;

        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption */
        foreach ($consent->getCookieOptions() as $cookieOption) {
            $cookieOptions[$cookieOption->getUid()] = $cookieOption;
        }

        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption */
        foreach ($cookieOptions as $cookieOption) {
            try {
                $query->matching(
                    $query->logicalAnd([
                        $query->equals('configuration', $configuration->getUid()),
                        $query->lessThan('dateBegin', $currentTime->format('c')),
                        $query->greaterThan('dateEnd', $currentTime->format('c')),
                        $query->equals(
                            'cookieOption',
                            $cookieOption instanceof CookieOption
                                ? $cookieOption->getUid()
                                : 0
                        ),
                    ])
                );
            } catch (InvalidQueryException $exception) {
                // ignore
            }

            $statisticOptions[] = $this->getOrCreateStatisticOption($query, $configuration, $cookieOption);
        }

        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticOption $statisticOption */
        foreach ($statisticOptions as $statisticOption) {
            $statisticOption->increaseCounter();
            $this->save($statisticOption);
        }
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticOption $statistic
     */
    public function save(StatisticOption $statistic): void
    {
        try {
            if (true === $statistic->_isNew()) {
                $this->add($statistic);
            } else {
                $this->update($statistic);
            }
        } catch (IllegalObjectTypeException | UnknownObjectException $exception) {
            // ignore
        }
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticOption
     */
    protected function getOrCreateStatisticOption(QueryInterface $query, Configuration $configuration, CookieOption $cookieOption = null): StatisticOption
    {
        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticOption|null $statisticOption */
        $statisticOption = $query->execute()->getFirst();

        if (!$statisticOption instanceof StatisticOption) {
            try {
                $dateBegin = new DateTime('00:00:00');
                $dateEnd = new DateTime('23:59:59');
                $statisticOption = new StatisticOption($configuration, $dateBegin, $dateEnd, $cookieOption);
            } catch (Exception $exception) {
                // ignore
            }
        }

        return $statisticOption;
    }
}
