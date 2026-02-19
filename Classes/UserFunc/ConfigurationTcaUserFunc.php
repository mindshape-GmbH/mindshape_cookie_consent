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
 *  (c) 2026 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Doctrine\DBAL\ParameterType;
use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
use PDO;
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
    protected SiteFinder $siteFinder;

    public function __construct()
    {
        $this->siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
    }

    /**
     * @param array $parameters
     * @throws \Doctrine\DBAL\Exception
     */
    public function sitesItemsProcFunc(array $parameters): void
    {
        $items = &$parameters['items'];
        $currentSite = null;
        $currentLanguageId = 0;

        if (is_int($parameters['row']['uid'] ?? null)) {
            [$currentSite, $currentLanguageId] = DatabaseUtility::databaseConnection()->select([
                'site',
                'sys_language_uid',
            ], Configuration::TABLE, ['uid' => $parameters['row']['uid']])->fetchNumeric();
        }

        $queryBuilder = DatabaseUtility::queryBuilder();

        $existingConfigurations = $queryBuilder
            ->select('site')
            ->from(Configuration::TABLE)
            ->where(
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter($currentLanguageId, ParameterType::INTEGER)
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();

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
                $items[] = [
                    'value' => $site->getIdentifier(),
                    'label' => $site->getIdentifier(),
                ];
            }
        }
    }
}
