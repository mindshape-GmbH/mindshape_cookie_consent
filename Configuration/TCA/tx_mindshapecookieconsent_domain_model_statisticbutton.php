<?php

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button',
        'label' => 'date',
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'searchFields' => 'date,decline,agree,agree_to_all,deny',
        'iconfile' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/model_statistic.png',
        'hideTable' => true,
    ],
    'types' => [
        '1' => ['showitem' => 'date, save, agree_to_all, deny'],
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
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.statistic_button.date',
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
