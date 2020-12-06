<?php

namespace App\ConfigFile\FileManager;

interface FileManagerInterface
{
    public function read(): string;
    public function write(string $content): void;
}
