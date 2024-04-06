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
 *  (c) 2024 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package Mindshape\MindshapeCookieConsent\Utility
 */
class DatabaseUtility
{
    /**
     * @var \TYPO3\CMS\Core\Database\Connection
     */
    private static Connection $databaseConnection;

    /**
     * @return \TYPO3\CMS\Core\Database\Connection
     */
    public static function databaseConnection(): Connection
    {
        if (static::$databaseConnection ?? null instanceof Connection) {
            return static::$databaseConnection;
        }

        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $connection = null;

        try {
            $connection = $connectionPool->getConnectionByName(ConnectionPool::DEFAULT_CONNECTION_NAME);
        } catch (Exception) {
            // ignore
        }

        static::$databaseConnection = $connection;

        return $connection;
    }

    /**
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    public static function queryBuilder(): QueryBuilder
    {
        $connection = static::databaseConnection();

        return $connection->createQueryBuilder();
    }
}
