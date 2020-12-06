<?php

namespace App\ConfigFile\Parser;

interface WhitelistParserInterface
{
    /**
     * @return string[]
     */
    public function getEmailsFromFileContent(string $configFileContent): array;

    public function getEmailFromLine(string $line): ?string;
}
