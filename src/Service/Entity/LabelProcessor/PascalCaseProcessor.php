<?php

namespace DualMedia\DtoRequestBundle\Service\Entity\LabelProcessor;

use DualMedia\DtoRequestBundle\Interfaces\Entity\LabelProcessorInterface;

class PascalCaseProcessor implements LabelProcessorInterface
{
    public function normalize(
        string $value
    ): string {
        return strtoupper((string)preg_replace('/[A-Z]/', '_\\0', lcfirst($value)));
    }

    public function denormalize(
        string $value
    ): string {
        return implode(
            '',
            array_map(
                fn (string $s) => ucfirst(strtolower($s)),
                explode('_', $value)
            )
        );
    }
}
