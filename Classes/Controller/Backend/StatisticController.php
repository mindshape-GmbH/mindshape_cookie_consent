<?php

namespace Mindshape\MindshapeCookieConsent\Controller\Backend;

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

use DateTime;
use Exception;
use GeorgRinger\NumberedPagination\NumberedPagination;
use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticOptionRepository;
use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
use Mindshape\MindshapeCookieConsent\Utility\SettingsUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * @package Mindshape\MindshapeCookieConsent\Controller
 */
class StatisticController extends ActionController
{
    /**
     * @var \TYPO3\CMS\Backend\Template\ModuleTemplateFactory
     */
    protected ModuleTemplateFactory $moduleTemplateFactory;

    /**
     * @var \TYPO3\CMS\Backend\Template\ModuleTemplate
     */
    protected ModuleTemplate $moduleTemplate;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration|null
     */
    protected ?Configuration $currentConfiguration;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository
     */
    protected StatisticCategoryRepository $statisticCategoryRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticOptionRepository
     */
    protected StatisticOptionRepository $statisticOptionRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository
     */
    protected StatisticButtonRepository $statisticButtonRepository;

    /**
     * @var \Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository
     */
    protected ConfigurationRepository $configurationRepository;

    /**
     * @param \TYPO3\CMS\Backend\Template\ModuleTemplateFactory $moduleTemplateFactory
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository $statisticCategoryRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticOptionRepository $statisticOptionRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository $statisticButtonRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository $configurationRepository
     */
    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        StatisticCategoryRepository $statisticCategoryRepository,
        StatisticOptionRepository $statisticOptionRepository,
        StatisticButtonRepository $statisticButtonRepository,
        ConfigurationRepository $configurationRepository
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->statisticCategoryRepository = $statisticCategoryRepository;
        $this->statisticOptionRepository = $statisticOptionRepository;
        $this->statisticButtonRepository = $statisticButtonRepository;
        $this->configurationRepository = $configurationRepository;

        $currentConfiguration = null;
        $parameters = GeneralUtility::_GET('tx_mindshapecookieconsent_mindshapecookieconsent_mindshapecookieconsentstatistic');

        if (true === is_array($parameters)) {
            if (true === array_key_exists('configuration', $parameters)) {
                $currentConfiguration = $this->configurationRepository->findByUidIgnoreLanguage((int)$parameters['configuration']);
            }
        }

        if (!$currentConfiguration instanceof Configuration) {
            $currentConfiguration = $this->configurationRepository->findAll()->getFirst();
        }

        $this->currentConfiguration = $currentConfiguration;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function initializeAction(): void
    {
        $this->moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->moduleTemplate->setFlashMessageQueue($this->getFlashMessageQueue());

        if ($this->currentConfiguration instanceof Configuration) {
            $this->buildActionMenu($this->currentConfiguration);
            $this->buildDateMenu($this->currentConfiguration);
        }
    }

