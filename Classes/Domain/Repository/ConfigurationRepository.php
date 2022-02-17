<?php
namespace Mindshape\MindshapeCookieConsent\Domain\Repository;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Repository
 */
class ConfigurationRepository extends Repository
{
    public function initializeObject(): void
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);

        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllLanguages(): QueryResultInterface
    {
        $query = $this->createQuery();

        $query->getQuerySettings()
            ->setLanguageOverlayMode(false)
            ->setRespectSysLanguage(false);

        return $query->execute();
    }

    /**
     * @param int $configurationUid
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration|null
     */
    public function findByUidIgnoreLanguage(int $configurationUid): ?Configuration
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);

        $query->matching(
            $query->equals('uid', $configurationUid)
        );

        return $query->execute()->getFirst();
    }

    /**
     * @param string $siteIdentifier
     * @return \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration|null
     */
    public function findBySiteIdentifier(string $siteIdentifier, int $languageId): ?Configuration
    {
        $query = $this->createQuery();
        $query
            ->getQuerySettings()
            ->setLanguageOverlayMode(false)
            ->setLanguageUid($languageId);

        $query->matching(
            $query->equals('site', $siteIdentifier)
        );

        return $query->execute()->getFirst();
    }
}
