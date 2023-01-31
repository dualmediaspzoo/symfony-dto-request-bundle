<?php

namespace DualMedia\DtoRequestBundle\Tests\PHPUnit;

use DualMedia\DtoRequestBundle\Service\Nelmio\DtoOADescriber;
use OpenApi\Annotations\OpenApi;
use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;

class NelmioTestCase extends KernelTestCase
{
    private DtoOADescriber $service;
    private AnnotatedRouteControllerLoader $loader;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->service = $this->getService(DtoOADescriber::class);
        $this->loader = new AnnotatedRouteControllerLoader();
    }

    protected function describe(
        string $class,
        string $routeName
    ): OpenApi {
        $api = new OpenApi([]);
        $collection = $this->loader->load($class);

        if (null === ($route = $collection->get($routeName))) {
            $this->fail('No route has been found');
        }

        $reflection = new \ReflectionMethod($route->getDefault('_controller'));
        $this->service->describe($api, $route, $reflection);

        return $api;
    }
}
