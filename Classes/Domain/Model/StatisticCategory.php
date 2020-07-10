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
class StatisticCategory extends AbstractStatistic
{
    public const TABLE = 'tx_mindshapecookieconsent_domain_model_statisticcategory';

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory
     */
    protected $cookieCategory;

    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \DateTime $dateBegin
     * @param \DateTime $dateEnd
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory $cookieCategory
     */
    public function __construct(Configuration $configuration, DateTime $dateBegin, DateTime $dateEnd, CookieCategory $cookieCategory = null)
    {
        $this->_languageUid = $configuration->getLanguageUid();
        $this->configuration = $configuration;
        $this->cookieCategory = $cookieCategory;
        $this->dateBegin = $dateBegin;
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory
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

    public function increaseCounter()
    {
        $this->counter++;
    }
}
