<?php
defined('TYPO3') or die();

// encapsulate all locally defined variables
(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Configuration/TypoScript/constants.typoscript">'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Configuration/TypoScript/setup.typoscript">'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'MindshapeCookieConsent',
        'Consent',
        [\Mindshape\MindshapeCookieConsent\Controller\ConsentController::class => 'settings,consent'],
        [\Mindshape\MindshapeCookieConsent\Controller\ConsentController::class => 'settings,consent']
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY,
        'Cookielist',
        [\Mindshape\MindshapeCookieConsent\Controller\CookieController::class => 'list']
    );

    $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Extensionmanager\Utility\InstallUtility::class,
        'afterExtensionInstall',
        \Mindshape\MindshapeCookieConsent\Signal\ExtensionInstallationSignal::class,
        'afterInstallation'
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = \Mindshape\MindshapeCookieConsent\Hook\RenderPreProcessHook::class . '->preProcess';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \Mindshape\MindshapeCookieConsent\Hook\TCEMainHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = \Mindshape\MindshapeCookieConsent\Hook\TCEMainHook::class;
})();
