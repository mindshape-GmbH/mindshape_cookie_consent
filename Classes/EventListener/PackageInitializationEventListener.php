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

use Mindshape\MindshapeCookieConsent\Service\ExtensionDefaultDataServiceTrait;
use TYPO3\CMS\Core\Package\Event\PackageInitializationEvent;

/**
 * @package Mindshape\MindshapeCookieConsent\EventListener
 */
class PackageInitializationEventListener
{
    use ExtensionDefaultDataServiceTrait;

    /**
     * @param \TYPO3\CMS\Core\Package\Event\PackageInitializationEvent $packageInitializationEvent
     */
    public function __invoke(PackageInitializationEvent $packageInitializationEvent): void
    {
        if ($packageInitializationEvent->getExtensionKey() === 'mindshape_cookie_consent') {
            $this->extensionDefaultDataService->checkAndAddDefaultConfigurations();
        }
    }
}
