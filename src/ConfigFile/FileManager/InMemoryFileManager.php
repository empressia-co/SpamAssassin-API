<?php

namespace App\ConfigFile\FileManager;

use Assert\Assertion;

/**
 * This manager should not be used in real word. It is used for for tests.
 */
final class InMemoryFileManager implements FileManagerInterface
{
    private string $content = '';

    public function read(): string
    {
        return $this->content;
    }

    public function write(string $content): void
    {
        $this->content = $content;
    }
}
