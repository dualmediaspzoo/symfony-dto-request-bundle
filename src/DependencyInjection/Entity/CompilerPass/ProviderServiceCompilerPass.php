<?php

namespace DM\DtoRequestBundle\DependencyInjection\Entity\CompilerPass;

use DM\DtoRequestBundle\Attributes\Entity\EntityProvider;
use DM\DtoRequestBundle\DtoBundle;
use DM\DtoRequestBundle\Exception\DependencyInjection\Entity\AttributeMissingException;
use DM\DtoRequestBundle\Exception\DependencyInjection\Entity\DuplicateDefaultProviderException;
use DM\DtoRequestBundle\Service\Entity\EntityProviderService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\OutOfBoundsException;
use Symfony\Component\DependencyInjection\Reference;

class ProviderServiceCompilerPass implements CompilerPassInterface
{
    /**
     * @throws DuplicateDefaultProviderException
     * @throws AttributeMissingException
     * @throws \ReflectionException
     */
    public function process(
        ContainerBuilder $container
    ): void {
        // @codeCoverageIgnoreStart
        if (!$container->hasDefinition(EntityProviderService::class)) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $service = $container->getDefinition(EntityProviderService::class);

        try {
            /** @var array<string, list<array{0: Reference, 1: class-string, 2: bool}>> $arg */
            $arg = $service->getArgument(0);
        } catch (OutOfBoundsException $e) {
            /** @var array<string, list<array{0: Reference, 1: class-string, 2: bool}>> $arg */
            $arg = [];
        }

        foreach ($container->findTaggedServiceIds(DtoBundle::ENTITY_PROVIDER_PRE_CONFIG_TAG) as $id => $tags) {
            $def = $container->getDefinition($id);
            $def->clearTag(DtoBundle::ENTITY_PROVIDER_PRE_CONFIG_TAG);

            if (array_key_exists($id, $arg)) {
                continue;
            }

            /** @var \ReflectionClass $reflection */
            $reflection = $container->getReflectionClass($def->getClass());
            $attributes = $reflection->getAttributes(EntityProvider::class);

            if (empty($attributes)) {
                throw new AttributeMissingException($reflection->getName(), sprintf(
                    "Service %s is not annotated with %s attribute and cannot be used as a provider",
                    $id,
                    EntityProvider::class
                ));
            }
            $arg[$id] = [];

            /** @var list<EntityProvider> $attributes */
            $attributes = array_map(
                fn (\ReflectionAttribute $a) => $a->newInstance(),
                $attributes
            );

            foreach ($attributes as $attribute) {
                $arg[$id][] = [
                    new Reference($id),
                    $attribute->fqcn,
                    $attribute->default,
                ];
            }
        }

        // validate defaults
        /** @var array<class-string, string> $defaults */
        $defaults = [];
        /** @var array<class-string, list<string>> $repeats */
        $repeats = [];

        foreach ($arg as $id => $services) {
            foreach ($services as $params) {
                /** @var class-string $fqcn */
                [$ref, $fqcn, $default] = $params;

                if ($default) {
                    if (!array_key_exists($fqcn, $defaults)) {
                        $defaults[$fqcn] = $id;
                    } else {
                        if (!array_key_exists($fqcn, $repeats)) {
                            $repeats[$fqcn] = [
                                $defaults[$fqcn], // add our previous default
                            ];
                        }

                        $repeats[$fqcn][] = $id;
                    }
                }
            }
        }

        // create a "Default provider for fqcn repeats in ...."
        if (!empty($repeats)) {
            $strings = [];

            foreach ($repeats as $fqcn => $services) {
                $strings[] = sprintf(
                    "%s with %s",
                    $fqcn,
                    implode(" and ", $services)
                );
            }

            throw new DuplicateDefaultProviderException($repeats, sprintf(
                "One or more providers have duplicated defaults: %s",
                implode(", ", $strings)
            ));
        }

        $service->setArgument(0, $arg);
    }
}
