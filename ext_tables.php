<?php

use Mindshape\MindshapeCookieConsent\Controller\Backend\StatisticController;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(
    function () {
        ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . SettingsUtility::EXTENSION_KEY . '/Configuration/TSconfig/ContentElementWizard.typoscript">');

        ExtensionManagementUtility::allowTableOnStandardPages('tx_mindshapecookieconsent_domain_model_configuration');
        ExtensionManagementUtility::allowTableOnStandardPages('tx_mindshapecookieconsent_domain_model_cookiecategory');
        ExtensionManagementUtility::allowTableOnStandardPages('tx_mindshapecookieconsent_domain_model_cookieoption');

        /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);

        $iconRegistry->registerIcon(
            SettingsUtility::EXTENSION_KEY . '-plugin-consent-icon',
            SvgIconProvider::class,
            ['source' => 'EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Public/Icons/plugin-consent.svg']
        );

        $iconRegistry->registerIcon(
            SettingsUtility::EXTENSION_KEY . '-plugin-cookielist-icon',
            SvgIconProvider::class,
            ['source' => 'EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Public/Icons/plugin-cookielist.svg']
        );

        $iconRegistry->registerIcon(
            'module-' . SettingsUtility::EXTENSION_NAME,
            SvgIconProvider::class,
            ['source' => 'EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Public/Icons/Extension.svg']
        );

        $iconRegistry->registerIcon(
            'module-' . SettingsUtility::EXTENSION_NAME . '-statistic',
            BitmapIconProvider::class,
            ['source' => 'EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Public/Icons/module_statistic.png']
        );

        if ((new Typo3Version())->getMajorVersion() < 12) {
            $webModuleIndex = array_search('web', array_keys($GLOBALS['TBE_MODULES'])) + 1;

            $modulesStart = array_slice($GLOBALS['TBE_MODULES'], 0, $webModuleIndex, true);
            $modulesEnd = array_slice($GLOBALS['TBE_MODULES'], $webModuleIndex, null, true);

            $GLOBALS['TBE_MODULES'] = $modulesStart;
            $GLOBALS['TBE_MODULES'] += [SettingsUtility::EXTENSION_NAME => ''];
            $GLOBALS['TBE_MODULES'] += $modulesEnd;

            $GLOBALS['TBE_MODULES']['_configuration'][SettingsUtility::EXTENSION_NAME] = [
                'labels' => 'LLL:EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/module_locallang.xlf',
                'name' => SettingsUtility::EXTENSION_NAME,
                'workspaces' => 'online',
                'iconIdentifier' => 'module-' . SettingsUtility::EXTENSION_NAME,
            ];

            ExtensionUtility::registerModule(
                'MindshapeCookieConsent',
                SettingsUtility::EXTENSION_NAME,
                'statistic',
                '',
                [
                    StatisticController::class => 'statisticButtons,statisticCategories,statisticOptions',
                ],
                [
                    'access' => 'user,group',
                    'iconIdentifier' => 'module-' . SettingsUtility::EXTENSION_NAME . '-statistic',
                    'labels' => 'LLL:EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/module_statistic_locallang.xlf',
                ]
            );
        }
    }
);
