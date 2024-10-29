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
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory;
use Mindshape\MindshapeCookieConsent\Domain\Model\StatisticCategory;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Repository
 */
class StatisticCategoryRepository extends AbstractStatisticRepository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'date' => QueryInterface::ORDER_DESCENDING,
        'cookieCategory' => QueryInterface::ORDER_ASCENDING,
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

        $cookieCategories = [];
        $cookieCategories[] = null;

        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption */
        foreach ($consent->getCookieOptions() as $cookieOption) {
            $cookieCategories[$cookieOption->getCookieCategory()->getUid()] = $cookieOption->getCookieCategory();
        }

        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory $cookieCategory */
        foreach ($cookieCategories as $cookieCategory) {
            try {
                $query->matching(
                    $query->logicalAnd(
                        $query->equals('configuration', $configuration->getUid()),
                        $query->equals('date', $currentTime->format('Y-m-d')),
                        $query->equals(
                            'cookieCategory',
                            $cookieCategory instanceof CookieCategory
                                ? $cookieCategory->getUid()
                                : 0
                        ),
                    )
                );
            } catch (InvalidQueryException) {
                // ignore
            }

            $statisticOptions[] = $this->getOrCreateStatisticCategory($query, $configuration, $cookieCategory);
        }

        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticCategory $statisticOption */
        foreach ($statisticOptions as $statisticOption) {
            $statisticOption->increaseCounter();
            $this->save($statisticOption);
        }
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticCategory $statistic
     */
    public function save(StatisticCategory $statistic): void
    {
        try {
            if (true === $statistic->_isNew()) {
                $this->add($statistic);
            } else {
                $this->update($statistic);
            }
        } catch (IllegalObjectTypeException | UnknownObjectException) {
            // ignore
        }
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory|null $cookieCategory
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticCategory
     */
    protected function getOrCreateStatisticCategory(QueryInterface $query, Configuration $configuration, CookieCategory $cookieCategory = null): StatisticCategory
    {
        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticCategory|null $statisticCategory */
        $statisticCategory = $query->execute()->getFirst();

        if (!$statisticCategory instanceof StatisticCategory) {
            try {
                $statisticCategory = new StatisticCategory();
                $statisticCategory->initialize($configuration, new DateTime(), $cookieCategory);
            } catch (Exception) {
                // ignore
            }
        }

        return $statisticCategory;
    }
}
