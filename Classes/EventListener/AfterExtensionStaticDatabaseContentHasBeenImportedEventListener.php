<?php

namespace Mindshape\MindshapeCookieConsent\EventListener;

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

use Mindshape\MindshapeCookieConsent\Service\ExtensionDefaultDataServiceTrait;
use TYPO3\CMS\Extensionmanager\Event\AfterExtensionStaticDatabaseContentHasBeenImportedEvent;

/**
 * @package Mindshape\MindshapeCookieConsent\EventListener
 */
class AfterExtensionStaticDatabaseContentHasBeenImportedEventListener
{
    use ExtensionDefaultDataServiceTrait;

    /**
     * @param \TYPO3\CMS\Extensionmanager\Event\AfterExtensionStaticDatabaseContentHasBeenImportedEvent $afterExtensionStaticDatabaseContentHasBeenImportedEvent
     */
    public function __invoke(
        AfterExtensionStaticDatabaseContentHasBeenImportedEvent $afterExtensionStaticDatabaseContentHasBeenImportedEvent
    ): void {
        if ($afterExtensionStaticDatabaseContentHasBeenImportedEvent->getPackageKey() === 'mindshape_cookie_consent') {
            $this->extensionDefaultDataService->checkAndAddDefaultConfigurations();
        }
    }
}
