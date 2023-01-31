<?php

namespace DualMedia\DtoRequestBundle\Profiler\Service\Validation;

use DualMedia\DtoRequestBundle\Interfaces\Validation\TypeValidationInterface;
use DualMedia\DtoRequestBundle\Profiler\AbstractWrapper;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @extends AbstractWrapper<ConstraintViolationListInterface>
 */
class ProfilingTypeValidationService extends AbstractWrapper implements TypeValidationInterface
{
    public function __construct(
        private readonly TypeValidationInterface $validation,
        Stopwatch|null $stopwatch = null
    ) {
        parent::__construct($stopwatch);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function validateType(
        array &$values,
        array $properties
    ): ConstraintViolationListInterface {
        return $this->wrap(
            'validate.%d',
            function () use (&$values, $properties) {
                return $this->validation->validateType($values, $properties);
            }
        );
    }
}
