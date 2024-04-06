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
 *  (c) 2024 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

/**
 * @package Mindshape\MindshapeCookieConsent\Utility
 */
class CookieUtility
{
    public const DEFAULT_COOKIE_NAME = 'cookie_consent';

    /**
     * @param string $name
     * @return array|null
     */
    public static function getCookieValue(string $name = self::DEFAULT_COOKIE_NAME): ?array
    {
        if (false === self::hasCookie($name)) {
            return null;
        }

        $cookieValue = json_decode($_COOKIE[$name], true);

        if (!is_array($cookieValue) || JSON_ERROR_NONE !== json_last_error()) {
            return null;
        }

        return $cookieValue;
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function hasCookie(string $name = self::DEFAULT_COOKIE_NAME): bool
    {
        return true === array_key_exists($name, $_COOKIE);
    }

    /**
     * @param string $name
     */
    public static function deleteCookie(string $name = self::DEFAULT_COOKIE_NAME): void
    {
        unset($_COOKIE[$name]);
    }
}
