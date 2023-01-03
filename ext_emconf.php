<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'mindshape Cookie Consent',
    'description' => 'This extension provides functionality to create  a customizable cookie consent for your website. It is developed for flexibility to be customized in accordance to your data security guidelines.',
    'category' => 'plugin',
    'author' => 'Daniel Dorndorf',
    'author_email' => 'dorndorf@mindshape.de',
    'author_company' => 'mindshape GmbH',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'version' => '2.2.5',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.2-11.5.99',
            'php' => '7.2.0-8.0.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