    /**
     * @param \DateTime|null $date
     * @param int $page
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function statisticButtonsAction(DateTime $date = null, int $page = 1): ResponseInterface
    {
        $this->moduleTemplate->setTitle(LocalizationUtility::translate('module.statistic.buttons', 'mindshape_cookie_consent'));

        $statisticButtons = null;

        if ($this->currentConfiguration instanceof Configuration) {
            $statisticButtons = $this->statisticActionMethod('StatisticButton', $this->currentConfiguration, $date);
            $this->addPaginationToView($statisticButtons, $page, (int)($this->settings['statisticItemsPerPage'] ?? 10));
        }

        $this->view->assignMultiple([
            'configuration' => $this->currentConfiguration,
            'date' => $date,
            'statisticButtons' => $statisticButtons,
        ]);

        $this->moduleTemplate->setContent($this->view->render());

        return $this->htmlResponse($this->moduleTemplate->renderContent());
    }

    /**
     * @param \DateTime|null $date
     * @param int $page
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function statisticCategoriesAction(DateTime $date = null, int $page = 1): ResponseInterface
    {
        $this->moduleTemplate->setTitle(LocalizationUtility::translate('module.statistic.categories', 'mindshape_cookie_consent'));

        $statisticCategories = [];
        $itemsPerPage = (int)($this->settings['statisticItemsPerPage'] ?? 10);

        if ($this->currentConfiguration instanceof Configuration) {
            $statisticCategories = $this->statisticActionMethod('StatisticCategory', $this->currentConfiguration, $date);
            // Multiply for each category having its own record + all count
            $itemsPerPage *= 1 + $this->currentConfiguration->getCookieCategories()->count();

            $this->addPaginationToView($statisticCategories, $page, $itemsPerPage);
        }

        $this->view->assignMultiple([
            'configuration' => $this->currentConfiguration,
            'date' => $date,
            'statisticCategories' => $statisticCategories,
        ]);

        $this->moduleTemplate->setContent($this->view->render());

        return $this->htmlResponse($this->moduleTemplate->renderContent());
    }

    /**
     * @param \DateTime|null $date
     * @param int $page
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function statisticOptionsAction(DateTime $date = null, int $page = 1): ResponseInterface
    {
        $this->moduleTemplate->setTitle(LocalizationUtility::translate('module.statistic.options', 'mindshape_cookie_consent'));

        $statisticOptions = [];
        $cookieOptions = [];
        $itemsPerPage = (int)($this->settings['statisticItemsPerPage'] ?? 10);

        if ($this->currentConfiguration instanceof Configuration) {
            $statisticOptions = $this->statisticActionMethod('StatisticOption', $this->currentConfiguration, $date);
            // Multiply for each category having its own record + all count
            $cookieOptionsCount = 0;

            /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieCategory $cookieCategory */
            foreach ($this->currentConfiguration->getCookieCategories() as $cookieCategory) {
                $cookieOptionsCount += $cookieCategory->getCookieOptions()->count();

                /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\CookieOption $cookieOption */
                foreach ($cookieCategory->getCookieOptions() as $cookieOption) {
                    $cookieOptions[$cookieOption->getUid()] = $cookieOption;
                }
            }

            $itemsPerPage *= 1 + $cookieOptionsCount;
            ksort($cookieOptions);

