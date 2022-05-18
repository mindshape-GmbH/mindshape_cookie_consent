<?php
namespace Mindshape\MindshapeCookieConsent\Service;

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

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption;
use Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository;
use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * @package Mindshape\MindshapeCookieConsent\Service
 */
class ExtensionDefaultDataService implements SingletonInterface
{
    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository
     */
    protected $configurationRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var \TYPO3\CMS\Core\Site\SiteFinder
     */
    protected $siteFinder;

    public function __construct(
        ConfigurationRepository $configurationRepository,
        PersistenceManager $persistenceManager,
        SiteFinder $siteFinder
    ) {
        $this->configurationRepository = $configurationRepository;
        $this->persistenceManager = $persistenceManager;
        $this->siteFinder = $siteFinder;
    }

    public function checkAndAddDefaultConfigurations(): void
    {
        $queryBuilder = DatabaseUtility::queryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        try {
            $resultCount = $queryBuilder
                ->count('*')
                ->from(Configuration::TABLE)
                ->execute()
                ->fetchColumn();

            if (0 === $resultCount) {
                $this->addDefaultConfigurations();
            }
        } catch (DBALException $exception) {
            // ignore
        }
    }

    protected function addDefaultConfigurations(): void
    {
        foreach ($this->siteFinder->getAllSites() as $site) {
            $siteDefaultLanguage = $site->getDefaultLanguage();

            $configuration = new Configuration();
            $configuration->setPid($site->getRootPageId());
            $configuration->setHeader($this->translate('label.cookie_settings', $siteDefaultLanguage));
            $configuration->setSite($site->getIdentifier());
            $configuration->setHint($this->translate('label.cookie_hint', $siteDefaultLanguage));
            $configuration->setNecessaryCookiesInfo('<p>' . $this->translate('label.necessary_cookies.info', $siteDefaultLanguage) . '</p>');

            $optionStatistic = new CookieCategory();
            $optionStatistic->setPid($site->getRootPageId());
            $optionStatistic->setLabel($this->translate('label.statistics', $siteDefaultLanguage));
            $optionStatistic->setInfo('<p>' . $this->translate('label.statistics.info', $siteDefaultLanguage) . '</p>');

            $optionMarketing = new CookieCategory();
            $optionMarketing->setPid($site->getRootPageId());
            $optionMarketing->setLabel($this->translate('label.marketing', $siteDefaultLanguage));
            $optionMarketing->setInfo('<p>' . $this->translate('label.marketing.info', $siteDefaultLanguage) . '</p>');

            $optionMedia = new CookieCategory();
            $optionMedia->setPid($site->getRootPageId());
            $optionMedia->setLabel($this->translate('label.external_media', $siteDefaultLanguage));
            $optionMedia->setInfo('<p>' . $this->translate('label.external_media.info', $siteDefaultLanguage) . '</p>');

            $cookieOptionYouTube = new CookieOption();
            $cookieOptionYouTube->setLabel('YouTube');
            $cookieOptionYouTube->setIdentifier('youtube');

            $cookieOptionVimeo = new CookieOption();
            $cookieOptionVimeo->setLabel('Vimeo');
            $cookieOptionVimeo->setIdentifier('vimeo');

            $cookieOptionConsent = new CookieOption();
            $cookieOptionConsent->setLabel($this->translate('label.cookie_consent', $siteDefaultLanguage));
            $cookieOptionConsent->setCookieName('cookie_consent');
            $cookieOptionConsent->setCookieDuration($this->translate('label.one_year', $siteDefaultLanguage));
            $cookieOptionConsent->setPurpose($this->translate('label.consent_cookie_purpose', $siteDefaultLanguage));

            $optionMedia->addCookieOption($cookieOptionYouTube);
            $optionMedia->addCookieOption($cookieOptionVimeo);

            $configuration->addCookieCategory($optionStatistic);
            $configuration->addCookieCategory($optionMarketing);
            $configuration->addCookieCategory($optionMedia);
            $configuration->addNecessaryCookieOption($cookieOptionConsent);

            try {
                $this->configurationRepository->add($configuration);
            } catch (IllegalObjectTypeException $exception) {
                // ignore
            }
        }

        $this->persistenceManager->persistAll();
    }

    /**
     * @param string $key
     * @param \TYPO3\CMS\Core\Site\Entity\SiteLanguage $siteLanguage
     * @param array|null $arguments
     * @return string
     */
    protected function translate(string $key, SiteLanguage $siteLanguage, array $arguments = null): string
    {
        return LocalizationUtility::translate(
            $key,
            SettingsUtility::EXTENSION_KEY,
            $arguments,
            $siteLanguage->getTypo3Language()
        );
    }
}
