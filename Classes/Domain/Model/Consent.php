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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Model
 */
class Consent extends AbstractValueObject
{
    /**
     * @var bool
     */
    protected $selectAll = false;

    /**
     * @var bool
     */
    protected $deny = false;

    /**
     * @var bool
     */
    protected $isAjaxRequest = false;

    /**
     * @var string
     */
    protected $currentUrl = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption>
     */
    protected $cookieOptions;

    public function __construct()
    {
        $this->cookieOptions = new ObjectStorage();
    }

    /**
     * @return bool
     */
    public function isSelectAll(): bool
    {
        return $this->selectAll;
    }

    /**
     * @param bool $selectAll
     */
    public function setSelectAll(bool $selectAll): void
    {
        $this->selectAll = $selectAll;
    }

    /**
     * @return bool
     */
    public function isDeny(): bool
    {
        return $this->deny;
    }

    /**
     * @param bool $deny
     * @return void
     */
    public function setDeny(bool $deny): void
    {
        $this->deny = $deny;
    }

    /**
     * @return bool
     */
    public function isAjaxRequest(): bool
    {
        return $this->isAjaxRequest;
    }

    /**
     * @param bool $isAjaxRequest
     */
    public function setIsAjaxRequest(bool $isAjaxRequest): void
    {
        $this->isAjaxRequest = $isAjaxRequest;
    }

    /**
     * @return string
     */
    public function getCurrentUrl(): string
    {
        return $this->currentUrl;
    }

    /**
     * @param string $currentUrl
     */
    public function setCurrentUrl(string $currentUrl): void
    {
        $this->currentUrl = $currentUrl;
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
