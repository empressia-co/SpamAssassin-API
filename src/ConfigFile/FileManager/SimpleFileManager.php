<?php

namespace App\ConfigFile\FileManager;

use Assert\Assertion;

final class SimpleFileManager implements FileManagerInterface
{
    private string $filepath;

    public function __construct(string $filepath)
    {
        Assertion::directory(\dirname($filepath));

        $this->filepath = $filepath;
    }

    public function read(): string
    {
        Assertion::file($this->filepath);

        $content = \file_get_contents($this->filepath);

        if (false === $content) {
            throw new \RuntimeException('There was an error when reading file %s content', $this->filepath);
        }

        return $content;
    }

    public function write(string $content): void
    {
        Assertion::directory(\dirname($this->filepath));

        if (false === \file_put_contents($this->filepath, $content)) {
            throw new \RuntimeException(\sprintf('There was an error when writing to file %s', $this->filepath));
        }
    }
}
