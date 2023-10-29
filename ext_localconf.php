<?php

use Mindshape\MindshapeCookieConsent\Controller\ConsentController;
use Mindshape\MindshapeCookieConsent\Controller\CookieController;
use Mindshape\MindshapeCookieConsent\Hook\RenderPreProcessHook;
use Mindshape\MindshapeCookieConsent\Hook\TCEMainHook;
use Mindshape\MindshapeCookieConsent\Updates\PluginCTypeMigrationUpdateWizard;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(
    function () {
        ExtensionManagementUtility::addTypoScriptConstants(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . SettingsUtility::EXTENSION_KEY . '/Configuration/TypoScript/constants.typoscript">'
        );

        ExtensionManagementUtility::addTypoScriptSetup(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . SettingsUtility::EXTENSION_KEY . '/Configuration/TypoScript/setup.typoscript">'
        );

        ExtensionUtility::configurePlugin(
            SettingsUtility::EXTENSION_KEY,
            'Consent',
            [ConsentController::class => 'settings,consent'],
            [ConsentController::class => 'consent'],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        ExtensionUtility::configurePlugin(
            SettingsUtility::EXTENSION_KEY,
            'Consentmodal',
            [ConsentController::class => 'modal'],
            [],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        ExtensionUtility::configurePlugin(
            SettingsUtility::EXTENSION_KEY,
            'Cookielist',
            [CookieController::class => 'list'],
            [CookieController::class => ''],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );


        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][PluginCTypeMigrationUpdateWizard::class] = PluginCTypeMigrationUpdateWizard::class;

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = RenderPreProcessHook::class . '->preProcess';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = TCEMainHook::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = TCEMainHook::class;
    }
);
