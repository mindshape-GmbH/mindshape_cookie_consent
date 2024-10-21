<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'mindshape Cookie Consent',
    'description' => 'This extension provides functionality to create  a customizable cookie consent for your website. It is developed for flexibility to be customized in accordance to your data security guidelines.',
    'category' => 'plugin',
    'author' => 'Daniel Dorndorf',
    'author_email' => 'dorndorf@mindshape.de',
    'author_company' => 'mindshape GmbH',
    'state' => 'stable',
    'version' => '4.0.0',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.4.99',
            'typo3' => '12.4.0-13.4.99',
            'numbered_pagination' => '2.1.0-2.1.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
