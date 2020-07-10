<?php
namespace Mindshape\MindshapeCookieConsent\Service;

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

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Model\Consent;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption;
use Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\CookieOptionRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository;
use Mindshape\MindshapeCookieConsent\Utility\CookieUtility;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @package Mindshape\MindshapeCookieConsent\Service
 */
class CookieConsentService implements SingletonInterface
{
    public const DEFAULT_CONTAINER_ID = 'cookie-consent';

    /**
     * @var \Mindshape\MindshapeCookieConsent\Service\TemplateRenderingService
     */
    protected $templateRenderingService;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository
     */
    protected $configurationRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\CookieOptionRepository
     */
    protected $cookieOptionRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository
     */
    protected $statisticCategoryRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository
     */
    protected $statisticButtonRepository;

    /**
     * @var \TYPO3\CMS\Core\Site\Entity\Site
     */
    protected $currentSite;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var bool
     */
    protected static $editCookieButtonIsUsed = false;

    /**
     * @param \Mindshape\MindshapeCookieConsent\Service\TemplateRenderingService $templateRenderingService
     */
    public function injectTemplateRenderingService(TemplateRenderingService $templateRenderingService): void
    {
        $this->templateRenderingService = $templateRenderingService;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository $configurationRepository
     */
    public function injectConfigurationRepository(ConfigurationRepository $configurationRepository): void
    {
        $this->configurationRepository = $configurationRepository;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\CookieOptionRepository $cookieOptionRepository
     */
    public function injectCookieOptionRepository(CookieOptionRepository $cookieOptionRepository): void
    {
        $this->cookieOptionRepository = $cookieOptionRepository;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository $statisticCategoryRepository
     */
    public function injectStatisticCategoryRepository(StatisticCategoryRepository $statisticCategoryRepository): void
    {
        $this->statisticCategoryRepository = $statisticCategoryRepository;
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository $statisticButtonRepository
     */
    public function injectStatisticButtonRepository(StatisticButtonRepository $statisticButtonRepository): void
    {
        $this->statisticButtonRepository = $statisticButtonRepository;
    }

    public function __construct()
    {
        $this->settings = SettingsUtility::pluginTypoScriptSettings();

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        try {
            $this->currentSite = $siteFinder->getSiteByPageId($GLOBALS['TSFE']->id);
        } catch (SiteNotFoundException $exception) {
            $this->currentSite = null;
        }
    }

    /**
     * @param string $containerId
     * @return string
     */
    public function renderConsentModal(string $containerId = self::DEFAULT_CONTAINER_ID): string
    {
        $consent = $this->getConsentFromCookie();

        $containerId = trim($containerId);

        if ('' === $containerId) {
            $containerId = self::DEFAULT_CONTAINER_ID;
        }

        if (!$consent instanceof Consent) {
            $consent = new Consent();
        }

        return $this->templateRenderingService->render(
            'Consent',
            'Modal',
            [
                'id' => $containerId,
                'consent' => $consent,
                'configuration' => $this->currentConfiguration(),
                'currentUrl' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
                'currentRootPageUid' => $this->currentSite->getRootPageId(),
            ]
        );
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
        }
    }

    /**
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\Consent|null
     */
    public function getConsentFromCookie(): ?Consent
    {
        $cookieValue = CookieUtility::getCookieValue();

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
    protected function currentConfiguration(): ?Configuration
    {
        $configuration = null;

        if ($this->currentSite instanceof Site) {
            $configuration = $this->configurationRepository->findBySiteIdentifier($this->currentSite->getIdentifier());
        }

        if (null === $configuration) {
            $configuration = $this->configurationRepository->findBySiteIdentifier(Configuration::SITE_ALL_SITES);
        }

        return $configuration;
    }
}
