<?php

namespace FileManager\Command;

use App\ConfigFile\Command\AddEmailToWhitelist;
use PHPUnit\Framework\TestCase;

class AddEmailToWhitelistTest extends TestCase
{
    public function testThrowsWhenBlankEmail()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToWhitelist('');
    }

    public function testThrowsWhenSimpleString()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToWhitelist('lorem');
    }

    public function testThrowsWhenSpaces()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToWhitelist('lorem ipsum@dolor.sit');
    }

    public function testThrowsWhenOnlyDomain()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToWhitelist('@dolor.sit');
    }

    public function testThrowsWhenNoDomain()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToWhitelist('lorem@');
    }

    public function testAllowWildcard()
    {
        $command = new AddEmailToWhitelist('*@lorem.com');
        $this->assertSame('*@lorem.com', $command->email());

        $command = new AddEmailToWhitelist('*@*.lorem.com');
        $this->assertSame('*@*.lorem.com', $command->email());
    }

    public function testTrimmed()
    {
        $command = new AddEmailToWhitelist(' lorem@ipsum.sit     ');

        $this->assertSame('lorem@ipsum.sit', $command->email());
    }
}
