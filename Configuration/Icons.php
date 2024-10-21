<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'mindshape_cookie_consent-plugin-consent-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/plugin-consent.svg'
    ],
    'mindshape_cookie_consent-plugin-cookielist-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/plugin-cookielist.svg'
    ],
    'module-mindshapecookieconsent' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/Extension.svg'
    ],
    'module-mindshapecookieconsent-statistic' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:mindshape_cookie_consent/Resources/Public/Icons/module_statistic.png'
    ],
];
