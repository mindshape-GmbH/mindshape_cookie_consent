<?php

namespace Mindshape\MindshapeCookieConsent\EventListener;

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

use Mindshape\MindshapeCookieConsent\Utility\CookieUtility;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Frontend\Event\BeforePageCacheIdentifierIsHashedEvent;

class BeforePageCacheIdentifierIsHashedEventListener
{
    protected bool $addConsentOptionsToPageCacheIdentifier = false;

    public function __construct(ExtensionConfiguration $extensionConfiguration)
    {
        try {
            $this->addConsentOptionsToPageCacheIdentifier = $extensionConfiguration->get('mindshape_cookie_consent', 'addConsentOptionsToPageCacheIdentifier');
        } catch (ExtensionConfigurationPathDoesNotExistException|ExtensionConfigurationExtensionNotConfiguredException) {
            $this->addConsentOptionsToPageCacheIdentifier = false;
        }
    }

    /**
     * @param \TYPO3\CMS\Core\Package\Event\PackageInitializationEvent $packageInitializationEvent
     */
    public function __invoke(BeforePageCacheIdentifierIsHashedEvent $beforePageCacheIdentifierIsHashedEvent): void
    {
        if (!$this->addConsentOptionsToPageCacheIdentifier) {
            return;
        }

        $pageCacheIdentifierParameters = $beforePageCacheIdentifierIsHashedEvent->getPageCacheIdentifierParameters();

        $pageCacheIdentifierParameters['consentOptions'] = CookieUtility::getConsentCookie()->getCookieOptions();

        $beforePageCacheIdentifierIsHashedEvent->setPageCacheIdentifierParameters($pageCacheIdentifierParameters);
    }
}
