<?php

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button',
        'label' => 'date_begin',
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'searchFields' => 'date_begin,date_end,decline,agree,agree_to_all,deny',
        'iconfile' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/model_statistic.png',
        'hideTable' => true,
    ],
    'types' => [
        '1' => ['showitem' => 'date_begin, date_end, save, agree_to_all, deny'],
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
        'date_begin' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.date_start',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'size' => 12,
                'required' => true,
                'default' => null,
            ],
        ],
        'date_end' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.date_end',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'size' => 12,
                'required' => true,
                'default' => null,
            ],
        ],
        'save' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.save',
            'config' => [
                'type' => 'number',
                'range' => [
                    'lower' => 0,
                ],
            ],
        ],
        'agree_to_all' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.agree_to_all',
            'config' => [
                'type' => 'number',
                'range' => [
                    'lower' => 0,
                ],
            ],
        ],
        'deny' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.deny',
            'config' => [
                'type' => 'number',
                'range' => [
                    'lower' => 0,
                ],
            ],
        ],
    ],
];
