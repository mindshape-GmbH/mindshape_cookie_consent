<?php
namespace Mindshape\MindshapeCookieConsent\Service;

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

use Mindshape\MindshapeCookieConsent\Service\ExtensionDefaultDataService;

/**
 * @package Mindshape\MindshapeCookieConsent\EventListener
 */
trait ExtensionDefaultDataServiceTrait
{
    /**
     * @var \Mindshape\MindshapeCookieConsent\Service\ExtensionDefaultDataService
     */
    protected $extensionDefaultDataService;

    /**
     * @param \Mindshape\MindshapeCookieConsent\Service\ExtensionDefaultDataService $extensionDefaultDataService
     */
    public function __construct(ExtensionDefaultDataService $extensionDefaultDataService)
    {
        $this->extensionDefaultDataService = $extensionDefaultDataService;
    }
}
