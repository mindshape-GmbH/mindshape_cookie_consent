<?php

namespace Mindshape\MindshapeCookieConsent\Controller;

use Psr\Http\Message\ResponseInterface;

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

/**
 * @package Mindshape\MindshapeCookieConsent\Controller
 */
class CookieController extends AbstractController
{
    public function listAction(): ResponseInterface
    {
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);

        $this->view->assign(
            'categories',
            $this->cookieConsentService->getCurrentConfigurationCookieCategories()
        );

        return $this->htmlResponse();
    }
}
