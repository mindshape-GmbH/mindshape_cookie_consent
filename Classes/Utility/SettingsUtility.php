<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\Utility;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;

/**
 * @package MindshapeNews
 * @subpackage Utility
 */
class SettingsUtility
{
    const EXTENSION_NAME = 'mindshapecookieconsent';
    const EXTENSION_KEY = 'mindshape_cookie_consent';
    const EXTENSION_TYPOSCRIPT_KEY = 'tx_' . self::EXTENSION_NAME;

    /**
     * @return null|array
     */
    public static function pluginTypoScriptSettings(): array
    {
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = ObjectUtility::makeInstance(ConfigurationManager::class);

        try {
            $settings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, self::EXTENSION_NAME);
        } catch (InvalidConfigurationTypeException $exception) {
            $settings = [];
        }

        return $settings;
    }

    /**
     * @return null|array
     */
    public static function extensionTypoScriptSettings(): array
    {
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = ObjectUtility::makeInstance(ConfigurationManager::class);

        try {
            $typoscript = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $typoscript = GeneralUtility::removeDotsFromTS($typoscript);

            if (false === array_key_exists(self::EXTENSION_TYPOSCRIPT_KEY, $typoscript['plugin'])) {
                return [];
            }

            $typoscript = $typoscript['plugin'][self::EXTENSION_TYPOSCRIPT_KEY];
        } catch (InvalidConfigurationTypeException $exception) {
            $typoscript = [];
        }

        return $typoscript;
    }
}
