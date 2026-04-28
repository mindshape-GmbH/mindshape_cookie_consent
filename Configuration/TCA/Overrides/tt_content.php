<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(
    function () {
        ExtensionUtility::registerPlugin(
            'mindshape_cookie_consent',
            'Consent',
            'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:plugin.consent.title',
            'mindshape_cookie_consent-plugin-consent-icon',
            'special'
        );

        ExtensionUtility::registerPlugin(
            'mindshape_cookie_consent',
            'Cookielist',
            'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:plugin.cookielist.title',
            'mindshape_cookie_consent-plugin-cookielist-icon',
            'special'
        );

        $GLOBALS['TCA']['tt_content']['types']['mindshapecookieconsent_consent']['columnsOverrides']['pi_flexform']['config']['ds'] = 'FILE:EXT:mindshape_cookie_consent/Configuration/FlexForms/Settings.xml';

        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,pi_flexform',
            'mindshapecookieconsent_consent',
            'after:header'
        );
    }
);
