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

use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SettingsUtility
{
    public static function pluginTypoScriptSettings(): ?array
    {
        return self::extensionTypoScriptSettings()['settings'] ?? null;
    }

    public static function extensionTypoScriptSettings(): ?array
    {
        /** @var \TYPO3\CMS\Core\Http\ServerRequest $request */
        $request = $GLOBALS['TYPO3_REQUEST'];

        /** @var \TYPO3\CMS\Core\TypoScript\FrontendTypoScript $typoScirpt */
        $frontendTypoScript = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript');

        if ($frontendTypoScript instanceof FrontendTypoScript && !$frontendTypoScript->hasSetup()) {
            $site = $request->getAttribute('site');
            $frontendTypoScript = TypoScriptUtility::getFrontendTypoScriptBySite($site);
        }

        if ($frontendTypoScript instanceof FrontendTypoScript) {
            $typoScript = GeneralUtility::removeDotsFromTS($frontendTypoScript->getSetupArray());

            return $typoScript['plugin']['tx_mindshapecookieconsent'] ?? null;
        }

        return null;
    }
}
