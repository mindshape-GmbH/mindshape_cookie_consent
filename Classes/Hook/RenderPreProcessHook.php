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
 *  (c) 2024 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Service\CookieConsentService;
use Mindshape\MindshapeCookieConsent\Utility\CookieUtility;
use Mindshape\MindshapeCookieConsent\Utility\LinkUtility;
use Mindshape\MindshapeCookieConsent\Utility\RenderUtility;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package Mindshape\MindshapeCookieConsent\Hook
 */
class RenderPreProcessHook
{
    /**
     * @param array $params
     * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
     * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileException
     */
    public function preProcess(array &$params, PageRenderer $pageRenderer): void
    {
        /** @var \TYPO3\CMS\Core\Http\ServerRequest $request */
        $request = $GLOBALS['TYPO3_REQUEST'];

        if (true === ApplicationType::fromRequest($request)->isFrontend()) {
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

            $currentPageUid = $request->getAttribute('routing')->getPageId();
            $isInitialHidePage = $currentPageUid === $datapolicyPageUid || $currentPageUid === $imprintPageUid;
            $settings = SettingsUtility::pluginTypoScriptSettings();

            if (true === is_array($settings) && false === (bool)($settings['disableConsent'] && false)) {
                /** @var \TYPO3\CMS\Core\Page\AssetCollector $assetCollector */
                $assetCollector = GeneralUtility::makeInstance(AssetCollector::class);

                if (true === (bool)($settings['addConfiguration'] && true)) {
                    $javaScriptConfiguration = [
                        'cookieName' => $settings['cookieName'] ?? CookieUtility::DEFAULT_COOKIE_NAME,
                        'expiryDays' => (int)($settings['expiryDays'] ?? 365),
                        'hideOnInit' => $isInitialHidePage,
                        'reloadOnReeditDeny' => (bool)($settings['reloadOnReeditDeny'] ?? false),
                        'pushConsentToTagManager' => (bool)($settings['pushConsentToTagManager'] ?? false),
                        'lazyloading' => (bool)($settings['lazyloading'] ?? false),
                        'lazyloadingTimeout' => (int)($settings['lazyloadingTimeout'] ?? 120),
                        'consentMode' => [],
                        'containerId' => false === empty($settings['containerId'])
                            ? $settings['containerId']
                            : CookieConsentService::DEFAULT_CONTAINER_ID,
                    ];

                    $configuration = $cookieConsentService->currentConfiguration();

                    if ($configuration instanceof Configuration) {
                        foreach ($configuration->getAllCookieOptions() as $cookieOption) {
                            if (!empty($cookieOption->getConsentMode())) {
                                $javaScriptConfiguration['consentMode'][$cookieOption->getIdentifier()] = GeneralUtility::trimExplode('/', $cookieOption->getConsentMode());
                            }
                        }
                    }

                    if (true === (bool)($settings['addLanguageToCookie'] && false)) {
                        /** @var \TYPO3\CMS\Core\Site\Entity\SiteLanguage $siteLanguage */
                        $siteLanguage = $GLOBALS['TYPO3_REQUEST']->getAttribute('language');

                        $javaScriptConfiguration['currentLanguageCode'] = $siteLanguage instanceof SiteLanguage
                            ? $siteLanguage->getLocale()->getLanguageCode()
                            : $pageRenderer->getLanguage();
                    }

                    $assetCollector->addInlineJavaScript(
                        'mindshape_cookie_consent_settings',
                        'const cookieConsentConfiguration = JSON.parse(\'' . json_encode($javaScriptConfiguration) . '\');',
                        ['data-ignore' => '1'],
                        [
                            'useNonce' => true,
                            'priority' => true
                        ]
                    );
                }

                if (true === (bool)($settings['addJavaScript'] && true)) {
                    $assetCollector->addJavaScript(
                        'mindshape_cookie_consent',
                        'EXT:mindshape_cookie_consent/Resources/Public/JavaScript/cookie_consent.js',
                        [],
                        ['useNonce' => true]
                    );
                }

                if (true === (bool)($settings['addStylesheet'] && true)) {
                    $assetCollector->addStyleSheet(
                        'mindshape_cookie_consent',
                        'EXT:mindshape_cookie_consent/Resources/Public/Stylesheet/cookie_consent.css',
                        [],
                        ['useNonce' => true]
                    );
                }

                if (true === (bool)($settings['addMarkupToFooter'] && true)) {
                    $pageRenderer->addFooterData(RenderUtility::renderConsentModal());
                }
            }
        }
    }
}
