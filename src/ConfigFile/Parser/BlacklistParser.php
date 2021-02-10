<?php

namespace App\ConfigFile\Parser;

use App\ConfigFile\Assert\AssertEmailPattern;
use Assert\AssertionFailedException;

final class BlacklistParser extends AbstractParser implements BlacklistParserInterface
{
    protected string $directive;
    protected string $newLine;

    public function __construct(string $directive = 'blacklist_from', string $newLine = "\n")
    {
        $this->directive = $directive;
        $this->newLine = $newLine;
    }
}
