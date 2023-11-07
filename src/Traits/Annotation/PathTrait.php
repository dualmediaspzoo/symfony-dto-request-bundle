<?php

namespace DualMedia\DtoRequestBundle\Traits\Annotation;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Implements shared path fields.
 */
trait PathTrait
{
    /**
     * Path for the item.
     *
     * Default means just the object property name
     * Value must be compatible with the {@link PropertyAccess} syntax for objects
     * Path must only take into account this object, as there might be a parent path inherited
     */
    public readonly string|null $path;

    public function getPath(): string|null
    {
        return $this->path;
    }
}
