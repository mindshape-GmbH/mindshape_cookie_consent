<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookieoption',
        'label' => 'label',
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'sortby' => 'sorting',
        'searchFields' => 'label,identifier,provider,purpose,cookie_name,cookie_duration,info',
        'iconfile' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/model_cookieoption.png',
        'hideTable' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'label, identifier, provider, purpose, cookie_name, cookie_duration, replacement_label, info'],
    ],
    'palettes' => [],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption::TABLE,
                'foreign_table_where' => 'AND ' . \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption::TABLE . '.pid=###CURRENT_PID### AND ' . \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption::TABLE . '.sys_language_uid IN (-1,0)',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.enabled',
                    ],
                ],
            ],
        ],

        'cookie_category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookie_category.label',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory::TABLE,
                'default' => 0,
            ],
        ],
        'label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookieoption.label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim,required',
            ],
        ],
        'identifier' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookieoption.identifier',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim,required',
            ],
        ],
        'provider' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookieoption.provider',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'purpose' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookieoption.purpose',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'cookie_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookieoption.cookie_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'cookie_duration' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookieoption.cookie_duration',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'replacement_label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookieoption.replacement_label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'info' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookieoption.info',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
    ],
];
