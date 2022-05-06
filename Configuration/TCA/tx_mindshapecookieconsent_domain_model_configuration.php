<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration',
        'label' => 'site',
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'searchFields' => 'site,enable_statistic,header,imprint,datapolicy,hint,necessary_cookies_info',
        'iconfile' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/model_configuration.png',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, sys_language_uid, site, enable_statistic, header, imprint, datapolicy, hint, necessary_cookies_info, necessary_cookie_options, cookie_categories, --palette--;LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.palettes.labels;labels'],
    ],
    'palettes' => [
        'labels' => ['showitem' => 'select_all_label, save_label, deny_label, --linebreak--, show_details_label, hide_details_label'],
    ],
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
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration::TABLE,
                'foreign_table_where' => 'AND ' . \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration::TABLE . '.pid=###CURRENT_PID### AND ' . \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration::TABLE . '.sys_language_uid IN (-1,0)',
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

        'site' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.site',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 1,
                'itemsProcFunc' => \Mindshape\MindshapeCookieConsent\UserFunc\ConfigurationTcaUserFunc::class . '->sitesItemsProcFunc',
                'items' => [
                    [
                        'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.site.all',
                        \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration::SITE_ALL_SITES,
                    ],
                ],
            ],
        ],
        'enable_statistic' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.enable_statistic',
            'config' => [
                'type' => 'check',
                'default' => true,
            ],
        ],
        'header' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.header',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'imprint' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.imprint',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'datapolicy' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.datapolicy',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hint' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.hint',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'necessary_cookies_info' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.necessary_cookies_info',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'necessary_cookie_options' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.necessary_cookie_options',
            'config' => [
                'type' => 'inline',
                'foreign_table' => \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption::TABLE,
                'foreign_label' => 'label',
                'foreign_sortby' => 'sorting',
                'minitems' => 0,
                'maxitems' => 10,
                'appearance' => [
                    'newRecordLinkTitle' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.necessary_cookie_options.new_record',
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'collapseAll' => true,
                    'levelLinksPosition' => 'top',
                ],
                'overrideChildTca' => [
                    'types' => [
                        '1' => ['showitem' => 'label, provider, purpose, cookie_name, cookie_duration, info'],
                    ],
                    'columns' => [
                        'identifier' => [
                            'config' => ['eval' => 'trim'],
                        ],
                    ],
                ],
            ],
        ],
        'select_all_label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.select_all_label',
            'config' => [
                'type' => 'input',
                'placeholder' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:label.select_all',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'save_label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.save_label',
            'config' => [
                'type' => 'input',
                'placeholder' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:label.save',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'deny_label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.deny_label',
            'config' => [
                'type' => 'input',
                'placeholder' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:label.deny',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'show_details_label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.show_details_label',
            'config' => [
                'type' => 'input',
                'placeholder' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:label.show_details',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hide_details_label' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.hide_details_label',
            'config' => [
                'type' => 'input',
                'placeholder' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:label.hide_details',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'cookie_categories' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.cookie_categories',
            'config' => [
                'type' => 'inline',
                'foreign_table' => \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory::TABLE,
                'foreign_field' => 'configuration',
                'foreign_label' => 'label',
                'foreign_sortby' => 'sorting',
                'minitems' => 0,
                'maxitems' => 10,
                'appearance' => [
                    'newRecordLinkTitle' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/locallang.xlf:tca.configuration.cookie_categories.new_record',
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
