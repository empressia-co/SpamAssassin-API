<?php

namespace App\Tests\Client\Generator;

use App\Client\Generator\RandomTokenGenerator;
use PHPUnit\Framework\TestCase;

class RandomTokenGeneratorTest extends TestCase
{
    public function testTokenUnique()
    {
        $generator = new RandomTokenGenerator(32);

        $this->assertNotSame($generator->generate(), $generator->generate());
    }

    public function testLength()
    {
        $generator = new RandomTokenGenerator(32);

        $this->assertSame(32, \strlen(\hex2bin($generator->generate())));
    }
}
