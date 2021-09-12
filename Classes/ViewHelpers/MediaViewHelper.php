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
use Mindshape\MindshapeCookieConsent\Service\TemplateRenderingService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\MediaViewHelper as CoreMediaViewHelper;

/**
 * @package Mindshape\PerbitCookieConsent\ViewHelpers
 */
class MediaViewHelper extends CoreMediaViewHelper
{
    /**
     * @var \Mindshape\MindshapeCookieConsent\Service\CookieConsentService
     */
    protected $cookieConsentService;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Service\TemplateRenderingService
     */
    protected $templateRenderingService;

    public function __construct()
    {
        parent::__construct();

        $this->cookieConsentService = GeneralUtility::makeInstance(CookieConsentService::class);
        $this->templateRenderingService = GeneralUtility::makeInstance(TemplateRenderingService::class);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $media = parent::render();

        if ('<iframe' === substr($media, 0, 7)) {
            $cookieOption = 'media';

            if (1 === preg_match('/src=".*?\/\/(www\.)?(youtube|youtu\.be)/i', $media)) {
                $cookieOption = 'youtube';
            }

            if (1 === preg_match('/src=".*?\/\/(www\.)?(player.)?(vimeo)/i', $media)) {
                $cookieOption = 'vimeo';
            }

            $media = $this->templateRenderingService->render(
                'Replacement',
                'Media',
                [
                    'iframe' => htmlentities($media),
                    'cookieOption' => $cookieOption,
                    'cookieOptionObject' => $this->cookieConsentService->getCookieOptionFromIdentifier($cookieOption),
                    'datapolicyPageTypoLink' => $this->cookieConsentService->getDatapolicyPageTypoLink(),
                    'imprintPageTypoLink' => $this->cookieConsentService->getImprintPageTypoLink(),
                ]
            );
        }

        return $media;
    }
}
