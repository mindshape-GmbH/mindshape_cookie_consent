<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\ExpressionLanguage;

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

use Mindshape\MindshapeCookieConsent\Utility\CookieUtility;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class CookieConsentWrapper
{
    protected bool $consent = false;
    protected array $consentOptions = [];
    protected ?string $languageIsoCode = null;

    public function __construct(string $cookieName, ?SiteLanguage $siteLanguage = null)
    {
        if ($siteLanguage instanceof SiteLanguage) {
            $this->languageIsoCode = $siteLanguage->getLocale()->getLanguageCode();
        }

        $cookieValue = CookieUtility::getCookieValue($cookieName);

        $this->consent = !empty($this->languageIsoCode) && array_key_exists('languageConsent', $cookieValue)
            ? $cookieValue['languageConsent'][$this->languageIsoCode] ?? false
            : $cookieValue['consent'] ?? false;

        $consentOptions = !empty($this->languageIsoCode) && array_key_exists('languageConsent', $cookieValue)
            ? $cookieValue['languageOptions'][$this->languageIsoCode] ?? []
            : $cookieValue['options'] ?? [];

        array_unique($consentOptions);
        sort($consentOptions);

        $this->consentOptions = $consentOptions;
    }

    public function getConsentOptions(): array
    {
        return $this->consentOptions;
    }

    public function hasConsent(): bool
    {
        return $this->consent;
    }

    public function hasOption(string $cookieOption): bool
    {
        return in_array($cookieOption, $this->consentOptions);
    }
}
