<?php
defined('TYPO3') || die();

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY,
            'Consent',
            'LLL:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/locallang.xlf:plugin.consent.title'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY,
            'Cookielist',
            'LLL:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/locallang.xlf:plugin.cookielist.title'
        );

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][\Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME . '_consent'] = 'recursive,select_key,pages';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][\Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME . '_consent'] = 'pi_flexform';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][\Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME . '_cookielist'] = 'recursive,select_key,pages';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME . '_consent',
            'FILE:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Configuration/FlexForms/Settings.xml'
        );
    }
);
