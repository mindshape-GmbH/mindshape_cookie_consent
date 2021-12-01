<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Configuration/TSconfig/ContentElementWizard.typoscript">');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mindshapecookieconsent_domain_model_configuration');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mindshapecookieconsent_domain_model_cookiecategory');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mindshapecookieconsent_domain_model_cookieoption');

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

        $iconRegistry->registerIcon(
            \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '-plugin-consent-icon',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'pie-chart']
        );

        $iconRegistry->registerIcon(
            \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '-plugin-cookielist-icon',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'list']
        );

        $iconRegistry->registerIcon(
            'module-' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Resources/Public/Icons/extension.svg']
        );

        $iconRegistry->registerIcon(
            'module-' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME . '-statistic',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Resources/Public/Icons/module_statistic.png']
        );

        $webModuleIndex = array_search('web', array_keys($GLOBALS['TBE_MODULES'])) + 1;

        $modulesStart = array_slice($GLOBALS['TBE_MODULES'], 0, $webModuleIndex, true);
        $modulesEnd = array_slice($GLOBALS['TBE_MODULES'], $webModuleIndex, null, true);

        $GLOBALS['TBE_MODULES'] = $modulesStart;
        $GLOBALS['TBE_MODULES'] += [\Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME => ''];
        $GLOBALS['TBE_MODULES'] += $modulesEnd;

        $GLOBALS['TBE_MODULES']['_configuration'][\Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME] = [
            'labels' => 'LLL:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/module_locallang.xlf',
            'name' => \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME,
            'workspaces' => 'online',
            'iconIdentifier' => 'module-' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME,
        ];

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'Mindshape.MindshapeCookieConsent',
            \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME,
            'statistic',
            '',
            [
                \Mindshape\MindshapeCookieConsent\Controller\Backend\StatisticController::class => 'statisticButtons,statisticCategories,statisticOptions',
            ],
            [
                'access' => 'user,group',
                'iconIdentifier' => 'module-' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_NAME . '-statistic',
                'labels' => 'LLL:EXT:' . \Mindshape\MindshapeCookieConsent\Utility\SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/module_statistic_locallang.xlf',
            ]
        );
    }
);
