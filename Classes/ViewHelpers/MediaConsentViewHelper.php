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
 *  (c) 2024 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Mindshape\MindshapeCookieConsent\Exception\InvalidFileTypeException;
use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use Mindshape\MindshapeCookieConsent\Service\TemplateRenderingService;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * @package Mindshape\PerbitCookieConsent\ViewHelpers
 */
class MediaConsentViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('file', 'object', 'External Media file, YouTube/Vimeo...', true);
        $this->registerArgument('additionalConfig', 'array', 'This array can hold additional configuration that is passed though to the Renderer object', false, []);
        $this->registerArgument('width', 'string', 'width of the iframe tag');
        $this->registerArgument('height', 'string', 'height of the iframe tag');
    }

    /**
     * @return string
     * @throws \Mindshape\MindshapeCookieConsent\Exception\InvalidFileTypeException
     */
    public function render(): string
    {
        /** @var \TYPO3\CMS\Core\Resource\FileInterface $file */
        $file = $this->arguments['file'];
        $width = $this->arguments['width'];
        $height = $this->arguments['height'];
        $additionalConfig = (array)$this->arguments['additionalConfig'];

        if (is_callable([$file, 'getOriginalResource'])) {
            $file = $file->getOriginalResource();
        }

        $cookieOption = match ($file->getMimeType()) {
            'video/youtube' => 'youtube',
            'video/vimeo' => 'vimeo',
            default => throw new InvalidFileTypeException(
                'Invalid file type "' . $file->getMimeType() . '" for this ViewHelper'
            ),
        };

        /** @var \Mindshape\MindshapeCookieConsent\Service\CookieConsentService $cookieConsentService */
        $cookieConsentService = GeneralUtility::makeInstance(CookieConsentService::class);
        /** @var \TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface $fileRenderer */
        $fileRenderer = GeneralUtility::makeInstance(RendererRegistry::class)->getRenderer($file);

        $iframe = $fileRenderer->render($file, $width, $height, $additionalConfig);
        $previewImage = null;

        if (is_callable([$file, 'getOriginalFile'])) {
            /** @var \TYPO3\CMS\Core\Resource\File $originalFile */
            $originalFile = $file->getOriginalFile();

            /** @var \TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface $onlineMediaHelper */
            $onlineMediaHelper = GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class)->getOnlineMediaHelper($originalFile);

            if ($onlineMediaHelper instanceof OnlineMediaHelperInterface) {
                /** @var \TYPO3\CMS\Core\Resource\ResourceFactory $resourceFactory */
                $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
                $previewImage = $resourceFactory->getFileObjectFromCombinedIdentifier(
                    '0:' . $onlineMediaHelper->getPreviewImage($originalFile)
                );
            }
        }

        return GeneralUtility::makeInstance(TemplateRenderingService::class)->render(
            'Replacement',
            'Media',
            [
                'iframe' => htmlentities($iframe),
                'cookieOption' => $cookieOption,
                'cookieOptionObject' => $cookieConsentService->getCookieOptionFromIdentifier($cookieOption),
                'datapolicyPageTypoLink' => $cookieConsentService->getDatapolicyPageTypoLink(),
                'imprintPageTypoLink' => $cookieConsentService->getImprintPageTypoLink(),
                'previewImage' => $previewImage,
                'width' => $width,
                'height' => $height,
            ]
        );
    }
}
