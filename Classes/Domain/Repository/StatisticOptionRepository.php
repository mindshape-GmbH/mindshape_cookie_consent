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

use DateTime;
use DateTimeZone;
use Exception;
use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Model\Consent;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption;
use Mindshape\MindshapeCookieConsent\Domain\Model\StatisticOption;
use TYPO3\CMS\Core\Context\LanguageAspect;
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
        'date' => QueryInterface::ORDER_DESCENDING,
        'cookieOption' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Consent $consent
     */
    public function updateStatistic(Configuration $configuration, Consent $consent): void
    {
        $query = $this->createQuery();
        $query
            ->getQuerySettings()
            ->setLanguageAspect(
                new LanguageAspect(
                    id: $configuration->getLanguageUid(),
                    overlayType: LanguageAspect::OVERLAYS_OFF
                )
            );

        $statisticOptions = [];

        try {
            $currentTime = new DateTime();
            $currentTime->setTimezone(new DateTimeZone('UTC'));
        } catch (Exception) {
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
                    $query->logicalAnd(
                        $query->equals('configuration', $configuration->getUid()),
                        $query->equals('date', $currentTime->format('Y-m-d')),
                        $query->equals(
                            'cookieOption',
                            $cookieOption instanceof CookieOption
                                ? $cookieOption->getUid()
                                : 0
                        ),
                    )
                );
            } catch (InvalidQueryException) {
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
        } catch (IllegalObjectTypeException|UnknownObjectException) {
            // ignore
        }
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption|null $cookieOption
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticOption
     */
    protected function getOrCreateStatisticOption(QueryInterface $query, Configuration $configuration, ?CookieOption $cookieOption = null): StatisticOption
    {
        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticOption|null $statisticOption */
        $statisticOption = $query->execute()->getFirst();

        if (!$statisticOption instanceof StatisticOption) {
            try {
                $statisticOption = new StatisticOption();
                $statisticOption->initialize($configuration, new DateTime(), $cookieOption);
            } catch (Exception) {
                // ignore
            }
        }

        return $statisticOption;
    }
}
