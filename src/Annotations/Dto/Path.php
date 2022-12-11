<?php

namespace DM\DtoRequestBundle\Annotations\Dto;

use DM\DtoRequestBundle\Interfaces\Attribute\DtoAnnotationInterface;
use DM\DtoRequestBundle\Interfaces\Attribute\PathInterface;
use DM\DtoRequestBundle\Interfaces\DtoInterface;
use DM\DtoRequestBundle\Traits\Annotation\PathTrait;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * By default, if this annotation does not exist on a property of {@link DtoInterface}, then a default object
 * with the path as the property name is created with defaults
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Path implements PathInterface, DtoAnnotationInterface
{
    use PathTrait;

    public function __construct(
        string|null $path = null
    ) {
        $this->path = $path;
    }
}
