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
use Mindshape\MindshapeCookieConsent\Domain\Model\Configuration;
use Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository;
use Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticOptionRepository;
use Mindshape\MindshapeCookieConsent\Utility\DatabaseUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * @package Mindshape\MindshapeCookieConsent\Controller
 */
class StatisticController
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
    protected ?Configuration $currentConfiguration = null;

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
     * @var array
     */
    protected array $settings;

    /**
     * @var string
     */
    protected string $currentRoute;

    /**
     * @param \TYPO3\CMS\Backend\Template\ModuleTemplateFactory $moduleTemplateFactory
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticCategoryRepository $statisticCategoryRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticOptionRepository $statisticOptionRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\StatisticButtonRepository $statisticButtonRepository
     * @param \Mindshape\MindshapeCookieConsent\Domain\Repository\ConfigurationRepository $configurationRepository
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager
     */
    public function __construct(
        ModuleTemplateFactory       $moduleTemplateFactory,
        StatisticCategoryRepository $statisticCategoryRepository,
        StatisticOptionRepository   $statisticOptionRepository,
        StatisticButtonRepository   $statisticButtonRepository,
        ConfigurationRepository     $configurationRepository,
        ConfigurationManager        $configurationManager,
    )
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->statisticCategoryRepository = $statisticCategoryRepository;
        $this->statisticOptionRepository = $statisticOptionRepository;
        $this->statisticButtonRepository = $statisticButtonRepository;
        $this->configurationRepository = $configurationRepository;

        $this->settings = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'mindshapeCookieConsent'
        );
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Doctrine\DBAL\Exception
     * @throws \TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException
     */
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->moduleTemplate = $this->moduleTemplateFactory->create($request);
        $this->currentRoute = $request->getAttribute('moduleData')->getModuleIdentifier();
        $this->moduleTemplate->assign('currentRoute', $this->currentRoute);

        $currentConfiguration = null;
        $currentConfigurationId = null;
        $parameters = $request->getQueryParams();

        if (array_key_exists('configuration', $parameters)) {
            $currentConfigurationId = (int)$parameters['configuration'];
        }

        if (is_int($currentConfigurationId) && $currentConfigurationId > 0) {
            $currentConfiguration = $this->configurationRepository->findByUidIgnoreLanguage($currentConfigurationId);
        }

        if (!$currentConfiguration instanceof Configuration) {
            $currentConfiguration = $this->configurationRepository->findAll()->getFirst();
        }

        $this->currentConfiguration = $currentConfiguration;

        $page = 1;
        $currentDate = null;

        if (array_key_exists('page', $parameters)) {
            $page = (int)$parameters['page'];
        }

        if (array_key_exists('date', $parameters) && !empty($parameters['date'])) {
            try {
                $currentDate = new DateTime($parameters['date']);
            } catch (Exception) {
                // just ignore invalid date parameters
            }
        }

        if ($currentConfiguration instanceof Configuration) {
            $this->buildRouteMenu($currentConfiguration);
            $this->buildDateMenu($currentConfiguration, $currentDate);
        }

        return match ($this->currentRoute) {
            'mindshapecookieconsent_statisticcategories' => $this->statisticCategoriesAction($currentDate, $page),
            'mindshapecookieconsent_statisticoptions' => $this->statisticOptionsAction($currentDate, $page),
            default => $this->statisticButtonsAction($currentDate, $page),
        };
    }

    /**
     * @param \DateTime|null $date
     * @param int $page
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function statisticButtonsAction(?DateTime $date = null, int $page = 1): ResponseInterface
    {
        $this->moduleTemplate->setTitle(LocalizationUtility::translate('module.statistic.buttons', 'mindshape_cookie_consent'));

        $statisticButtons = null;

        if ($this->currentConfiguration instanceof Configuration) {
            $statisticButtons = $this->statisticActionMethod('StatisticButton', $this->currentConfiguration, $date);
            $this->addPaginationToView($statisticButtons, $page, (int)($this->settings['statisticItemsPerPage'] ?? 10));
        }

        $this->moduleTemplate->assignMultiple([
            'configuration' => $this->currentConfiguration,
            'date' => $date,
            'statisticButtons' => $statisticButtons,
        ]);

        return $this->moduleTemplate->renderResponse('Backend/Statistic/StatisticButtons');
    }

    /**
     * @param \DateTime|null $date
     * @param int $page
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function statisticCategoriesAction(?DateTime $date = null, int $page = 1): ResponseInterface
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

        $this->moduleTemplate->assignMultiple([
            'configuration' => $this->currentConfiguration,
            'date' => $date,
            'statisticCategories' => $statisticCategories,
        ]);

        return $this->moduleTemplate->renderResponse('Backend/Statistic/StatisticCategories');
    }

    /**
     * @param \DateTime|null $date
     * @param int $page
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function statisticOptionsAction(?DateTime $date = null, int $page = 1): ResponseInterface
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

        $this->moduleTemplate->assignMultiple([
            'configuration' => $this->currentConfiguration,
            'date' => $date,
            'statisticOptions' => $statisticOptions,
            'cookieOptions' => $cookieOptions,
        ]);

        return $this->moduleTemplate->renderResponse('Backend/Statistic/StatisticOptions');
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $queryResult
     * @param int $currentPage
     * @param int $itemsPerPage
     */
    protected function addPaginationToView(QueryResultInterface $queryResult, int $currentPage, int $itemsPerPage): void
    {
        $paginator = new QueryResultPaginator($queryResult, $currentPage, $itemsPerPage);
        $pagination = new SlidingWindowPagination($paginator, (int)($this->settings['maximumPaginationLinks'] ?? 0));

        $this->moduleTemplate->assignMultiple([
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
    protected function statisticActionMethod(string $entityName, Configuration $configuration, ?DateTime $date = null): QueryResultInterface
    {
        $repositoryName = lcfirst($entityName) . 'Repository';

        if (null === $date) {
            return $this->{$repositoryName}->findAllByConfiguration($configuration);
        }

        $this->moduleTemplate->assign('date', $date);

        return $this->{$repositoryName}->findByMonth($configuration, $date);
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $currentConfiguration
     * @throws \Doctrine\DBAL\Exception
     * @throws \TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException
     */
    protected function buildRouteMenu(Configuration $currentConfiguration): void
    {
        /** @var \TYPO3\CMS\Backend\Routing\UriBuilder $uriBuilder */
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $actionMenu = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $actionMenu->setIdentifier('mindshape_cookie_consent_action');

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
                $siteLabel = LocalizationUtility::translate('tca.configuration.site.all', 'mindshape_cookie_consent');
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

            $configurationId = $configuration->_getProperty('_localizedUid') ?? $configuration->getUid();
            $currentConfigurationId = $currentConfiguration->_getProperty('_localizedUid') ?? $currentConfiguration->getUid();

            $actionMenu->addMenuItem(
                $actionMenu
                    ->makeMenuItem()
                    ->setTitle(LocalizationUtility::translate('module.statistic.menu.action.buttons', 'mindshape_cookie_consent') . ' - ' . $siteLabel)
                    ->setHref($uriBuilder->buildUriFromRoute('mindshapecookieconsent_statisticbuttons', ['configuration' => $configurationId]))
                    ->setActive(
                        'mindshapecookieconsent_statisticbuttons' === $this->currentRoute &&
                        $configurationId === $currentConfigurationId
                    )
            );

            $actionMenu->addMenuItem(
                $actionMenu
                    ->makeMenuItem()
                    ->setTitle(LocalizationUtility::translate('module.statistic.menu.action.categories', 'mindshape_cookie_consent') . ' - ' . $siteLabel)
                    ->setHref($uriBuilder->buildUriFromRoute('mindshapecookieconsent_statisticcategories', ['configuration' => $configurationId]))
                    ->setActive(
                        'mindshapecookieconsent_statisticcategories' === $this->currentRoute &&
                        $configurationId === $currentConfigurationId
                    )
            );

            $actionMenu->addMenuItem(
                $actionMenu
                    ->makeMenuItem()
                    ->setTitle(LocalizationUtility::translate('module.statistic.menu.action.options', 'mindshape_cookie_consent') . ' - ' . $siteLabel)
                    ->setHref($uriBuilder->buildUriFromRoute('mindshapecookieconsent_statisticoptions', ['configuration' => $configurationId]))
                    ->setActive(
                        'mindshapecookieconsent_statisticoptions' === $this->currentRoute &&
                        $configurationId === $currentConfigurationId
                    )
            );
        }

        $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->addMenu($actionMenu);
    }

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $currentConfiguration
     * @param \DateTime|null $currentDate
     * @throws \TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException
     */
    protected function buildDateMenu(Configuration $currentConfiguration, ?DateTime $currentDate): void
    {
        /** @var \TYPO3\CMS\Backend\Routing\UriBuilder $uriBuilder */
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $dateMenu = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $dateMenu->setIdentifier('mindshape_cookie_consent_date');

        switch ($this->currentRoute) {
            case 'mindshapecookieconsent_statisticbuttons':
                $availableMonths = $this->statisticButtonRepository->findAvailableMonths(
                    $currentConfiguration->getLanguageUid()
                );
                break;
            case 'mindshapecookieconsent_statisticcategories':
                $availableMonths = $this->statisticCategoryRepository->findAvailableMonths(
                    $currentConfiguration->getLanguageUid()
                );
                break;
            case 'mindshapecookieconsent_statisticoptions':
                $availableMonths = $this->statisticOptionRepository->findAvailableMonths(
                    $currentConfiguration->getLanguageUid()
                );
                break;
            default:
                return;
        }

        $dateMenu->addMenuItem(
            $dateMenu
                ->makeMenuItem()
                ->setTitle(LocalizationUtility::translate('module.statistic.date_select_all', 'mindshape_cookie_consent'))
                ->setActive(!$currentDate instanceof DateTime)
                ->setHref($uriBuilder->buildUriFromRoute($this->currentRoute, [
                    'configuration' => $currentConfiguration->getLocalizedUid(),
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
                    ->setHref($uriBuilder->buildUriFromRoute($this->currentRoute, [
                        'date' => $availableMonth->format('Y-m'),
                        'configuration' => $currentConfiguration->getLocalizedUid(),
                    ]))
            );
        }

        $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->addMenu($dateMenu);
    }
}
