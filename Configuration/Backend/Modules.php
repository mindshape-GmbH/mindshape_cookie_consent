<?php

use Mindshape\MindshapeCookieConsent\Controller\Backend\StatisticController;

return [
    'mindshapecookieconsent' => [
        'labels' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/module_locallang.xlf',
        'iconIdentifier' => 'module-mindshapecookieconsent',
        'position' => ['after' => 'site'],
    ],
    'mindshapecookieconsent_statisticindex' => [
        'parent' => 'mindshapecookieconsent',
        'access' => 'user',
        'path' => '/module/mindshapecookieconsent/statistic',
        'iconIdentifier' => 'module-mindshapecookieconsent-statistic',
        'labels' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/module_statistic_locallang.xlf',
    ],
    'mindshapecookieconsent_statisticbuttons' => [
        'parent' => 'mindshapecookieconsent_statisticindex',
        'access' => 'user',
        'path' => '/module/mindshapecookieconsent/statistic/buttons',
        'routes' => [
            '_default' => [
                'target' => StatisticController::class . '::handleRequest'
            ],
        ],
    ],
    'mindshapecookieconsent_statisticcategories' => [
        'parent' => 'mindshapecookieconsent_statisticindex',
        'access' => 'user',
        'path' => '/module/mindshapecookieconsent/statistic/categories',
        'routes' => [
            '_default' => [
                'target' => StatisticController::class . '::handleRequest'
            ],
        ],
    ],
    'mindshapecookieconsent_statisticoptions' => [
        'parent' => 'mindshapecookieconsent_statisticindex',
        'access' => 'user',
        'path' => '/module/mindshapecookieconsent/statistic/options',
        'routes' => [
            '_default' => [
                'target' => StatisticController::class . '::handleRequest'
            ],
        ],
    ],
];
