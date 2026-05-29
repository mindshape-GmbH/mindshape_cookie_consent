<?php

namespace Mindshape\MindshapeCookieConsent\Service;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2026 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Fluid\View\FluidViewFactory;

/**
 * @package Mindshape\MindshapeCookieConsent\Service
 */
class TemplateRenderingService implements SingletonInterface
{
    /**
     * @var array
     */
    protected array $viewSettings;

    /**
     * @var array
     */
    protected array $settings;

    public function __construct(
        protected FluidViewFactory $viewFactory
    ) {
        $extensionTypoScript = SettingsUtility::extensionTypoScriptSettings();

        $this->viewSettings = $extensionTypoScript['view'];
        $this->settings = $extensionTypoScript['settings'];
    }

    /**
     * @param string $templateFolder
     * @param string $templateName
     * @param array $variables
     * @return string
     */
    public function render(
        string $templateFolder,
        string $templateName,
        array $variables = [],
    ): string {
        $view = $this->viewFactory->create(new ViewFactoryData(
            $this->viewSettings['templateRootPaths'],
            $this->viewSettings['partialRootPaths'],
            $this->viewSettings['layoutRootPaths'],
            request: $GLOBALS['TYPO3_REQUEST'] ?? null,
        ));

        if (false === array_key_exists('settings', $variables)) {
            $variables['settings'] = $this->settings;
        }

        $view->assignMultiple($variables);

        return $view->render(
            sprintf('%s/%s', $templateFolder, $templateName)
        );
    }
}
