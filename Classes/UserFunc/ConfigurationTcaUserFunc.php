<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\UserFunc;

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

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package Mindshape\MindshapeApi\UserFunc
 */
class ConfigurationTcaUserFunc
{
    /**
     * @param array $parameters
     * @param \TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems $tcaSelectItems
     */
    public function sitesItemsProcFunc(array $parameters, TcaSelectItems $tcaSelectItems): void
    {
        $items = &$parameters['items'];
        $currentSite = $parameters['row']['site'];

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        $existingConfigurations = DatabaseUtility::queryBuilder()
            ->select('site')
            ->from(Configuration::TABLE)
            ->execute()
            ->fetchAll();

        $existingConfigurations = array_column($existingConfigurations, 'site');

        if (
            true === in_array(Configuration::SITE_ALL_SITES, $existingConfigurations, true) &&
            $currentSite !== Configuration::SITE_ALL_SITES
        ) {
            $items = [];
        }

        foreach ($siteFinder->getAllSites() as $site) {
            if (
                false === in_array($site->getIdentifier(), $existingConfigurations) ||
                $currentSite === $site->getIdentifier()
            ) {
                $items[] = [$site->getIdentifier(), $site->getIdentifier()];
            }
        }
    }
}
