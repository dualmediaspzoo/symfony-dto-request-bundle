<?php

namespace DualMedia\DtoRequestBundle\Service\Http;

use DualMedia\DtoRequestBundle\Interfaces\Attribute\HttpActionInterface;
use DualMedia\DtoRequestBundle\Interfaces\Http\ActionValidatorInterface;

class ActionValidatorService implements ActionValidatorInterface
{
    /**
     * @var ActionValidatorInterface[]
     */
    private array $validators;

    /**
     * @param \IteratorAggregate<array-key, ActionValidatorInterface> $iterator
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function __construct(
        \IteratorAggregate $iterator
    ) {
        $this->validators = iterator_to_array($iterator->getIterator());
    }

    public function supports(
        HttpActionInterface $action,
        $variable
    ): bool {
        foreach ($this->validators as $validator) {
            if ($validator->supports($action, $variable)) {
                return true;
            }
        }

        return false;
    }

    public function validate(
        HttpActionInterface $action,
        $variable
    ): bool {
        foreach ($this->validators as $validator) {
            if ($validator->supports($action, $variable)) {
                return $validator->validate($action, $variable);
            }
        }

        return false;
    }
}
