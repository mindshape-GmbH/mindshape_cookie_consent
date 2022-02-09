<?php

namespace Mindshape\MindshapeCookieConsent\Controller;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Mindshape\MindshapeCookieConsent\Domain\Model\Consent;
use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use Mindshape\MindshapeCookieConsent\Utility\LinkUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @package Mindshape\MindshapeCookieConsent\Controller
 */
class ConsentController extends AbstractController
{
    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Consent $consent
     * @return string
     */
    public function consentAction(Consent $consent): string
    {
        if (true === $consent->isDeny()) {
            $consent->setCookieOptions(new ObjectStorage());
        } elseif (true === $consent->isSelectAll()) {
            $consent->setCookieOptions(
                $this->cookieConsentService->getCurrentConfigurationCookieOptions()
            );
        }

        $this->cookieConsentService->updateConsentStatistic($consent);

        if (false === $consent->isAjaxRequest()) {
            $redirectUrl = null;

            if (false === empty($consent->getCurrentUrl())) {
                $redirectUrl = $consent->getCurrentUrl();

                $redirectHost = parse_url($redirectUrl, PHP_URL_HOST);
                $currentHost = GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY');

                if ($redirectHost !== $currentHost) {
                    HttpUtility::setResponseCodeAndExit(HttpUtility::HTTP_STATUS_400);
                }
            }

            if (true === empty($redirectUrl)) {
                $redirectUrl = LinkUtility::renderPageLink($GLOBALS['TSFE']->id);
            }

            HttpUtility::redirect($redirectUrl, HttpUtility::HTTP_STATUS_303);
        }

        return '[]';
    }

    public function settingsAction(): void
    {
        CookieConsentService::setCookieButtonIsUsed();
    }
}
