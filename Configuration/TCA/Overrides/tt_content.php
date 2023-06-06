<?php

use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(
    function () {
        ExtensionUtility::registerPlugin(
            SettingsUtility::EXTENSION_KEY,
            'Consent',
            'LLL:EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/locallang.xlf:plugin.consent.title',
            SettingsUtility::EXTENSION_KEY . '-plugin-consent-icon',
            'special'
        );

        ExtensionUtility::registerPlugin(
            SettingsUtility::EXTENSION_KEY,
            'Cookielist',
            'LLL:EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/locallang.xlf:plugin.cookielist.title',
            SettingsUtility::EXTENSION_KEY . '-plugin-cookielist-icon',
            'special'
        );

        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:' . SettingsUtility::EXTENSION_KEY . '/Configuration/FlexForms/Settings.xml',
            SettingsUtility::EXTENSION_NAME . '_consent'
        );

        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,pi_flexform',
            SettingsUtility::EXTENSION_NAME . '_consent',
            'after:header'
        );
    }
);
