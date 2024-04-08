<?php

namespace Mindshape\MindshapeCookieConsent\Service;

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

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Model\Consent;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption;
use Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\CookieOptionRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticOptionRepository;
use Mindshape\MindshapeCookieConsent\Utility\CookieUtility;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\Entity\NullSite;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @package Mindshape\MindshapeCookieConsent\Service
 */
class CookieConsentService implements SingletonInterface
{
    public const DEFAULT_CONTAINER_ID = 'cookie-consent';

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository
     */
    protected ConfigurationRepository $configurationRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\CookieOptionRepository
     */
    protected CookieOptionRepository $cookieOptionRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository
     */
    protected StatisticCategoryRepository $statisticCategoryRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository
     */
    protected StatisticButtonRepository $statisticButtonRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticOptionRepository
     */
    protected StatisticOptionRepository $statisticOptionRepository;

    /**
     * @var \TYPO3\CMS\Core\Site\Entity\Site|\TYPO3\CMS\Core\Site\Entity\NullSite
     */
    protected Site|NullSite $currentSite;

    /**
     * @var \TYPO3\CMS\Core\Site\Entity\SiteLanguage
     */
    protected SiteLanguage $currentSiteLanguage;

    /**
     * @var array|null
     */
    protected ?array $settings;

    /**
     * @var bool
     */
    protected static bool $editCookieButtonIsUsed = false;

    /**
     * @param \TYPO3\CMS\Core\Site\SiteFinder $siteFinder
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository $configurationRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\CookieOptionRepository $cookieOptionRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository $statisticCategoryRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository $statisticButtonRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticOptionRepository $statisticOptionRepository
     */
    public function __construct(
        SiteFinder $siteFinder,
        ConfigurationRepository $configurationRepository,
        CookieOptionRepository $cookieOptionRepository,
        StatisticCategoryRepository $statisticCategoryRepository,
        StatisticButtonRepository $statisticButtonRepository,
        StatisticOptionRepository $statisticOptionRepository
    ) {
        $this->configurationRepository = $configurationRepository;
        $this->cookieOptionRepository = $cookieOptionRepository;
        $this->statisticCategoryRepository = $statisticCategoryRepository;
        $this->statisticButtonRepository = $statisticButtonRepository;
        $this->statisticOptionRepository = $statisticOptionRepository;
        $this->settings = SettingsUtility::pluginTypoScriptSettings();

        try {
            $this->currentSite = $siteFinder->getSiteByPageId($GLOBALS['TSFE']->id);

            if ($GLOBALS['TSFE'] instanceof TypoScriptFrontendController) {
                $this->currentSiteLanguage = $GLOBALS['TSFE']->getLanguage();
            }
        } catch (SiteNotFoundException) {
            $this->currentSite = new NullSite();
            $this->currentSiteLanguage = $this->currentSite->getDefaultLanguage();
        }
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Consent $consent
     */
    public function updateConsentStatistic(Consent $consent): void
    {
        $configuration = $this->currentConfiguration();

        if (
            $configuration instanceof Configuration &&
            true === $configuration->isEnableStatistic()
        ) {
            $this->statisticCategoryRepository->updateStatistic($configuration, $consent);
            $this->statisticButtonRepository->updateStatistic($configuration, $consent);
            $this->statisticOptionRepository->updateStatistic($configuration, $consent);
        }
    }

    /**
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\Consent|null
     */
    public function getConsentFromCookie(): ?Consent
    {
        $cookieValue = CookieUtility::getCookieValue(
            $this->settings['cookieName'] ?? CookieUtility::DEFAULT_COOKIE_NAME
        );

        if (
            false === is_array($cookieValue) ||
            false === array_key_exists('options', $cookieValue)
        ) {
            return null;
        }

        $consent = new Consent();

        foreach ($cookieValue['options'] as $optionIdentifier) {
            $cookieOption = $this->cookieOptionRepository->findByCookieOptionIdentifier($optionIdentifier);

            if ($cookieOption instanceof CookieOption) {
                $consent->addCookieOption($cookieOption);
            }
        }

        return $consent;
    }

    /**
     * @param string $identifier
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption|null
     */
    public function getCookieOptionFromIdentifier(string $identifier): ?CookieOption
    {
        return $this->cookieOptionRepository->findByCookieOptionIdentifier($identifier);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCurrentConfigurationCookieOptions(): ObjectStorage
    {
        $configuration = $this->currentConfiguration();

        $cookieOptions = new ObjectStorage();

        if ($configuration instanceof Configuration) {
            /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory $cookieCategory */
            foreach ($configuration->getCookieCategories() as $cookieCategory) {
                /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption */
                foreach ($cookieCategory->getCookieOptions() as $cookieOption) {
                    $cookieOptions->attach($cookieOption);
                }
            }
        }

        return $cookieOptions;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCurrentConfigurationCookieCategories(): ObjectStorage
    {
        $configuration = $this->currentConfiguration();

        if ($configuration instanceof Configuration) {
            return $configuration->getCookieCategories();
        }

        return new ObjectStorage();
    }

    /**
     * @return string|null
     */
    public function getDatapolicyPageTypoLink(): ?string
    {
        if (!$this->currentConfiguration() instanceof Configuration) {
            return null;
        }

        return $this->currentConfiguration()->getDatapolicy();
    }

    /**
     * @return string|null
     */
    public function getImprintPageTypoLink(): ?string
    {
        if (!$this->currentConfiguration() instanceof Configuration) {
            return null;
        }

        return $this->currentConfiguration()->getImprint();
    }

    /**
     * @return bool
     */
    public static function cookieButtonIsUsed(): bool
    {
        return self::$editCookieButtonIsUsed;
    }

    public static function setCookieButtonIsUsed(): void
    {
        self::$editCookieButtonIsUsed = true;
    }

    /**
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration|null
     */
    public function currentConfiguration(): ?Configuration
    {
        $configuration = null;

        if ($this->currentSite instanceof Site) {
            $configuration = $this->configurationRepository->findBySiteIdentifier(
                $this->currentSite->getIdentifier(),
                $this->currentSiteLanguage->getLanguageId()
            );
        }

        if (null === $configuration) {
            $configuration = $this->configurationRepository->findBySiteIdentifier(
                Configuration::SITE_ALL_SITES,
                $this->currentSiteLanguage->getLanguageId()
            );
        }

        return $configuration;
    }

    /**
     * @return \TYPO3\CMS\Core\Site\Entity\SiteInterface
     */
    public function currentSite(): SiteInterface
    {
        return $this->currentSite;
    }
}
