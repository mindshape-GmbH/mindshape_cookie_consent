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
 *  (c) 2021 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
use PDO;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package Mindshape\MindshapeApi\UserFunc
 */
class ConfigurationTcaUserFunc
{
    /**
     * @var \TYPO3\CMS\Core\Site\SiteFinder
     */
    protected $siteFinder;

    public function __construct()
    {
        $this->siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
    }

    /**
     * @param array $parameters
     * @param \TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems $tcaSelectItems
     */
    public function sitesItemsProcFunc(array $parameters, TcaSelectItems $tcaSelectItems): void
    {
        $items = &$parameters['items'];
        $currentSite = $parameters['row']['site'];
        $currentLanguageId = (int) (is_array($parameters['row']['sys_language_uid']) ? $parameters['row']['sys_language_uid'][0] : $parameters['row']['sys_language_uid']);
        $queryBuilder = DatabaseUtility::queryBuilder();

        $existingConfigurations = $queryBuilder
            ->select('site')
            ->from(Configuration::TABLE)
            ->where(
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter($currentLanguageId, PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAll();

        $existingConfigurations = array_column($existingConfigurations, 'site');

        if (
            true === in_array(Configuration::SITE_ALL_SITES, $existingConfigurations, true) &&
            $currentSite !== Configuration::SITE_ALL_SITES
        ) {
            $items = [];
        }

        foreach ($this->siteFinder->getAllSites() as $site) {
            if (
                false === in_array($site->getIdentifier(), $existingConfigurations) ||
                $currentSite === $site->getIdentifier()
            ) {
                $items[] = [$site->getIdentifier(), $site->getIdentifier()];
            }
        }
    }
}
