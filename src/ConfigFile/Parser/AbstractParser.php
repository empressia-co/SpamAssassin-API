<?php

namespace App\ConfigFile\Parser;

use App\ConfigFile\Assert\AssertEmailPattern;
use Assert\AssertionFailedException;

abstract class AbstractParser
{
    protected string $directive;
    protected string $newLine;

    public function getEmailsFromFileContent(string $configFileContent): array
    {
        $lines = explode($this->newLine, $configFileContent);

        $emails = [];

        foreach ($lines as $line) {
            $email = $this->getEmailFromLine($line);

            if (null !== $email && !\in_array($email, $emails, true)) {
                $emails[] = $email;
            }
        }

        return $emails;
    }

    public function getEmailFromLine(string $line): ?string
    {
        // remove commented out fragments
        if (false !== $commentPos = \strpos($line, '#')) {
            $line = \substr($line, 0, $commentPos);
        }

        if (false === $directivePos = \stripos($line, $this->directive)) {
            return null;
        }

        $value = \substr($line, $directivePos + \strlen($this->directive));
        $email = \trim($value);

        try {
            AssertEmailPattern::email($email);
        } catch (AssertionFailedException $exception) {
            return null;
        }

        return $email;
    }
}
