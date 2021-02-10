<?php

namespace FileManager\CommandHandler;

use App\ConfigFile\Command\AddEmailToBlacklist;
use App\ConfigFile\CommandHandler\AddEmailToBlacklistHandler;
use App\ConfigFile\FileManager\InMemoryFileManager;
use App\ConfigFile\Parser\BlacklistParser;
use PHPUnit\Framework\TestCase;

class AddEmailToBlacklistHandlerTest extends TestCase
{
    public function testNoDuplicates()
    {
        $fileManager = new InMemoryFileManager();
        $fileManager->write('blacklist_from lorem@ipsum.com');
        $parser = new BlacklistParser();

        $handler = new AddEmailToBlacklistHandler($fileManager, $parser);
        $handler(new AddEmailToBlacklist('lorem@ipsum.com'));

        $this->assertSame(['lorem@ipsum.com'], $parser->getEmailsFromFileContent($fileManager->read()));
    }

    public function testAdd()
    {
        $fileManager = new InMemoryFileManager();
        $fileManager->write('blacklist_from lorem@ipsum.com');
        $parser = new BlacklistParser();

        $handler = new AddEmailToBlacklistHandler($fileManager, $parser);
        $handler(new AddEmailToBlacklist('new@ipsum.com'));


        $this->assertSame(['lorem@ipsum.com', 'new@ipsum.com'], $parser->getEmailsFromFileContent($fileManager->read()));
    }
}
