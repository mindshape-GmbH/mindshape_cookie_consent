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
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('mindshapeCookieConsentDateColumnMigrationUpdateWizard')]
class DateColumnMigrationUpdateWizard implements UpgradeWizardInterface
{
    protected const STATISTIC_TABLES = [
        'tx_mindshapecookieconsent_domain_model_statisticcategory',
        'tx_mindshapecookieconsent_domain_model_statisticoption',
        'tx_mindshapecookieconsent_domain_model_statisticbutton',
    ];

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
        return 'Update statistic records to the new single date column';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Statistic records have been simplified to have just one simple date dolumn';
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
        foreach (self::STATISTIC_TABLES as $tableName) {
            $queryBuilder = DatabaseUtility::queryBuilder();

            $queryBuilder
                ->count('uid')
                ->from($tableName)
                ->where(
                    $queryBuilder->expr()->isNull('date')
                );

            if ($queryBuilder->executeQuery()->fetchOne() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function executeUpdate(): bool
    {
        $queryBuilder = DatabaseUtility::queryBuilder();

        foreach (self::STATISTIC_TABLES as $tableName) {
            $queryBuilder
                ->update($tableName)
                ->set('date', 'date_begin', false)
                ->where(
                    $queryBuilder->expr()->isNull('date')
                );

            $queryBuilder->executeStatement();
        }

        return true;
    }
}
