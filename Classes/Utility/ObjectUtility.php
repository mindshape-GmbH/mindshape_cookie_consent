<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\Utility;

/***
 *
 * This file is part of the "mindshape Cookie Consent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @package Mindshape\MindshapeCookieConsent\Utility
 */
class ObjectUtility
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected static $objectManager;

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public static function makeInstance(string $className, ...$arguments): object
    {
        if (!static::$objectManager instanceof ObjectManager) {
            /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
            static::$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        }

        return static::$objectManager->get($className, ...$arguments);
    }
}
