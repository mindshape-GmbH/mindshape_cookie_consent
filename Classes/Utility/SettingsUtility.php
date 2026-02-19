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
 *  (c) 2026 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
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
    /**
     * @return array|null
     */
    public static function pluginTypoScriptSettings(): ?array
    {
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        try {
            $settings = $configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                'mindshapecookieconsent'
            );
        } catch (InvalidConfigurationTypeException) {
            return null;
        }

        return $settings;
    }

    /**
     * @return array|null
     */
    public static function extensionTypoScriptSettings(): ?array
    {
        /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        try {
            $typoscript = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $typoscript = GeneralUtility::removeDotsFromTS($typoscript);

            if (
                false === is_array($typoscript['plugin']) ||
                false === array_key_exists('tx_mindshapecookieconsent', $typoscript['plugin'])
            ) {
                return null;
            }

            $typoscript = $typoscript['plugin']['tx_mindshapecookieconsent'];
        } catch (InvalidConfigurationTypeException) {
            $typoscript = [];
        }

        return $typoscript;
    }
}
