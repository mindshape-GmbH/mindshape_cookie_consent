<?php
namespace Mindshape\MindshapeCookieConsent\Domain\Model;

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

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Model
 */
class StatisticOption extends AbstractStatistic
{
    public const TABLE = 'tx_mindshapecookieconsent_domain_model_statisticoption';

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption
     */
    protected $cookieOption;

    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \DateTime $dateBegin
     * @param \DateTime $dateEnd
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption
     */
    public function __construct(Configuration $configuration, DateTime $dateBegin, DateTime $dateEnd, CookieOption $cookieOption = null)
    {
        $this->_languageUid = $configuration->getLanguageUid();
        $this->configuration = $configuration;
        $this->cookieOption = $cookieOption;
        $this->dateBegin = $dateBegin;
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption
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

    public function increaseCounter()
    {
        $this->counter++;
    }
}
