<?php

namespace Mindshape\MindshapeCookieConsent\Domain\Model;

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

class ConsentCookie
{
    protected bool $consent = false;
    protected array $consentOptions;

    public function __construct(bool $consent, array $consentOptions)
    {
        $this->consent = $consent;
        $this->consentOptions = $consentOptions;
    }

    public function hasConsent(): bool
    {
        return $this->consent;
    }

    public function getCookieOptions(): array
    {
        return $this->consentOptions;
    }
}
