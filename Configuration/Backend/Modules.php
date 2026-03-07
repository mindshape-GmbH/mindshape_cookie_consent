<?php
declare(strict_types=1);

use Mindshape\MindshapeCookieConsent\Controller\Backend\StatisticController;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die();

/** @var \TYPO3\CMS\Core\Configuration\ExtensionConfiguration $extensionConfiguration */
$extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);

try {
    $isDisableStatisticBackendModule = $extensionConfiguration->get('mindshape_cookie_consent', 'disableStatisticBackendModule');
} catch (ExtensionConfigurationPathDoesNotExistException|ExtensionConfigurationExtensionNotConfiguredException) {
    $isDisableStatisticBackendModule = false;
}

if ($isDisableStatisticBackendModule) {
    return [];
}

return [
    'mindshapecookieconsent' => [
        'labels' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/module_locallang.xlf',
        'iconIdentifier' => 'module-mindshapecookieconsent',
        'position' => ['after' => 'site'],
    ],
    'mindshapecookieconsent_statisticindex' => [
        'parent' => 'mindshapecookieconsent',
        'access' => 'user',
        'path' => '/module/mindshapecookieconsent/statistic',
        'iconIdentifier' => 'module-mindshapecookieconsent-statistic',
        'labels' => 'LLL:EXT:mindshape_cookie_consent/Resources/Private/Language/module_statistic_locallang.xlf',
    ],
    'mindshapecookieconsent_statisticbuttons' => [
        'parent' => 'mindshapecookieconsent_statisticindex',
        'access' => 'user',
        'path' => '/module/mindshapecookieconsent/statistic/buttons',
        'routes' => [
            '_default' => [
                'target' => StatisticController::class . '::handleRequest'
            ],
        ],
    ],
    'mindshapecookieconsent_statisticcategories' => [
        'parent' => 'mindshapecookieconsent_statisticindex',
        'access' => 'user',
        'path' => '/module/mindshapecookieconsent/statistic/categories',
        'routes' => [
            '_default' => [
                'target' => StatisticController::class . '::handleRequest'
            ],
        ],
    ],
    'mindshapecookieconsent_statisticoptions' => [
        'parent' => 'mindshapecookieconsent_statisticindex',
        'access' => 'user',
        'path' => '/module/mindshapecookieconsent/statistic/options',
        'routes' => [
            '_default' => [
                'target' => StatisticController::class . '::handleRequest'
            ],
        ],
    ],
];
