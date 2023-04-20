<?php

namespace Mindshape\MindshapeCookieConsent\Controller;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Mindshape\MindshapeCookieConsent\Domain\Model\Consent;
use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use Mindshape\MindshapeCookieConsent\Utility\LinkUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @package Mindshape\MindshapeCookieConsent\Controller
 */
class ConsentController extends AbstractController
{
    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Consent $consent
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \TYPO3\CMS\Core\Http\PropagateResponseException
     */
    public function consentAction(Consent $consent): ResponseInterface
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
                    throw new PropagateResponseException(new NullResponse(), 400);
                }
            }

            if (true === empty($redirectUrl)) {
                $redirectUrl = LinkUtility::renderPageLink($GLOBALS['TSFE']->id);
            }

            return new RedirectResponse($redirectUrl, 303);
        }

        return new JsonResponse([]);
    }

    public function settingsAction(): ResponseInterface
    {
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);

        CookieConsentService::setCookieButtonIsUsed();

        return $this->htmlResponse();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function modalAction(): ResponseInterface
    {
        $consent = $this->cookieConsentService->getConsentFromCookie();

        if (!$consent instanceof Consent) {
            $consent = new Consent();
        }

        $this->view->assignMultiple([
            'id' => CookieConsentService::DEFAULT_CONTAINER_ID,
            'consent' => $consent,
            'configuration' => $this->cookieConsentService->currentConfiguration(),
            'currentUrl' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            'currentRootPageUid' => $this->cookieConsentService->currentSite()->getRootPageId(),
        ]);

        return $this->htmlResponse();
    }
}
