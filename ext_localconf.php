<?php

use Mindshape\MindshapeCookieConsent\Controller\ConsentController;
use Mindshape\MindshapeCookieConsent\Controller\CookieController;
use Mindshape\MindshapeCookieConsent\Hook\RenderPreProcessHook;
use Mindshape\MindshapeCookieConsent\Hook\TCEMainHook;
use Mindshape\MindshapeCookieConsent\Updates\PluginCTypeMigrationUpdateWizard;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(
    function () {
        ExtensionManagementUtility::addTypoScriptConstants(
            '@import \'EXT:mindshape_cookie_consent/Configuration/TypoScript/constants.typoscript\''
        );

        ExtensionManagementUtility::addTypoScriptSetup(
            '@import \'EXT:mindshape_cookie_consent/Configuration/TypoScript/setup.typoscript\''
        );

        ExtensionUtility::configurePlugin(
            'mindshape_cookie_consent',
            'Consent',
            [ConsentController::class => 'settings,consent'],
            [ConsentController::class => 'consent'],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        ExtensionUtility::configurePlugin(
            'mindshape_cookie_consent',
            'Consentmodal',
            [ConsentController::class => 'modal'],
            [],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        ExtensionUtility::configurePlugin(
            'mindshape_cookie_consent',
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
