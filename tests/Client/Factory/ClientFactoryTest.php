<?php

namespace App\Tests\Client\Factory;

use App\Client\Factory\ClientFactory;
use App\Client\Generator\RandomTokenGenerator;
use App\Client\Model\AllowedActions;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class ClientFactoryTest extends TestCase
{
    public function testBlankNameThrows()
    {
        $this->expectException(AssertionFailedException::class);

        $factory = new ClientFactory(new RandomTokenGenerator(32));
        $factory->create('', new AllowedActions([]));
    }
}
