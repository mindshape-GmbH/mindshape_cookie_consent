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

use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use Mindshape\MindshapeCookieConsent\Utility\ObjectUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * @package Mindshape\MindshapeCookieConsent\ViewHelpers
 */
class ConsentModalViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('id', 'string', 'The id of the cookie consent container', false, CookieConsentService::DEFAULT_CONTAINER_ID);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        /** @var \Mindshape\MindshapeCookieConsent\Service\CookieConsentService $cookieConsentService */
        $cookieConsentService = ObjectUtility::makeInstance(CookieConsentService::class);

        return $cookieConsentService->renderConsentModal($arguments['id']);
    }
}
