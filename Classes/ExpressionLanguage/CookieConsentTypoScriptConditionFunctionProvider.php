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
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class CookieConsentTypoScriptConditionFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'cookieConsent',
                static fn() => null,
                static function (array $arguments, string $cookieName = CookieUtility::DEFAULT_COOKIE_NAME): mixed {
                    /** @var \TYPO3\CMS\Core\Site\Entity\SiteLanguage $siteLanguage */
                    $siteLanguage = $arguments['siteLanguage'] ?? null;

                    return new CookieConsentWrapper($cookieName, $siteLanguage);
                }
            ),
        ];
    }
}
