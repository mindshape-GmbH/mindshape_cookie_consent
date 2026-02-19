<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\ViewHelpers;

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

use Mindshape\MindshapeCookieConsent\Exception\NotANumberException;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * @package Mindshape\MindshapeCookieConsent\ViewHelpers
 */
class PercentageViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('total', 'mixed', 'The total amount to calculate the percatentage from', true);
        $this->registerArgument('fraction', 'mixed', 'The fraction to calculate the percatentage from', true);
    }

    /**
     * @return float|int
     * @throws \Mindshape\MindshapeCookieConsent\Exception\NotANumberException
     */
    public function render(): float|int
    {
        if (
            false === is_numeric($this->arguments['total']) ||
            false === is_numeric($this->arguments['fraction'])
        ) {
            throw new NotANumberException('This viewhelper accepts numbers only');
        }

        $total = (float) $this->arguments['total'];
        $fraction = (float) $this->arguments['fraction'];

        if ($total === 0.0) {
            return 0;
        }

        return 100 / $total * $fraction;
    }
}
