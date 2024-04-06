<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\Updates;

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

use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
use PDO;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class PluginCTypeMigrationUpdateWizard implements UpgradeWizardInterface
{
    /**
     * This method is still necessary in TYPO3 v11
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::class;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Update old plugin registration style';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Having the vendor name in the plugin registration'
            . ' is not needed anymore, and breaks the plugin in v12.'
            . ' This update migrates your existing plugins to an own CType.';
    }

    /**
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [];
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function updateNecessary(): bool
    {
        return count($this->getOldPluginRecords()) > 0;
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function executeUpdate(): bool
    {
        $queryBuilder = DatabaseUtility::queryBuilder();
        $pluginRecords = $this->getOldPluginRecords();

        foreach ($pluginRecords as $pluginRecord) {
            $queryBuilder
                ->update('tt_content')
                ->set(
                    'CType',
                    match ($pluginRecord['list_type']) {
                        'mindshapecookieconsent_consent', 'mindshape.mindshapecookieconsent_consent' => 'mindshapecookieconsent_consent',
                        'mindshapecookieconsent_cookielist', 'mindshape.mindshapecookieconsent_cookielist' => 'mindshapecookieconsent_cookielist',
                    }
                )
                ->set('list_type', '')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($pluginRecord['uid'], PDO::PARAM_INT)
                    )
                )
                ->executeStatement();
        }

        return true;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    protected function getOldPluginRecords(): array
    {
        $queryBuilder = DatabaseUtility::queryBuilder();

        return $queryBuilder
            ->select('uid', 'list_type')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list')),
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter('mindshape.mindshapecookieconsent_consent')
                    ),
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter('mindshape.mindshapecookieconsent_cookielist')
                    ),
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter('mindshapecookieconsent_consent')
                    ),
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter('mindshapecookieconsent_cookielist')
                    )
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
