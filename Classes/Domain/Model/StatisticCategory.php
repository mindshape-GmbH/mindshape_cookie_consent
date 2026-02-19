<?php

namespace Mindshape\MindshapeCookieConsent\Domain\Model;

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

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Model
 */
class StatisticCategory extends AbstractStatistic
{
    public const TABLE = 'tx_mindshapecookieconsent_domain_model_statisticcategory';

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory|null
     */
    protected ?CookieCategory $cookieCategory;

    /**
     * @var int
     */
    protected int $counter = 0;

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \DateTime $date
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory|null $cookieCategory
     */
    public function initialize(Configuration $configuration, DateTime $date, ?CookieCategory $cookieCategory = null): void
    {
        $this->_languageUid = $configuration->getLanguageUid();
        $this->configuration = $configuration;
        $this->cookieCategory = $cookieCategory;
        $this->date = $date;
    }

    /**
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory|null
     */
    public function getCookieCategory(): ?CookieCategory
    {
        return $this->cookieCategory;
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }

    public function increaseCounter(): void
    {
        $this->counter++;
    }
}
