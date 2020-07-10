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
 *  (c) 2020 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Closure;
use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use Mindshape\MindshapeCookieConsent\Service\TemplateRenderingService;
use Mindshape\MindshapeCookieConsent\Utility\ObjectUtility;
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
        $cookieConsentService = ObjectUtility::makeInstance(CookieConsentService::class);
        $templateRenderingService = ObjectUtility::makeInstance(TemplateRenderingService::class);

        return $templateRenderingService->render(
            'Replacement',
            $arguments['template'],
            [
                'replacement' => htmlentities($renderChildrenClosure()),
                'scripts' => json_encode($arguments['scripts']),
                'cookieOption' => $cookieConsentService->getCookieOptionFromIdentifier($arguments['identifier']),
                'datapolicyPageTypoLink' => $cookieConsentService->getDatapolicyPageTypoLink(),
                'imprintPageTypoLink' => $cookieConsentService->getImprintPageTypoLink(),
            ]
        );
    }
}
