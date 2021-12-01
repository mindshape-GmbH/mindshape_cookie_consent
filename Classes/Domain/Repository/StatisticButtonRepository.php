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
            $currentTime->setTimezone(new DateTimeZone('UTC'));
        } catch (Exception $exception) {
            $currentTime = null;
        }

        try {
            $query->matching(
                $query->logicalAnd([
                    $query->equals('configuration', $configuration),
                    $query->lessThan('dateBegin', $currentTime->format('c')),
                    $query->greaterThan('dateEnd', $currentTime->format('c')),
                ])
            );
        } catch (InvalidQueryException $exception) {
            // ignore
        }

        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\StatisticButton|null $statisticButton */
        $statisticButton = $query->execute()->getFirst();

        if (!$statisticButton instanceof StatisticButton) {
            $statisticButton = new StatisticButton($configuration);

            try {
                $dateBegin = new DateTime('00:00:00');
                $dateEnd = new DateTime('23:59:59');

                $statisticButton->setDateBegin($dateBegin);
                $statisticButton->setDateEnd($dateEnd);
            } catch (Exception $exception) {
                // ignore
            }
        }

        if (true === $consent->isSelectAll()) {
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
        } catch (IllegalObjectTypeException | UnknownObjectException $exception) {
            // ignore
        }
    }
}
