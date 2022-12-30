<?php

namespace DualMedia\DtoRequestBundle\Profiler\Service\Resolver;

use DualMedia\DtoRequestBundle\Attributes\Dto\Bag;
use DualMedia\DtoRequestBundle\Interfaces\Resolver\DtoTypeExtractorInterface;
use DualMedia\DtoRequestBundle\Model\Type\Dto;
use DualMedia\DtoRequestBundle\Profiler\AbstractWrapper;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @extends AbstractWrapper<Dto>
 */
class ProfilingDtoTypeExtractorService extends AbstractWrapper implements DtoTypeExtractorInterface
{
    private DtoTypeExtractorInterface $dtoTypeExtractor;

    public function __construct(
        DtoTypeExtractorInterface $dtoTypeExtractor,
        ?Stopwatch $stopwatch = null
    ) {
        $this->dtoTypeExtractor = $dtoTypeExtractor;
        parent::__construct($stopwatch);
    }

    public function extract(
        \ReflectionClass $class,
        ?Bag $root = null
    ): Dto {
        return $this->wrap(
            'extract:%d:'.$class->getName(),
            fn () => $this->dtoTypeExtractor->extract($class, $root)
        );
    }
}
