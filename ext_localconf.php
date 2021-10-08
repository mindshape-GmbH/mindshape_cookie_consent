<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Configuration/TypoScript/constants.typoscript">'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Configuration/TypoScript/setup.typoscript">'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mindshape.' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY,
            'Consent',
            [\Mindshape\MindshapeCookieConsent\Controller\ConsentController::class => 'settings,consent'],
            [\Mindshape\MindshapeCookieConsent\Controller\ConsentController::class => 'settings,consent']
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mindshape.' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY,
            'Cookielist',
            [\Mindshape\MindshapeCookieConsent\Controller\CookieController::class => 'list'],
            [\Mindshape\MindshapeCookieConsent\Controller\CookieController::class => '']
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = \Mindshape\MindshapeCookieConsent\Hook\RenderPreProcessHook::class . '->preProcess';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \Mindshape\MindshapeCookieConsent\Hook\TCEMainHook::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = \Mindshape\MindshapeCookieConsent\Hook\TCEMainHook::class;
    }
);
