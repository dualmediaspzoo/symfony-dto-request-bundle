<?php

namespace DM\DtoRequestBundle\Attributes\Entity;

use DM\DtoRequestBundle\Interfaces\Entity\ProviderInterface;

/**
 * Allows setting which entity is provided by the provider
 *
 * Use together with implementing {@link ProviderInterface}
 */
#[\Attribute]
class EntityProvider
{
    public function __construct(
        public readonly string $fqcn,
        public readonly bool $default = false
    ) {
    }
}
