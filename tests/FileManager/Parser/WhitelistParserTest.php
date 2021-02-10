<?php

namespace App\Tests\FileManager\Parser;

use App\ConfigFile\Parser\WhitelistParser;
use PHPUnit\Framework\TestCase;

class WhitelistParserTest extends TestCase
{
    public function testWhenFileEmpty()
    {
        $parser = new WhitelistParser();

        $this->assertEmpty($parser->getEmailsFromFileContent(''));
    }

    public function testWhenOnlyBlankLines()
    {
        $parser = new WhitelistParser();

        $this->assertEmpty($parser->getEmailsFromFileContent('\n \n\n\n \n'));
    }

    public function testWhenLineEmpty()
    {
        $parser = new WhitelistParser();

        $this->assertNull($parser->getEmailFromLine(''));
    }

    public function testWhenLineIsComment()
    {
        $parser = new WhitelistParser();

        $this->assertNull($parser->getEmailFromLine('#this is just a comment'));
    }

    public function testWhenNoDirective()
    {
        $parser = new WhitelistParser();

        $this->assertNull($parser->getEmailFromLine('lorem ipsum dolor sit amet'));
    }

    public function testWhenNoValue()
    {
        $parser = new WhitelistParser();

        $this->assertNull($parser->getEmailFromLine('whitelist_from'));
        $this->assertNull($parser->getEmailFromLine('whitelist_from     '));
    }

    public function testWhenCommentedValue()
    {
        $parser = new WhitelistParser();

        $this->assertNull($parser->getEmailFromLine('whitelist_from #lorem@ipsum.com'));
    }

    public function testWhenValueNoEmail()
    {
        $parser = new WhitelistParser();

        $this->assertNull($parser->getEmailFromLine('whitelist_from lorem'));
        $this->assertNull($parser->getEmailFromLine('whitelist_from lorem ipsum'));
    }

    public function testWhenEmailInvalid()
    {
        $parser = new WhitelistParser();

        $this->assertNull($parser->getEmailFromLine('whitelist_from lorem@'));
        $this->assertNull($parser->getEmailFromLine('whitelist_from @ipsum'));
        $this->assertNull($parser->getEmailFromLine('whitelist_from lorem ipsum@ipsum'));
    }

    public function testWhenTabsOrSpacesBeforeDirective()
    {
        $parser = new WhitelistParser();

        $this->assertSame('lorem@ipsum.com', $parser->getEmailFromLine('  whitelist_from lorem@ipsum.com'));
        $this->assertSame('lorem@ipsum.com', $parser->getEmailFromLine("\twhitelist_from lorem@ipsum.com"));
    }

    public function testEmailTrimmed()
    {
        $parser = new WhitelistParser();

        $this->assertSame('lorem@ipsum.com', $parser->getEmailFromLine('  whitelist_from   lorem@ipsum.com   '));
    }

    public function testWhenCommentAfter()
    {
        $parser = new WhitelistParser();

        $this->assertSame('lorem@ipsum.com', $parser->getEmailFromLine('whitelist_from lorem@ipsum.com  # some comment '));
    }

    public function testNoDuplicates()
    {
        $parser = new WhitelistParser();

        $this->assertSame(
            ['lorem@ipsum.com'],
            $parser->getEmailsFromFileContent("whitelist_from lorem@ipsum.com\n\nwhitelist_from lorem@ipsum.com")
        );
    }

    public function testMultipleEmails()
    {
        $parser = new WhitelistParser();

        $this->assertSame(
            ['lorem@ipsum.com', 'lorem@dolor.com'],
            $parser->getEmailsFromFileContent("whitelist_from lorem@ipsum.com\n\nwhitelist_from lorem@dolor.com")
        );
    }
}
