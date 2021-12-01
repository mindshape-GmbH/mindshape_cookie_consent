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
 *  (c) 2018 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

/**
 * @package Mindshape\MindshapeCookieConsent\Utility
 */
class LinkUtility
{
    /**
     * @param int $pageUid
     * @param bool $absolute
     * @return string
     */
    public static function renderPageLink(int $pageUid, bool $absolute = false): string
    {
        return self::renderTypoLink('t3://page?uid=' . $pageUid, $absolute);
    }

    /**
     * @param string $typoLink
     * @return int|null
     */
    public static function parseTypoLinkPageUid(string $typoLink): ?int
    {
        if (true === is_numeric($typoLink)) {
            return (int) $typoLink;
        } elseif (1 === preg_match('/t3:\/\/page\?uid=(\d+)/i', $typoLink, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * @param string $parameter
     * @param bool $absolute
     * @return string
     */
    public static function renderTypoLink(string $parameter, bool $absolute = false): string
    {
        /** @var ContentObjectRenderer $contentObjectRenderer */
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        return $contentObjectRenderer->typoLink_URL([
            'parameter' => $parameter,
            'forceAbsoluteUrl' => $absolute,
        ]);
    }
}
