<?php

namespace DM\DtoRequestBundle\Tests\Unit\Annotations\Dto;

use DM\DtoRequestBundle\Annotations\Dto\Bag;
use DM\DtoRequestBundle\Tests\PHPUnit\TestCase;

class BagTest extends TestCase
{
    /**
     * @testWith ["query"]
     *           ["request"]
     *           ["attributes"]
     *           ["files"]
     *           ["cookies"]
     *           ["headers", true]
     */
    public function testCreation(
        string $bag,
        bool $isHeader = false
    ): void {
        $annotation = new Bag($bag);

        $this->assertEquals(
            $bag,
            $annotation->bag
        );
        $this->assertEquals(
            $isHeader,
            $annotation->bag->isHeaders()
        );
    }
}
