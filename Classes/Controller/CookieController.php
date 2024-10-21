<?php

namespace Mindshape\MindshapeCookieConsent\Controller;

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2024 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

/**
 * @package Mindshape\MindshapeCookieConsent\Controller
 */
class CookieController extends AbstractController
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $currentConfiguration = $this->cookieConsentService->currentConfiguration();
        $cookieCategories = $this->cookieConsentService->getCurrentConfigurationCookieCategories()->toArray();

        if (
            $currentConfiguration instanceof Configuration &&
            $this->settings['addNecessaryCookieCategoryInList']
        ) {
            $necessaryCookiesCategory = new CookieCategory();
            $necessaryCookiesCategory->setConfiguration($currentConfiguration);
            $necessaryCookiesCategory->setCookieOptions(
                $currentConfiguration->getNecessaryCookieOptions()
            );
            $necessaryCookiesCategory->setLabel(
                LocalizationUtility::translate(
                    'label.necessary_cookies',
                    'MindshapeCookieConsent')
            );
            $necessaryCookiesCategory->setInfo(
                LocalizationUtility::translate(
                    'label.necessary_cookies.info',
                    'MindshapeCookieConsent'
                )
            );

            array_unshift($cookieCategories, $necessaryCookiesCategory);
        }

        $contentObject = $this->request->getAttribute('currentContentObject');

        if ($contentObject instanceof ContentObjectRenderer) {
            $this->view->assign('data', $contentObject->data ?? []);
        }

        $this->view->assign('categories', $cookieCategories);

        return $this->htmlResponse();
    }
}
