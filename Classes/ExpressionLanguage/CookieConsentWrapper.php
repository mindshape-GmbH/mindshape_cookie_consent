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

use Mindshape\MindshapeCookieConsent\Domain\Model\ConsentCookie;
use Mindshape\MindshapeCookieConsent\Utility\CookieUtility;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class CookieConsentWrapper
{
    protected ConsentCookie $consentCookie;

    public function __construct(string $cookieName, ?SiteLanguage $siteLanguage = null)
    {
        $this->consentCookie = CookieUtility::getConsentCookie($cookieName, $siteLanguage);
    }

    public function getConsentOptions(): array
    {
        return $this->consentCookie->getCookieOptions();
    }

    public function hasConsent(): bool
    {
        return $this->consentCookie->hasConsent();
    }

    public function hasOption(string $cookieOption): bool
    {
        return in_array($cookieOption, $this->consentCookie->getCookieOptions(), true);
    }
}
