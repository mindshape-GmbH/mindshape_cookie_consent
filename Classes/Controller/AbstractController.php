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

use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * @package Mindshape\MindshapeCookieConsent\Controller
 */
class AbstractController extends ActionController
{
    /**
     * @var \Mindshape\MindshapeCookieConsent\Service\CookieConsentService
     */
    protected $cookieConsentService;

    /**
     * @param \Mindshape\MindshapeCookieConsent\Service\CookieConsentService $cookieConsentService
     */
    public function injectCookieConsentService(CookieConsentService $cookieConsentService): void
    {
        $this->cookieConsentService = $cookieConsentService;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     */
    public function initializeView(ViewInterface $view)
    {
        parent::initializeView($view);

        $view->assign('data', $this->configurationManager->getContentObject()->data);
    }
}
