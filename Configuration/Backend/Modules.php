<?php

use Mindshape\MindshapeCookieConsent\Controller\Backend\StatisticController;

return [
    'mindshapecookieconsent' => [
        'labels' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/module_locallang.xlf',
        'iconIdentifier' => 'module-mindshapecookieconsent',
        'position' => ['after' => 'site'],
    ],
    'mindshapecookieconsent' . '_statistics' => [
        'parent' => 'mindshapecookieconsent',
        'access' => 'user',
        'iconIdentifier' => 'module-mindshapecookieconsent-statistic',
        'labels' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/module_statistic_locallang.xlf',
        'extensionName' => 'mindshapecookieconsent',
        'controllerActions' => [
            StatisticController::class => [
                'statisticButtons', 'statisticCategories', 'statisticOptions',
            ],
        ],
    ],
];
