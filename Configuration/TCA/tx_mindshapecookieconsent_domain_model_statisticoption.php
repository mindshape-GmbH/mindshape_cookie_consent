<?php

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_option',
        'label' => 'date',
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'searchFields' => 'date,cookieoption,counter',
        'iconfile' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/model_statistic.png',
        'hideTable' => true,
    ],
    'types' => [
        '1' => ['showitem' => 'date, cookie_option, counter'],
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
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_option.date',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'date',
                'size' => 12,
                'required' => true,
                'default' => null,
            ],
        ],
        'cookie_option' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_option.cookiecategory',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => CookieOption::TABLE,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'counter' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_option.counter',
            'config' => [
                'type' => 'number',
                'range' => [
                    'lower' => 0,
                ],
            ],
        ],
    ],
];
