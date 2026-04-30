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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\View\TemplateView;
use TYPO3Fluid\Fluid\View\ViewInterface;

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

    public function __construct()
    {
        $extensionTypoScript = SettingsUtility::extensionTypoScriptSettings();

        $this->viewSettings = $extensionTypoScript['view'];
        $this->settings = $extensionTypoScript['settings'];
    }

    /**
     * @param string $templateFolder
     * @param string $templateName
     * @param array $variables
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface|null $renderingContext
     * @return string
     */
    public function render(
        string $templateFolder,
        string $templateName,
        array $variables = [],
        ?RenderingContextInterface $renderingContext = null
    ): string {
        $view = $this->getView($templateFolder, $templateName, $renderingContext);

        if (false === array_key_exists('settings', $variables)) {
            $variables['settings'] = $this->settings;
        }

        $view->assignMultiple($variables);

        return $view->render();
    }

    /**
     * @param string $templateFolder
     * @param string $templateName
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface|null $renderingContext
     * @return \TYPO3Fluid\Fluid\View\ViewInterface
     */
    protected function getView(
        string $templateFolder,
        string $templateName,
        ?RenderingContextInterface $renderingContext = null
    ): ViewInterface {
        /** @var \TYPO3Fluid\Fluid\View\TemplateView $view */
        $view = GeneralUtility::makeInstance(TemplateView::class, $renderingContext);
        $templatePaths = $view->getRenderingContext()->getTemplatePaths();

        // We need the absolute path here for TYPO3Fluid\Fluid\View\TemplatePaths, this works different from TYPO3\CMS\Fluid\View\TemplatePaths
        $templatePaths->setLayoutRootPaths(
            array_map(GeneralUtility::class . '::getFileAbsFileName', $this->viewSettings['layoutRootPaths'])
        );
        $templatePaths->setTemplateRootPaths(
            array_map(GeneralUtility::class . '::getFileAbsFileName', $this->viewSettings['templateRootPaths'])
        );
        $templatePaths->setPartialRootPaths(
            array_map(GeneralUtility::class . '::getFileAbsFileName', $this->viewSettings['partialRootPaths'])
        );

        $templatePaths->setTemplatePathAndFilename(
            $templatePaths->resolveTemplateFileForControllerAndActionAndFormat(
                $templateFolder,
                $templateName
            )
        );

        return $view;
    }
}
