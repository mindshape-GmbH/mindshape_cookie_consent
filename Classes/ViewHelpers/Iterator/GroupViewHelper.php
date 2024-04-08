<?php
declare(strict_types=1);

namespace Mindshape\MindshapeCookieConsent\ViewHelpers\Iterator;

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

use Closure;
use DateTime;
use Mindshape\MindshapeCookieConsent\Exception\NonIterableObjectException;
use Mindshape\MindshapeCookieConsent\Exception\NotAnObjectException;
use Mindshape\MindshapeCookieConsent\Exception\UnknownObjectException;
use Traversable;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * @package Mindshape\MindshapeCookieConsent\ViewHelpers
 */
class GroupViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('objects', 'mixed', 'Objects to group', true);
        $this->registerArgument('field', 'string', 'The field to group by', true);
        $this->registerArgument('fillSmallerGroups', 'bool', 'Fills grouped chunks to be equal in size', false, false);
        $this->registerArgument('as', 'string', 'The field to group by', false, 'groupedObjects');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     * @throws \Mindshape\MindshapeCookieConsent\Exception\NonIterableObjectException
     * @throws \Mindshape\MindshapeCookieConsent\Exception\NotAnObjectException
     * @throws \Mindshape\MindshapeCookieConsent\Exception\UnknownObjectException
     */
    public static function renderStatic(array $arguments, Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        if (
            !$arguments['objects'] instanceof Traversable &&
            false === is_array($arguments['objects'])
        ) {
            throw new NonIterableObjectException('This viewhelper accepts numbers only');
        }

        $groupedObjectsChunks = [];
        $chunkSize = 0;

        /** @var \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface|array $object */
        foreach ($arguments['objects'] as $object) {
            if ($object instanceof DomainObjectInterface) {
                $groupValue = $object->_getProperty($arguments['field']);
            } elseif (true === is_array($object)) {
                $groupValue = $object[$arguments['field']];
            } elseif (false === is_object($object)) {
                throw new NotAnObjectException('Expecting an iterable of objects or arrays "' . gettype($object) . '"-item was given');
            } else {
                throw new UnknownObjectException('Unkown object of type. "' . get_class($object) . '" can not be used with this ViewHelper');
            }

            if ($groupValue instanceof DateTime) {
                $groupValue = $groupValue->format('c');
            }

            if (false === array_key_exists($groupValue, $groupedObjectsChunks)) {
                $groupedObjectsChunks[$groupValue] = [];
            }

            $groupedObjectsChunks[$groupValue][] = $object;
            $groupedObjectsChunkSize = count($groupedObjectsChunks[$groupValue]);

            if ($chunkSize < $groupedObjectsChunkSize) {
                $chunkSize = $groupedObjectsChunkSize;
            }
        }

        if (true === (bool) $arguments['fillSmallerGroups'] && 0 < $chunkSize) {
            foreach ($groupedObjectsChunks as &$groupedObjects) {
                while ($chunkSize > count($groupedObjects)) {
                    $groupedObjects[] = null;
                }
            }
        }

        $renderingContext->getVariableProvider()->add($arguments['as'], $groupedObjectsChunks);

        return $renderChildrenClosure();
    }
}
