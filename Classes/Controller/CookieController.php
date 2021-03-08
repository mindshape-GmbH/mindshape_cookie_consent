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

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * @package Mindshape\MindshapeCookieConsent\Controller
 */
class CookieController extends AbstractController
{
    public function listAction(): void
    {
        $this->view->assign(
            'categories',
            $this->cookieConsentService->getCurrentConfigurationCookieCategories()
        );
    }
}
