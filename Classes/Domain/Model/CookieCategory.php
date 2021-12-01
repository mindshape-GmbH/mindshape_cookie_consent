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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Model
 */
class CookieCategory extends AbstractEntity
{
    public const TABLE = 'tx_mindshapecookieconsent_domain_model_cookiecategory';

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration
     */
    protected $configuration;

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $info = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption>
     */
    protected $cookieOptions;

    public function __construct()
    {
        $this->cookieOptions = new ObjectStorage();
    }

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
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * @param string $info
     */
    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption
     */
    public function addCookieOption(CookieOption $cookieOption): void
    {
        $this->cookieOptions->attach($cookieOption);
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption
     */
    public function removeCookieOption(CookieOption $cookieOption): void
    {
        $this->cookieOptions->detach($cookieOption);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption>
     */
    public function getCookieOptions(): ObjectStorage
    {
        return $this->cookieOptions;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption> $cookieOptions
     */
    public function setCookieOptions(ObjectStorage $cookieOptions): void
    {
        $this->cookieOptions = $cookieOptions;
    }
}
