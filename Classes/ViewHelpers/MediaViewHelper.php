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
use Mindshape\MindshapeCookieConsent\Utility\ObjectUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\MediaViewHelper as CoreMediaViewHelper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package Mindshape\PerbitCookieConsent\ViewHelpers
 */
class MediaViewHelper extends CoreMediaViewHelper
{
    /**
     * @var CookieConsentService
     */
    protected $cookieConsentService;

    /**
     * @var TemplateRenderingService
     */
    protected $templateRenderingService;

    /**
     * @var OnlineMediaHelperRegistry
     */
    protected $onlineMediaHelperRegistry;

    public function __construct()
    {
        parent::__construct();

        $this->cookieConsentService = ObjectUtility::makeInstance(CookieConsentService::class);
        $this->templateRenderingService = ObjectUtility::makeInstance(TemplateRenderingService::class);
        $this->onlineMediaHelperRegistry = OnlineMediaHelperRegistry::getInstance();
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $media = parent::render();

        if ('<iframe' === substr($media, 0, 7)) {
            $cookieOption = 'media';
            $file = $this->arguments['file']->getOriginalFile();
            $previewImage = '';
            $onlineMediaHelper = $this->onlineMediaHelperRegistry->getOnlineMediaHelper($file);

            if ($onlineMediaHelper !== false) {
                $previewImage = $onlineMediaHelper->getPreviewImage($file);

                if (Environment::getVarPath() !== Environment::getPublicPath() . '/typo3temp/var') {
                    $tempFile = str_replace(Environment::getVarPath() . '/transient', Environment::getPublicPath() . '/typo3temp/assets/images', $previewImage);
                    GeneralUtility::writeFileToTypo3tempDir($tempFile, @file_get_contents($previewImage));
                    $previewImage = $tempFile;
                }

                $conf = [
                    'file' => $previewImage,
                    'file.' => [
                        'maxW' => 1200,
                        'maxH' => 1200,
                    ],
                ];

                /** @var ContentObjectRenderer $contentObjectRenderer */
                $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class, $GLOBALS['TSFE']);

                $previewImage = $contentObjectRenderer->cObjGetSingle('IMG_RESOURCE', $conf);
            }

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
                    'previewImage' => $previewImage,
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
