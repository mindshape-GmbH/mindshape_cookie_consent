<?php

use Mindshape\MindshapeCookieConsent\Controller\Backend\StatisticController;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;

return [
    SettingsUtility::EXTENSION_NAME => [
        'labels' => 'LLL:EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/module_locallang.xlf',
        'iconIdentifier' => 'module-' . SettingsUtility::EXTENSION_NAME,
        'position' => ['after' => 'site'],
    ],
    SettingsUtility::EXTENSION_NAME . '_statistics' => [
        'parent' => SettingsUtility::EXTENSION_NAME,
        'access' => 'user',
        'iconIdentifier' => 'module-' . SettingsUtility::EXTENSION_NAME . '-statistic',
        'labels' => 'LLL:EXT:' . SettingsUtility::EXTENSION_KEY . '/Resources/Private/Language/module_statistic_locallang.xlf',
        'extensionName' => SettingsUtility::EXTENSION_NAME,
        'controllerActions' => [
            StatisticController::class => [
                'statisticButtons', 'statisticCategories', 'statisticOptions',
            ],
        ],
    ],
];
