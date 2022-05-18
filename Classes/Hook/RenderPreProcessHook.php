<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\Hook;

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
use Mindshape\MindshapeCookieConsent\Utility\CookieUtility;
use Mindshape\MindshapeCookieConsent\Utility\LinkUtility;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * @package Mindshape\MindshapeCookieConsent\Hook
 */
class RenderPreProcessHook
{
    /**
     * @param array $params
     * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
     */
    public function preProcess(array &$params, PageRenderer $pageRenderer): void
    {
        if (true === ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
            /** @var \Mindshape\MindshapeCookieConsent\Service\CookieConsentService $cookieConsentService */
            $cookieConsentService = GeneralUtility::makeInstance(CookieConsentService::class);
            $datapolicyPageUid = null;
            $imprintPageUid = null;

            if (null !== $cookieConsentService->getDatapolicyPageTypoLink()) {
                $datapolicyPageUid = LinkUtility::parseTypoLinkPageUid($cookieConsentService->getDatapolicyPageTypoLink());
            }

            if (null !== $cookieConsentService->getImprintPageTypoLink()) {
                $imprintPageUid = LinkUtility::parseTypoLinkPageUid($cookieConsentService->getImprintPageTypoLink());
            }

            $currentPageUid = (int)$GLOBALS['TSFE']->id;
            $isInitialHidePage = $currentPageUid === $datapolicyPageUid || $currentPageUid === $imprintPageUid;
            $settings = SettingsUtility::pluginTypoScriptSettings();

            if (true === is_array($settings) && false === (bool)$settings['disableConsent']) {
                if (true === (bool)$settings['addConfiguration']) {
                    $javaScriptConfiguration = [
                        'cookieName' => $settings['cookieName'] ?? CookieUtility::DEFAULT_COOKIE_NAME,
                        'expiryDays' => (int)$settings['expiryDays'],
                        'hideOnInit' => $isInitialHidePage,
                        'pushConsentToTagManager' => (bool)$settings['pushConsentToTagManager'],
                        'lazyloading' => (bool)$settings['lazyloading'],
                        'lazyloadingTimeout' => (int)$settings['lazyloadingTimeout'],
                        'containerId' => false === empty($settings['containerId'])
                            ? $settings['containerId']
                            : CookieConsentService::DEFAULT_CONTAINER_ID,
                    ];

                    if (true === (bool)$settings['addLanguageToCookie']) {
                        /** @var \TYPO3\CMS\Core\Site\Entity\SiteLanguage $siteLanguage */
                        $siteLanguage = $GLOBALS['TYPO3_REQUEST']->getAttribute('language');

                        $javaScriptConfiguration['currentLanguageCode'] = $siteLanguage instanceof SiteLanguage
                            ? $siteLanguage->getTwoLetterIsoCode()
                            : $pageRenderer->getLanguage();
                    }

                    $pageRenderer->addHeaderData('<script data-ignore="1">const cookieConsentConfiguration = JSON.parse(\'' . json_encode($javaScriptConfiguration) . '\');</script>');
                }

                if (true === (bool)$settings['addJavaScript']) {
                    $pageRenderer->addJsFooterLibrary(
                        'cookie_consent',
                        PathUtility::getAbsoluteWebPath('typo3conf/ext/mindshape_cookie_consent/Resources/Public/JavaScript/cookie_consent.js')
                    );
                }

                if (true === (bool)$settings['addStylesheet']) {
                    $pageRenderer->addCssFile(
                        PathUtility::getAbsoluteWebPath('typo3conf/ext/mindshape_cookie_consent/Resources/Public/Stylesheet/cookie_consent.css')
                    );
                }

                if (true === (bool)$settings['addMarkupToFooter']) {
                    $pageRenderer->addFooterData($cookieConsentService->renderConsentModal());
                }
            }
        }
    }
}
