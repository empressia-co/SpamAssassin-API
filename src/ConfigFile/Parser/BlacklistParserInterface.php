<?php

namespace App\ConfigFile\Parser;

interface BlacklistParserInterface
{
    /**
     * @return string[]
     */
    public function getEmailsFromFileContent(string $configFileContent): array;

    public function getEmailFromLine(string $line): ?string;
}
