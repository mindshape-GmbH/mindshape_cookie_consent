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
 *  (c) 2021 Daniel Dorndorf <dorndorf@mindshape.de>, mindshape GmbH
 *
 ***/

use Closure;
use Mindshape\MindshapeCookieConsent\Exception\NotANumberException;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * @package Mindshape\MindshapeCookieConsent\ViewHelpers
 */
class PercentageViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public function initializeArguments()
    {
        $this->registerArgument('total', 'mixed', 'The total amount to calculate the percatentage from', true);
        $this->registerArgument('fraction', 'mixed', 'The fraction to calculate the percatentage from', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return float
     * @throws \Mindshape\MindshapeCookieConsent\Exception\NotANumberException
     */
    public static function renderStatic(array $arguments, Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        if (
            false === is_numeric($arguments['total']) ||
            false === is_numeric($arguments['fraction'])
        ) {
            throw new NotANumberException('This viewhelper accepts numbers only');
        }

        $total = (float) $arguments['total'];
        $fraction = (float) $arguments['fraction'];

        return 100 / $total * $fraction;
    }
}
