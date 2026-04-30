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
 *  (c) 2026 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Doctrine\DBAL\Exception;
use JsonException;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScriptFactory;
use TYPO3\CMS\Core\TypoScript\IncludeTree\SysTemplateRepository;
use TYPO3\CMS\Core\TypoScript\IncludeTree\SysTemplateTreeBuilder;
use TYPO3\CMS\Core\TypoScript\IncludeTree\Traverser\ConditionVerdictAwareIncludeTreeTraverser;
use TYPO3\CMS\Core\TypoScript\IncludeTree\Traverser\IncludeTreeTraverser;
use TYPO3\CMS\Core\TypoScript\Tokenizer\LossyTokenizer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

class TypoScriptUtility
{
    public static function getFrontendTypoScriptBySite(SiteInterface $site): ?FrontendTypoScript
    {
        try {
            $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $site->getRootPageId())->get();
        } catch (Exception) {
            return null;
        }

        /** @var SysTemplateRepository $sysTemplateRepository */
        $sysTemplateRepository = GeneralUtility::makeInstance(SysTemplateRepository::class);

        $sysTemplateRows = $sysTemplateRepository->getSysTemplateRowsByRootline($rootline);

        $frontendTypoScriptFactory = GeneralUtility::makeInstance(
            FrontendTypoScriptFactory::class,
            GeneralUtility::makeInstance(ContainerInterface::class),
            GeneralUtility::makeInstance(EventDispatcherInterface::class),
            GeneralUtility::makeInstance(SysTemplateTreeBuilder::class),
            GeneralUtility::makeInstance(LossyTokenizer::class),
            GeneralUtility::makeInstance(IncludeTreeTraverser::class),
            GeneralUtility::makeInstance(ConditionVerdictAwareIncludeTreeTraverser::class),
        );

        $frontendTypoScript = $frontendTypoScriptFactory->createSettingsAndSetupConditions(
            $site,
            $sysTemplateRows,
            [],
            null,
        );

        try {
            $frontendTypoScript = $frontendTypoScriptFactory->createSetupConfigOrFullSetup(
                true,
                $frontendTypoScript,
                $site,
                $sysTemplateRows,
                [],
                '0',
                null,
                null,
            );
        } catch (JsonException) {
            $frontendTypoScript = null;
        }

        return $frontendTypoScript;
    }
}
