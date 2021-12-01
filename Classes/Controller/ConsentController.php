<?php

namespace Mindshape\MindshapeCookieConsent\Controller;

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

use Mindshape\MindshapeCookieConsent\Domain\Model\Consent;
use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use Mindshape\MindshapeCookieConsent\Utility\LinkUtility;
use Psr\Http\Message\ResponseFactoryInterface;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * @package Mindshape\MindshapeCookieConsent\Controller
 */
class ConsentController extends AbstractController
{
    // Inject PSR-17 ResponseFactoryInterface
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
   }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Consent $consent
     * @return string
     */
    public function consentAction(Consent $consent): string
    {
        if (true === $consent->isSelectAll()) {
            $consent->setCookieOptions(
                $this->cookieConsentService->getCurrentConfigurationCookieOptions()
            );
        }

        $this->cookieConsentService->updateConsentStatistic($consent);

        if (false === $consent->isAjaxRequest()) {
            $redirectUrl = true === empty($consent->getCurrentUrl())
                ? LinkUtility::renderPageLink($GLOBALS['TSFE']->id)
                : $consent->getCurrentUrl();

            $response = $this->responseFactory
                ->createResponse(303)
                ->withAddedHeader('location', $redirectUrl);
            return $response;
        }

        return '[]';
    }

    public function settingsAction(): void
    {
        CookieConsentService::setCookieButtonIsUsed();
    }
}
