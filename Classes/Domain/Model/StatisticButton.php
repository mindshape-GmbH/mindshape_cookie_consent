<?php

namespace Mindshape\MindshapeCookieConsent\Domain\Model;

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

/**
 * @package Mindshape\MindshapeCookieConsent\Domain\Model
 */
class StatisticButton extends AbstractStatistic
{
    public const TABLE = 'tx_mindshapecookieconsent_domain_model_statisticbutton';

    /**
     * @var int
     */
    protected int $save = 0;

    /**
     * @var int
     */
    protected int $agreeToAll = 0;

    /**
     * @var int
     */
    protected int $deny = 0;

    /**
     * @param \Mindshape\MindshapeCookieConsent\Domain\Model\Configuration $configuration
     */
    public function initialize(Configuration $configuration): void
    {
        $this->_languageUid = $configuration->getLanguageUid();
        $this->configuration = $configuration;
    }

    /**
     * @return int
     */
    public function getSave(): int
    {
        return $this->save;
    }

    /**
     * @return int
     */
    public function getAgreeToAll(): int
    {
        return $this->agreeToAll;
    }

    /**
     * @return int
     */
    public function getDeny(): int
    {
        return $this->deny;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->save + $this->agreeToAll + $this->deny;
    }

    public function increaseSave(): void
    {
        $this->save++;
    }

    public function increaseAgreeToAll(): void
    {
        $this->agreeToAll++;
    }

    public function increaseDeny(): void
    {
        $this->deny++;
    }
}
