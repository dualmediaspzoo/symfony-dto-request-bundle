<?php

namespace DualMedia\DtoRequestBundle\Service\Entity;

use DualMedia\DtoRequestBundle\Exception\Entity\ComplexLoaderFunctionNotFoundException;
use DualMedia\DtoRequestBundle\Exception\Entity\ComplexLoaderNotFoundException;
use DualMedia\DtoRequestBundle\Interfaces\Attribute\FindComplexInterface;
use DualMedia\DtoRequestBundle\Interfaces\Entity\ComplexLoaderInterface;
use DualMedia\DtoRequestBundle\Interfaces\Entity\ComplexLoaderServiceInterface;
use DualMedia\DtoRequestBundle\Interfaces\Entity\ProviderServiceInterface;

class ComplexLoaderService implements ComplexLoaderServiceInterface
{
    /**
     * @var array<string, ComplexLoaderInterface>
     */
    private array $loaders;
    private ProviderServiceInterface $providerService;

    /**
     * @param array<string, ComplexLoaderInterface> $loaders
     * @param ProviderServiceInterface $providerService
     */
    public function __construct(
        array $loaders,
        ProviderServiceInterface $providerService
    ) {
        $this->loaders = $loaders;
        $this->providerService = $providerService;
    }

    public function loadComplex(
        string $fqcn,
        FindComplexInterface $find,
        array $input
    ): mixed {
        if (!array_key_exists($find->getService(), $this->loaders)) {
            throw new ComplexLoaderNotFoundException(sprintf(
                "Attempted to use loader with id %s",
                $find->getService()
            ));
        }

        if (!method_exists($this->loaders[$find->getService()], $find->getFn())) {
            throw new ComplexLoaderFunctionNotFoundException(sprintf(
                "Method %s does not exist on class %s",
                $find->getFn(),
                get_class($this->loaders[$find->getService()]),
            ));
        }

        return $this->providerService->getProvider(
            $fqcn,
            $find->getProviderId()
        )->findComplex(
            \Closure::fromCallable([$this->loaders[$find->getService()], $find->getFn()]), // @phpstan-ignore-line
            $input,
            $find->getOrderBy()
        );
    }
}
