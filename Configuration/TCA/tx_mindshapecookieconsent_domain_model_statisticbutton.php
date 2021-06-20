<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button',
        'label' => 'date_begin',
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'searchFields' => 'date_begin,date_end,decline,agree,agree_to_all',
        'iconfile' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/model_statistic.png',
        'hideTable' => true,
    ],
    'interface' => [
        'showRecordFieldList' => 'date_begin, date_end, save, agree_to_all',
    ],
    'types' => [
        '1' => ['showitem' => 'date_begin, date_end, save, agree_to_all'],
    ],
    'palettes' => [],
    'columns' => [
        'configuration' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration::TABLE,
            ],
        ],
        'date_begin' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.date_start',
            'config' => [
                'dbType' => 'datetime',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 12,
                'eval' => 'datetime,required',
                'default' => null,
            ],
        ],
        'date_end' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.date_end',
            'config' => [
                'dbType' => 'datetime',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 12,
                'eval' => 'datetime,required',
                'default' => null,
            ],
        ],
        'save' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.save',
            'config' => [
                'type' => 'input',
                'eval' => 'int',
                'range' => [
                    'lower' => 0,
                ],
            ],
        ],
        'agree_to_all' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.agree_to_all',
            'config' => [
                'type' => 'input',
                'eval' => 'int',
                'range' => [
                    'lower' => 0,
                ],
            ],
        ],
    ],
];
