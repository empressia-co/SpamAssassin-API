<?php

namespace App\ConfigFile\CommandHandler;

use App\ConfigFile\Command\AddEmailToWhitelist;
use App\ConfigFile\FileManager\FileManagerInterface;
use App\ConfigFile\Parser\WhitelistParserInterface;
use Assert\Assert;
use Assert\Assertion;

final class AddEmailToWhitelistHandler
{
    private FileManagerInterface $fileManager;
    private WhitelistParserInterface $parser;
    private string $directive;
    private string $newLine;

    public function __construct(
        FileManagerInterface $fileManager,
        WhitelistParserInterface $parser,
        string $directive = 'whitelist_from',
        string $newLine = "\n"
    ) {
        $this->fileManager = $fileManager;
        $this->parser = $parser;
        $this->directive = $directive;
        $this->newLine = $newLine;
    }

    public function __invoke(AddEmailToWhitelist $command): void
    {
        $file = $this->fileManager->read();
        $emails = $this->parser->getEmailsFromFileContent($file);

        if (\in_array(\trim($command->email()), $emails, true)) {
            return;
        }

        $file .= \sprintf('%s%s %s', $this->newLine, $this->directive, \trim($command->email()));

        $this->fileManager->write($file);
    }
}
