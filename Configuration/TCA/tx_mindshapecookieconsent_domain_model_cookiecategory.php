<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookie_category',
        'label' => 'label',
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'sortby' => 'sorting',
        'searchFields' => 'label,identifier,info',
        'iconfile' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/model_cookiecategory.png',
        'hideTable' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'label, identifier, info, cookie_options'],
    ],
    'palettes' => [],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language'
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory::TABLE,
                'foreign_table_where' => 'AND ' . \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory::TABLE . '.pid=###CURRENT_PID### AND ' . \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory::TABLE . '.sys_language_uid IN (-1,0)',
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

        'configuration' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookie_category.label',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration::TABLE,
            ],
        ],
        'label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookie_category.label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim,required',
            ],
        ],
        'info' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookie_category.info',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'cookie_options' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookie_category.cookie_options',
            'config' => [
                'type' => 'inline',
                'foreign_table' => \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption::TABLE,
                'foreign_field' => 'cookie_category',
                'foreign_label' => 'label',
                'foreign_sortby' => 'sorting',
                'minitems' => 0,
                'maxitems' => 10,
                'appearance' => [
                    'newRecordLinkTitle' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.cookie_category.cookie_options.new_record',
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'collapseAll' => true,
                    'levelLinksPosition' => 'top',
                ],
            ],
        ],
    ],
];
