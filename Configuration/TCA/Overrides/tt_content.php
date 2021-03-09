<?php
defined('TYPO3_MODE') || die();

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
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][\Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME . '_cookielist'] = 'recursive,select_key,pages';
    }
);
