<?php
namespace Mindshape\MindshapeCookieConsent\Signal;

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
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory;
use Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository;
use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
use Mindshape\MindshapeCookieConsent\Utility\ObjectUtility;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * @package MindshapeNotifications
 * @subpackage Signal
 */
class ExtensionInstallationSignal
{
    /**
     * @param string $extensionKey
     */
    public function afterInstallation(string $extensionKey): void
    {
        if (SettingsUtility::EXTENSION_KEY !== $extensionKey) {
            return;
        }

        $queryBuilder = DatabaseUtility::queryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $result = $queryBuilder
            ->count('*')
            ->from(Configuration::TABLE)
            ->execute()
            ->fetch();

        if (0 === reset($result)) {
            $this->addDefaultData();
        }
    }

    protected function addDefaultData(): void
    {
        /** @var \Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository $configurationRepository */
        $configurationRepository = ObjectUtility::makeInstance(ConfigurationRepository::class);
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager */
        $persistenceManager = ObjectUtility::makeInstance(PersistenceManager::class);
        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = ObjectUtility::makeInstance(SiteFinder::class);

        foreach ($siteFinder->getAllSites() as $site) {
            $configuration = new Configuration();
            $configuration->setPid($site->getRootPageId());
            $configuration->setHeader(LocalizationUtility::translate('label.cookie_settings', SettingsUtility::EXTENSION_KEY));
            $configuration->setSite($site->getIdentifier());
            $configuration->setHint(LocalizationUtility::translate('label.cookie_hint', SettingsUtility::EXTENSION_KEY));
            $configuration->setNecessaryCookiesInfo('<p>' . LocalizationUtility::translate('label.necessary_cookies.info', SettingsUtility::EXTENSION_KEY) . '</p>');

            $optionStatistic = new CookieCategory();
            $optionStatistic->setPid($site->getRootPageId());
            $optionStatistic->setLabel(LocalizationUtility::translate('label.statistics', SettingsUtility::EXTENSION_KEY));
            $optionStatistic->setInfo('<p>' . LocalizationUtility::translate('label.statistics.info', SettingsUtility::EXTENSION_KEY) . '</p>');

            $optionMarketing = new CookieCategory();
            $optionMarketing->setPid($site->getRootPageId());
            $optionMarketing->setLabel(LocalizationUtility::translate('label.marketing', SettingsUtility::EXTENSION_KEY));
            $optionMarketing->setInfo('<p>' . LocalizationUtility::translate('label.marketing.info', SettingsUtility::EXTENSION_KEY) . '</p>');

            $optionMedia = new CookieCategory();
            $optionMedia->setPid($site->getRootPageId());
            $optionMedia->setLabel(LocalizationUtility::translate('label.external_media', SettingsUtility::EXTENSION_KEY));
            $optionMedia->setInfo('<p>' . LocalizationUtility::translate('label.external_media.info', SettingsUtility::EXTENSION_KEY) . '</p>');

            $cookieOptionYouTube = new CookieOption();
            $cookieOptionYouTube->setLabel('YouTube');
            $cookieOptionYouTube->setIdentifier('youtube');

            $cookieOptionVimeo = new CookieOption();
            $cookieOptionVimeo->setLabel('Vimeo');
            $cookieOptionVimeo->setIdentifier('vimeo');

            $optionMedia->addCookieOption($cookieOptionYouTube);
            $optionMedia->addCookieOption($cookieOptionVimeo);

            $configuration->addCookieCategory($optionStatistic);
            $configuration->addCookieCategory($optionMarketing);
            $configuration->addCookieCategory($optionMedia);

            try {
                $configurationRepository->add($configuration);
            } catch (IllegalObjectTypeException $exception) {
                // ignore
            }
        }

        $persistenceManager->persistAll();
    }
}
