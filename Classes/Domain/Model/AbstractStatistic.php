<?php

namespace Mindshape\MindshapeCookieConsent\Domain\Model;

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
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Model
 */
abstract class AbstractStatistic extends AbstractEntity
{
    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration
     */
    protected Configuration $configuration;

    /**
     * @var \DateTime
     */
    protected DateTime $date;

    /**
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration): void
    {
        $this->configuration = $configuration;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }
}
