<?php

namespace FileManager\CommandHandler;

use App\ConfigFile\Command\AddEmailToWhitelist;
use App\ConfigFile\CommandHandler\AddEmailToWhitelistHandler;
use App\ConfigFile\FileManager\InMemoryFileManager;
use App\ConfigFile\Parser\WhitelistParser;
use PHPUnit\Framework\TestCase;

class AddEmailToWhitelistHandlerTest extends TestCase
{
    public function testNoDuplicates()
    {
        $fileManager = new InMemoryFileManager();
        $fileManager->write('whitelist_from lorem@ipsum.com');
        $parser = new WhitelistParser();

        $handler = new AddEmailToWhitelistHandler($fileManager, $parser);
        $handler(new AddEmailToWhitelist('lorem@ipsum.com'));

        $this->assertSame(['lorem@ipsum.com'], $parser->getEmailsFromFileContent($fileManager->read()));
    }

    public function testAdd()
    {
        $fileManager = new InMemoryFileManager();
        $fileManager->write('whitelist_from lorem@ipsum.com');
        $parser = new WhitelistParser();

        $handler = new AddEmailToWhitelistHandler($fileManager, $parser);
        $handler(new AddEmailToWhitelist('new@ipsum.com'));


        $this->assertSame(['lorem@ipsum.com', 'new@ipsum.com'], $parser->getEmailsFromFileContent($fileManager->read()));
    }
}
