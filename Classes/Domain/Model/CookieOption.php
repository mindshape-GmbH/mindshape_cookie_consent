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

use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Model
 */
class CookieOption extends AbstractValueObject
{
    public const TABLE = 'tx_mindshapecookieconsent_domain_model_cookieoption';

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory
     */
    protected $cookieCategory;

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $identifier = '';

    /**
     * @var string
     */
    protected $provider = '';

    /**
     * @var string
     */
    protected $purpose = '';

    /**
     * @var string
     */
    protected $cookieName = '';

    /**
     * @var string
     */
    protected $cookieDuration = '';

    /**
     * @var string
     */
    protected $replacementLabel = '';

    /**
     * @var string
     */
    protected $info = '';

    /**
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory
     */
    public function getCookieCategory(): ?CookieCategory
    {
        return $this->cookieCategory;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory $cookieCategory
     */
    public function setCookieCategory(CookieCategory $cookieCategory): void
    {
        $this->cookieCategory = $cookieCategory;
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
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     */
    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getPurpose(): string
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     */
    public function setPurpose(string $purpose): void
    {
        $this->purpose = $purpose;
    }

    /**
     * @return string
     */
    public function getCookieName(): string
    {
        return $this->cookieName;
    }

    /**
     * @param string $cookieName
     */
    public function setCookieName(string $cookieName): void
    {
        $this->cookieName = $cookieName;
    }

    /**
     * @return string
     */
    public function getCookieDuration(): string
    {
        return $this->cookieDuration;
    }

    /**
     * @param string $cookieDuration
     */
    public function setCookieDuration(string $cookieDuration): void
    {
        $this->cookieDuration = $cookieDuration;
    }

    /**
     * @return string
     */
    public function getReplacementLabel(): string
    {
        return $this->replacementLabel;
    }

    /**
     * @param string $replacementLabel
     */
    public function setReplacementLabel(string $replacementLabel): void
    {
        $this->replacementLabel = $replacementLabel;
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
     * @return bool
     */
    public function isNecessary(): bool
    {
        return !$this->cookieCategory instanceof CookieCategory;
    }
}
