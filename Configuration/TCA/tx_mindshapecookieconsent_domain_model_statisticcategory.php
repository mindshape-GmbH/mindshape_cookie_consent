<?php

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_category',
        'label' => 'date',
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'searchFields' => 'date,cookieoption,counter',
        'iconfile' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/model_statistic.png',
        'hideTable' => true,
    ],
    'types' => [
        '1' => ['showitem' => 'date, cookie_category, counter'],
    ],
    'palettes' => [],
    'columns' => [
        'configuration' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => Configuration::TABLE,
            ],
        ],
        'date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_category.date',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'date',
                'size' => 12,
                'required' => true,
                'default' => null,
            ],
        ],
        'cookie_category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_category.cookiecategory',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => CookieCategory::TABLE,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'counter' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_category.counter',
            'config' => [
                'type' => 'number',
                'range' => [
                    'lower' => 0,
                ],
            ],
        ],
    ],
];
