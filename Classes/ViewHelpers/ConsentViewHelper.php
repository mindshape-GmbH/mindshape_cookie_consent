<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\ViewHelpers;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Closure;
use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use Mindshape\MindshapeCookieConsent\Service\TemplateRenderingService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * @package Mindshape\MindshapeCookieConsent\ViewHelpers
 */
class ConsentViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('identifier', 'string', 'The cookie option identifier to check against', true);
        $this->registerArgument('template', 'string', 'Alternative template', false, 'Default');
        $this->registerArgument('arguments', 'array', 'Additional arguments for the alternative template', false, []);
        $this->registerArgument('scripts', 'array', 'Scripts to inject on consent', false, []);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $cookieConsentService = GeneralUtility::makeInstance(CookieConsentService::class);
        $templateRenderingService = GeneralUtility::makeInstance(TemplateRenderingService::class);

        return $templateRenderingService->render(
            'Replacement',
            $arguments['template'],
            array_merge(
                [
                    'replacement' => htmlentities($renderChildrenClosure()),
                    'scripts' => json_encode($arguments['scripts']),
                    'cookieOption' => $cookieConsentService->getCookieOptionFromIdentifier($arguments['identifier']),
                    'datapolicyPageTypoLink' => $cookieConsentService->getDatapolicyPageTypoLink(),
                    'imprintPageTypoLink' => $cookieConsentService->getImprintPageTypoLink(),
                ],
                $arguments['arguments']
            )
        );
    }
}
