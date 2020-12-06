<?php

namespace App\Client\Generator;

interface TokenGeneratorInterface
{
    public function generate(): string;
}
