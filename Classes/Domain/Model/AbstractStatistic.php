<?php
namespace Mindshape\MindshapeCookieConsent\Domain\Model;

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
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Model
 */
abstract class AbstractStatistic extends AbstractEntity
{
    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration
     */
    protected $configuration;

    /**
     * @var \DateTime
     */
    protected $dateBegin;

    /**
     * @var \DateTime
     */
    protected $dateEnd;

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
    public function getDateBegin(): DateTime
    {
        return $this->dateBegin;
    }

    /**
     * @param \DateTime $dateBegin
     */
    public function setDateBegin(DateTime $dateBegin): void
    {
        $this->dateBegin = $dateBegin;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd(): DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateEnd
     */
    public function setDateEnd(DateTime $dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }
}
