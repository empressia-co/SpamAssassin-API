<?php

namespace App\Tests\FileManager\Parser;

use App\ConfigFile\Parser\BlacklistParser;
use PHPUnit\Framework\TestCase;

class BlacklistParserTest extends TestCase
{
    public function testWhenFileEmpty()
    {
        $parser = new BlacklistParser();

        $this->assertEmpty($parser->getEmailsFromFileContent(''));
    }

    public function testWhenOnlyBlankLines()
    {
        $parser = new BlacklistParser();

        $this->assertEmpty($parser->getEmailsFromFileContent('\n \n\n\n \n'));
    }

    public function testWhenLineEmpty()
    {
        $parser = new BlacklistParser();

        $this->assertNull($parser->getEmailFromLine(''));
    }

    public function testWhenLineIsComment()
    {
        $parser = new BlacklistParser();

        $this->assertNull($parser->getEmailFromLine('#this is just a comment'));
    }

    public function testWhenNoDirective()
    {
        $parser = new BlacklistParser();

        $this->assertNull($parser->getEmailFromLine('lorem ipsum dolor sit amet'));
    }

    public function testWhenNoValue()
    {
        $parser = new BlacklistParser();

        $this->assertNull($parser->getEmailFromLine('blacklist_from'));
        $this->assertNull($parser->getEmailFromLine('blacklist_from     '));
    }

    public function testWhenCommentedValue()
    {
        $parser = new BlacklistParser();

        $this->assertNull($parser->getEmailFromLine('blacklist_from #lorem@ipsum.com'));
    }

    public function testWhenValueNoEmail()
    {
        $parser = new BlacklistParser();

        $this->assertNull($parser->getEmailFromLine('blacklist_from lorem'));
        $this->assertNull($parser->getEmailFromLine('blacklist_from lorem ipsum'));
    }

    public function testWhenEmailInvalid()
    {
        $parser = new BlacklistParser();

        $this->assertNull($parser->getEmailFromLine('blacklist_from lorem@'));
        $this->assertNull($parser->getEmailFromLine('blacklist_from @ipsum'));
        $this->assertNull($parser->getEmailFromLine('blacklist_from lorem ipsum@ipsum'));
    }

    public function testWhenTabsOrSpacesBeforeDirective()
    {
        $parser = new BlacklistParser();

        $this->assertSame('lorem@ipsum.com', $parser->getEmailFromLine('  blacklist_from lorem@ipsum.com'));
        $this->assertSame('lorem@ipsum.com', $parser->getEmailFromLine("\tblacklist_from lorem@ipsum.com"));
    }

    public function testEmailTrimmed()
    {
        $parser = new BlacklistParser();

        $this->assertSame('lorem@ipsum.com', $parser->getEmailFromLine('  blacklist_from   lorem@ipsum.com   '));
    }

    public function testWhenCommentAfter()
    {
        $parser = new BlacklistParser();

        $this->assertSame('lorem@ipsum.com', $parser->getEmailFromLine('blacklist_from lorem@ipsum.com  # some comment '));
    }

    public function testNoDuplicates()
    {
        $parser = new BlacklistParser();

        $this->assertSame(
            ['lorem@ipsum.com'],
            $parser->getEmailsFromFileContent("blacklist_from lorem@ipsum.com\n\nblacklist_from lorem@ipsum.com")
        );
    }

    public function testMultipleEmails()
    {
        $parser = new BlacklistParser();

        $this->assertSame(
            ['lorem@ipsum.com', 'lorem@dolor.com'],
            $parser->getEmailsFromFileContent("blacklist_from lorem@ipsum.com\n\nblacklist_from lorem@dolor.com")
        );
    }
}
