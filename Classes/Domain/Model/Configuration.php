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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Model
 */
class Configuration extends AbstractEntity
{
    public const TABLE = 'tx_mindshapecookieconsent_domain_model_configuration';
    public const SITE_ALL_SITES = '§§§ALL_SITES§§§';

    /**
     * @var string
     */
    protected string $site = '';

    /**
     * @var bool
     */
    protected bool $enableStatistic = false;

    /**
     * @var string
     */
    protected string $header = '';

    /**
     * @var string
     */
    protected string $imprint = '';

    /**
     * @var string
     */
    protected string $datapolicy = '';

    /**
     * @var string
     */
    protected string $hint = '';

    /**
     * @var string
     */
    protected string $necessaryCookiesInfo = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption>
     */
    protected ObjectStorage $necessaryCookieOptions;

    /**
     * @var string
     */
    protected string $selectAllLabel = '';

    /**
     * @var string
     */
    protected string $saveLabel = '';

    /**
     * @var string
     */
    protected string $denyLabel = '';

    /**
     * @var string
     */
    protected string $showDetailsLabel = '';

    /**
     * @var string
     */
    protected string $hideDetailsLabel = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory>
     */
    protected ObjectStorage $cookieCategories;

    public function __construct()
    {
        $this->cookieCategories = new ObjectStorage();
        $this->necessaryCookieOptions = new ObjectStorage();
    }

    /**
     * @return string
     */
    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * @param string $site
     */
    public function setSite(string $site): void
    {
        $this->site = $site;
    }

    /**
     * @return bool
     */
    public function isEnableStatistic(): bool
    {
        return $this->enableStatistic;
    }

    /**
     * @param bool $enableStatistic
     */
    public function setEnableStatistic(bool $enableStatistic): void
    {
        $this->enableStatistic = $enableStatistic;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getImprint(): string
    {
        return $this->imprint;
    }

    /**
     * @param string $imprint
     */
    public function setImprint(string $imprint): void
    {
        $this->imprint = $imprint;
    }

    /**
     * @return string
     */
    public function getDatapolicy(): string
    {
        return $this->datapolicy;
    }

    /**
     * @param string $datapolicy
     */
    public function setDatapolicy(string $datapolicy): void
    {
        $this->datapolicy = $datapolicy;
    }

    /**
     * @return string
     */
    public function getHint(): string
    {
        return $this->hint;
    }

    /**
     * @param string $hint
     */
    public function setHint(string $hint): void
    {
        $this->hint = $hint;
    }

    /**
     * @return string
     */
    public function getNecessaryCookiesInfo(): string
    {
        return $this->necessaryCookiesInfo;
    }

    /**
     * @param string $necessaryCookiesInfo
     */
    public function setNecessaryCookiesInfo(string $necessaryCookiesInfo): void
    {
        $this->necessaryCookiesInfo = $necessaryCookiesInfo;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption
     */
    public function addNecessaryCookieOption(CookieOption $cookieOption): void
    {
        $this->necessaryCookieOptions->attach($cookieOption);
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption
     */
    public function removeNecessaryCookieOptions(CookieOption $cookieOption): void
    {
        $this->necessaryCookieOptions->detach($cookieOption);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption>
     */
    public function getNecessaryCookieOptions(): ObjectStorage
    {
        return $this->necessaryCookieOptions;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption> $necessaryCookieOptions
     */
    public function setNecessaryCookieOptions(ObjectStorage $necessaryCookieOptions): void
    {
        $this->necessaryCookieOptions = $necessaryCookieOptions;
    }

    /**
     * @return string
     */
    public function getSelectAllLabel(): string
    {
        return $this->selectAllLabel;
    }

    /**
     * @param string $selectAllLabel
     */
    public function setSelectAllLabel(string $selectAllLabel): void
    {
        $this->selectAllLabel = $selectAllLabel;
    }

    /**
     * @return string
     */
    public function getSaveLabel(): string
    {
        return $this->saveLabel;
    }

    /**
     * @param string $saveLabel
     */
    public function setSaveLabel(string $saveLabel): void
    {
        $this->saveLabel = $saveLabel;
    }

    /**
     * @return string
     */
    public function getDenyLabel(): string
    {
        return $this->denyLabel;
    }

    /**
     * @param string $denyLabel
     */
    public function setDenyLabel(string $denyLabel): void
    {
        $this->denyLabel = $denyLabel;
    }

    /**
     * @return string
     */
    public function getShowDetailsLabel(): string
    {
        return $this->showDetailsLabel;
    }

    /**
     * @param string $showDetailsLabel
     */
    public function setShowDetailsLabel(string $showDetailsLabel): void
    {
        $this->showDetailsLabel = $showDetailsLabel;
    }

    /**
     * @return string
     */
    public function getHideDetailsLabel(): string
    {
        return $this->hideDetailsLabel;
    }

    /**
     * @param string $hideDetailsLabel
     */
    public function setHideDetailsLabel(string $hideDetailsLabel): void
    {
        $this->hideDetailsLabel = $hideDetailsLabel;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory $cookieCategory
     */
    public function addCookieCategory(CookieCategory $cookieCategory): void
    {
        $this->cookieCategories->attach($cookieCategory);
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory $cookieCategory
     */
    public function removeCookieCategory(CookieCategory $cookieCategory): void
    {
        $this->cookieCategories->detach($cookieCategory);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory>
     */
    public function getCookieCategories(): ObjectStorage
    {
        return $this->cookieCategories;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory> $cookieCategories
     */
    public function setCookieCategories(ObjectStorage $cookieCategories): void
    {
        $this->cookieCategories = $cookieCategories;
    }

    /**
     * @return int
     */
    public function getLanguageUid(): int
    {
        return $this->_languageUid;
    }

    /**
     * @return int
     */
    public function getLocalizedUid(): int
    {
        return $this->_localizedUid ?? $this->uid;
    }

    /**
     * @return bool
     */
    public function hasNoneNecessaryCookies(): bool
    {
        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory $cookieCategory */
        foreach ($this->cookieCategories as $cookieCategory) {
            if (0 < $cookieCategory->getCookieOptions()->count()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption>
     */
    public function getAllCookieOptions(): ObjectStorage
    {
        $cookieOptions = clone $this->getNecessaryCookieOptions();

        foreach ($this->getCookieCategories() as $cookieCategory) {
            foreach ($cookieCategory->getCookieOptions() as $cookieOption) {
                $cookieOptions->attach($cookieOption);
            }
        }

        return $cookieOptions;
    }
}
