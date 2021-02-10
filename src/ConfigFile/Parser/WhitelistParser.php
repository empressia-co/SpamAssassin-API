<?php

namespace App\ConfigFile\Parser;

final class WhitelistParser extends AbstractParser implements WhitelistParserInterface
{
    protected string $directive;
    protected string $newLine;

    public function __construct(string $directive = 'whitelist_from', string $newLine = "\n")
    {
        $this->directive = $directive;
        $this->newLine = $newLine;
    }
}
