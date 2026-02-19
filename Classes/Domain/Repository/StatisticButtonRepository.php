<?php

namespace Mindshape\MindshapeCookieConsent\Domain\Repository;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2026 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use DateTime;
use Exception;
use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Model\Consent;
use Mindshape\MindshapeCookieConsent\Domain\Model\StatisticButton;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Repository
 */
class StatisticButtonRepository extends AbstractStatisticRepository
{
    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Consent $consent
     */
    public function updateStatistic(Configuration $configuration, Consent $consent): void
    {
        $query = $this->createQuery();

        try {
            $currentTime = new DateTime();
        } catch (Exception) {
            $currentTime = null;
        }

        try {
            $query->matching(
                $query->logicalAnd(
                    $query->equals('configuration', $configuration->getUid()),
                    $query->equals('date', $currentTime->format('Y-m-d')),
                )
            );
        } catch (InvalidQueryException) {
            // ignore
        }

        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticButton|null $statisticButton */
        $statisticButton = $query->execute()->getFirst();

        if (!$statisticButton instanceof StatisticButton) {
            $statisticButton = new StatisticButton();
            $statisticButton->initialize($configuration);

            try {
                $statisticButton->setDate(new DateTime());
            } catch (Exception) {
                // ignore
            }
        }

        if (true === $consent->isDeny()) {
            $statisticButton->increaseDeny();
        } elseif (true === $consent->isSelectAll()) {
            $statisticButton->increaseAgreeToAll();
        } else {
            $statisticButton->increaseSave();
        }

        $this->save($statisticButton);
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticButton $statisticButton
     */
    public function save(StatisticButton $statisticButton): void
    {
        try {
            if (true === $statisticButton->_isNew()) {
                $this->add($statisticButton);
            } else {
                $this->update($statisticButton);
            }
        } catch (IllegalObjectTypeException|UnknownObjectException) {
            // ignore
        }
    }
}