            $this->addPaginationToView($statisticOptions, $page, $itemsPerPage);
        }

        $this->view->assignMultiple([
            'configuration' => $this->currentConfiguration,
            'date' => $date,
            'statisticOptions' => $statisticOptions,
            'cookieOptions' => $cookieOptions,
        ]);

        $this->moduleTemplate->setContent($this->view->render());

        return $this->htmlResponse($this->moduleTemplate->renderContent());
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $queryResult
     * @param int $currentPage
     * @param int $itemsPerPage
     */
    protected function addPaginationToView(QueryResultInterface $queryResult, int $currentPage, int $itemsPerPage): void
    {
        $paginator = new QueryResultPaginator($queryResult, $currentPage, $itemsPerPage);
        $pagination = new NumberedPagination($paginator, (int)($this->settings['maximumPaginationLinks'] ?? 0));

        $this->view->assignMultiple([
            'paginator' => $paginator,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param string $entityName
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     * @param \DateTime|null $date
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    protected function statisticActionMethod(string $entityName, Configuration $configuration, DateTime $date = null): QueryResultInterface
    {
        $repositoryName = lcfirst($entityName) . 'Repository';

        if (null === $date) {
            return $this->{$repositoryName}->findAllByConfiguration($configuration);
        }

        $this->view->assign('date', $date);

        return $this->{$repositoryName}->findByMonth($configuration, $date);
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $currentConfiguration
     * @throws \Doctrine\DBAL\Exception
     */
    protected function buildActionMenu(Configuration $currentConfiguration): void
    {
        $actionMenu = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $actionMenu->setIdentifier(SettingsUtility::EXTENSION_KEY . '_action');

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        /** @var \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration */
        foreach ($this->configurationRepository->findAllLanguages() as $configuration) {
            $siteIdentifier = $configuration->getSite();
            $siteLabel = $siteIdentifier;
            $languageLabel = null;

            try {
                $site = $siteFinder->getSiteByIdentifier($siteIdentifier);
            } catch (SiteNotFoundException) {
                $site = null;
            }

            if (Configuration::SITE_ALL_SITES === $siteIdentifier) {
                $siteLabel = LocalizationUtility::translate('tca.configuration.site.all', SettingsUtility::EXTENSION_KEY);
            }

            if ($site instanceof Site) {
                $languageLabel = $site->getLanguageById($configuration->getLanguageUid())->getTitle();
            } elseif (0 < $configuration->getLanguageUid()) {
                if ((new Typo3Version())->getMajorVersion() < 12) {
                    $languageLabel = DatabaseUtility::databaseConnection()->select(
                        ['title'],
                        'sys_language',
                        ['uid' => $configuration->getLanguageUid()]
                    )->fetchOne();
                } else {
                    $languageLabel = LocalizationUtility::translate(
                        'module.statistic.language_id',
                        'mindshape_cookie_consent',
                        [$configuration->getLanguageUid()]
                    );
                }
            }

            if (true === is_string($languageLabel)) {
                $siteLabel .= ' (' . $languageLabel . ')';
            }

            $actionMenu->addMenuItem(
                $actionMenu
                    ->makeMenuItem()
                    ->setTitle(LocalizationUtility::translate('module.statistic.menu.action.buttons', SettingsUtility::EXTENSION_KEY) . ' - ' . $siteLabel)
                    ->setHref($this->uriBuilder->reset()->uriFor('statisticButtons', ['configuration' => $configuration]))
                    ->setActive(
                        'statisticButtonsAction' === $this->actionMethodName &&
                        $configuration->getUid() === $currentConfiguration->getUid()
                    )
            );

            $actionMenu->addMenuItem(
                $actionMenu
                    ->makeMenuItem()
                    ->setTitle(LocalizationUtility::translate('module.statistic.menu.action.categories', SettingsUtility::EXTENSION_KEY) . ' - ' . $siteLabel)
                    ->setHref($this->uriBuilder->reset()->uriFor('statisticCategories', ['configuration' => $configuration]))
                    ->setActive(
                        'statisticCategoriesAction' === $this->actionMethodName &&
                        $configuration->getUid() === $currentConfiguration->getUid()
                    )
            );

            $actionMenu->addMenuItem(
                $actionMenu
                    ->makeMenuItem()
                    ->setTitle(LocalizationUtility::translate('module.statistic.menu.action.options', SettingsUtility::EXTENSION_KEY) . ' - ' . $siteLabel)
                    ->setHref($this->uriBuilder->reset()->uriFor('statisticOptions', ['configuration' => $configuration]))
                    ->setActive(
                        'statisticOptionsAction' === $this->actionMethodName &&
                        $configuration->getUid() === $currentConfiguration->getUid()
                    )
            );
        }

        $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->addMenu($actionMenu);
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $currentConfiguration
     */
    protected function buildDateMenu(Configuration $currentConfiguration): void
    {
        $dateMenu = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $dateMenu->setIdentifier(SettingsUtility::EXTENSION_KEY . '_date');

        $currentAction = substr($this->actionMethodName, 0, -6);

        switch ($currentAction) {
            case 'statisticButtons':
                $availableMonths = $this->statisticButtonRepository->findAvailableMonths(
                    $currentConfiguration->getLanguageUid()
                );
                break;
            case 'statisticCategories':
                $availableMonths = $this->statisticCategoryRepository->findAvailableMonths(
                    $currentConfiguration->getLanguageUid()
                );
                break;
            default:
                return;
        }

        try {
            $currentDate = new DateTime($this->request->getArgument('date'));
        } catch (NoSuchArgumentException|Exception) {
            $currentDate = null;
        }

        $dateMenu->addMenuItem(
            $dateMenu
                ->makeMenuItem()
                ->setTitle(LocalizationUtility::translate('module.statistic.date_select_all', SettingsUtility::EXTENSION_KEY))
                ->setActive(!$currentDate instanceof DateTime)
                ->setHref($this->uriBuilder->reset()->uriFor($currentAction, [
                    'configuration' => $currentConfiguration,
                ]))
        );

        foreach ($availableMonths as $availableMonth) {
            $dateMenu->addMenuItem(
                $dateMenu
                    ->makeMenuItem()
                    ->setTitle($availableMonth->format('Y-m'))
                    ->setActive(
                        $currentDate instanceof DateTime &&
                        $currentDate->format('Y-m') === $availableMonth->format('Y-m')
                    )
                    ->setHref($this->uriBuilder->reset()->uriFor($currentAction, [
                        'date' => $availableMonth->format('c'),
                        'configuration' => $currentConfiguration,
                    ]))
            );
        }

        $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->addMenu($dateMenu);
    }
}
