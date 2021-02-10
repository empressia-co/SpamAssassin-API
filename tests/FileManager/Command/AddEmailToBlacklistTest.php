<?php

namespace FileManager\Command;

use App\ConfigFile\Command\AddEmailToBlacklist;
use PHPUnit\Framework\TestCase;

class AddEmailToBlacklistTest extends TestCase
{
    public function testThrowsWhenBlankEmail()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToBlacklist('');
    }

    public function testThrowsWhenSimpleString()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToBlacklist('lorem');
    }

    public function testThrowsWhenSpaces()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToBlacklist('lorem ipsum@dolor.sit');
    }

    public function testThrowsWhenOnlyDomain()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToBlacklist('@dolor.sit');
    }

    public function testThrowsWhenNoDomain()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AddEmailToBlacklist('lorem@');
    }

    public function testAllowWildcard()
    {
        $command = new AddEmailToBlacklist('*@lorem.com');
        $this->assertSame('*@lorem.com', $command->email());

        $command = new AddEmailToBlacklist('*@*.lorem.com');
        $this->assertSame('*@*.lorem.com', $command->email());
    }

    public function testTrimmed()
    {
        $command = new AddEmailToBlacklist(' lorem@ipsum.sit     ');

        $this->assertSame('lorem@ipsum.sit', $command->email());
    }
}
