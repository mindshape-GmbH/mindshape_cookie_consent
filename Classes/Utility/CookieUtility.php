<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\Utility;

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

/**
 * @package Mindshape\MindshapeCookieConsent\Utility
 */
class CookieUtility
{
    public const COOKIE_CONSENT = 'cookie_consent';

    /**
     * @return array|null
     */
    public static function getCookieValue(): ?array
    {
        if (false === self::hasCookie()) {
            return null;
        }

        return json_decode($_COOKIE[self::COOKIE_CONSENT], true);
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function hasCookie(string $name = self::COOKIE_CONSENT): bool
    {
        return true === array_key_exists($name, $_COOKIE);
    }

    /**
     * @param string $name
     */
    public static function deleteCookie(string $name = self::COOKIE_CONSENT): void
    {
        unset($_COOKIE[$name]);
    }
}
