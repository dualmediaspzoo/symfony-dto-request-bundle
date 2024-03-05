<?php

namespace DualMedia\DtoRequestBundle\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MappedToPathValidator extends ConstraintValidator
{
    public function validate(
        $value,
        Constraint $constraint
    ): void {
        // @codeCoverageIgnoreStart
        if (!$constraint instanceof MappedToPath) {
            throw new UnexpectedTypeException($constraint, MappedToPath::class);
        }
        // @codeCoverageIgnoreEnd

        if (null === $value) {
            return;
        }

        $constraints = is_array($constraint->constraints) ? $constraint->constraints : [$constraint->constraints];

        $this->context->getValidator()
            ->inContext($this->context)
            ->atPath($constraint->path)
            ->validate($value, $constraints);
    }
}
