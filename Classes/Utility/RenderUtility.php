<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

/**
 * @package Mindshape\MindshapeCookieConsent\Utility
 */
class RenderUtility
{
    /**
     * @return string
     */
    public static function renderConsentModal(): string
    {
        /** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObjectRenderer */
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        return $contentObjectRenderer->cObjGetSingle(
            'USER',
            [
                'userFunc' => 'TYPO3\CMS\Extbase\Core\Bootstrap->run',
                'extensionName' => 'MindshapeCookieConsent',
                'pluginName' => 'Consentmodal',
            ]
        );
    }
}
