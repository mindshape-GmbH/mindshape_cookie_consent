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
 *  (c) 2026 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use Mindshape\MindshapeCookieConsent\Service\TemplateRenderingService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * @package Mindshape\MindshapeCookieConsent\ViewHelpers
 */
class ConsentViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('identifier', 'string', 'The cookie option identifier to check against', true);
        $this->registerArgument('template', 'string', 'Alternative template', false, 'Default');
        $this->registerArgument('arguments', 'array', 'Additional arguments for the alternative template', false, []);
        $this->registerArgument('scripts', 'array', 'Scripts to inject on consent', false, []);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $cookieConsentService = GeneralUtility::makeInstance(CookieConsentService::class);
        $templateRenderingService = GeneralUtility::makeInstance(TemplateRenderingService::class);

        return $templateRenderingService->render(
            'Replacement',
            $this->arguments['template'],
            array_merge(
                [
                    'replacement' => htmlentities($this->renderChildren() ?? ''),
                    'scripts' => json_encode($this->arguments['scripts']),
                    'cookieOption' => $cookieConsentService->getCookieOptionFromIdentifier($this->arguments['identifier']),
                    'datapolicyPageTypoLink' => $cookieConsentService->getDatapolicyPageTypoLink(),
                    'imprintPageTypoLink' => $cookieConsentService->getImprintPageTypoLink(),
                ],
                $this->arguments['arguments']
            )
        );
    }
}
