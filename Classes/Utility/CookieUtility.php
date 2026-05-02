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
 *  (c) 2026 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Mindshape\MindshapeCookieConsent\Domain\Model\ConsentCookie;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

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

    public static function getConsentCookie(string $name = self::DEFAULT_COOKIE_NAME, ?SiteLanguage $siteLanguage = null): ConsentCookie
    {
        if (
            !$siteLanguage instanceof SiteLanguage &&
            ($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface
        ) {
            $siteLanguage = $GLOBALS['TYPO3_REQUEST']->getAttribute('language');
        }

        $cookieValue = self::getCookieValue($name) ?? [];
        $languageIsoCode = $siteLanguage?->getLocale()?->getLanguageCode() ?? null;
        $languageConsent = $cookieValue['languageConsent'] ?? null;
        $languageOptions = $cookieValue['languageOptions'] ?? null;

        $consent = !empty($languageIsoCode) && is_array($languageConsent)
            ? ($languageConsent[$languageIsoCode] ?? false)
            : ($cookieValue['consent'] ?? false);

        $consent = is_bool($consent) ? $consent : false;

        $consentOptions = !empty($languageIsoCode) && is_array($languageOptions)
            ? ($languageOptions[$languageIsoCode] ?? [])
            : ($cookieValue['options'] ?? []);

        if (!is_array($consentOptions)) {
            $consentOptions = [];
        } else {
            $consentOptions = array_filter(
                $consentOptions,
                fn(mixed $consentOption): bool => !empty($consentOption)
            );

            $consentOptions = array_map('strval', $consentOptions);
            $consentOptions = array_unique($consentOptions);

            sort($consentOptions);
        }

        return new ConsentCookie($consent, $consentOptions);
    }
}
