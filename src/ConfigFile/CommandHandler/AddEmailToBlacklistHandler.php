<?php

namespace App\ConfigFile\CommandHandler;

use App\ConfigFile\Command\AddEmailToBlacklist;
use App\ConfigFile\FileManager\FileManagerInterface;
use App\ConfigFile\Parser\BlacklistParserInterface;

final class AddEmailToBlacklistHandler
{
    private FileManagerInterface $fileManager;
    private BlacklistParserInterface $parser;
    private string $directive;
    private string $newLine;

    public function __construct(
        FileManagerInterface $fileManager,
        BlacklistParserInterface $parser,
        string $directive = 'blacklist_from',
        string $newLine = "\n"
    ) {
        $this->fileManager = $fileManager;
        $this->parser = $parser;
        $this->directive = $directive;
        $this->newLine = $newLine;
    }

    public function __invoke(AddEmailToBlacklist $command): void
    {
        $file = $this->fileManager->read();
        $emails = $this->parser->getEmailsFromFileContent($file);

        if (\in_array($command->email(), $emails, true)) {
            return;
        }

        $file .= \sprintf('%s%s %s', $this->newLine, $this->directive, $command->email());

        $this->fileManager->write($file);
    }
}
