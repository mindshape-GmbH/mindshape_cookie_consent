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
class StatisticOption extends AbstractStatistic
{
    public const TABLE = 'tx_mindshapecookieconsent_domain_model_statisticoption';

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption|null
     */
    protected ?CookieOption $cookieOption;

    /**
     * @var int
     */
    protected int $counter = 0;

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \DateTime $date
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption|null $cookieOption
     */
    public function initialize(Configuration $configuration, DateTime $date, ?CookieOption $cookieOption = null): void
    {
        $this->_languageUid = $configuration->getLanguageUid();
        $this->configuration = $configuration;
        $this->cookieOption = $cookieOption;
        $this->date = $date;
    }

    /**
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption|null
     */
    public function getCookieOption(): ?CookieOption
    {
        return $this->cookieOption;
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
