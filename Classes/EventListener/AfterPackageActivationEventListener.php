<?php
namespace Mindshape\MindshapeCookieConsent\EventListener;

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

use Mindshape\MindshapeCookieConsent\Service\ExtensionDefaultDataServiceTrait;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;

/**
 * @package Mindshape\MindshapeCookieConsent\EventListener
 */
class AfterPackageActivationEventListener
{
    use ExtensionDefaultDataServiceTrait;

    /**
     * @param \TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent $afterPackageActivationEvent
     */
    public function __invoke(AfterPackageActivationEvent $afterPackageActivationEvent): void
    {
        if (SettingsUtility::EXTENSION_KEY === $afterPackageActivationEvent->getPackageKey()) {
            $this->extensionDefaultDataService->checkAndAddDefaultConfigurations();
        }
    }
}
