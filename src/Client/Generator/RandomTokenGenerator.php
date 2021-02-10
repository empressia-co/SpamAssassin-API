<?php

namespace App\Client\Generator;

final class RandomTokenGenerator implements TokenGeneratorInterface
{
    private int $tokenSize;

    public function __construct(int $tokenSize = 32)
    {
        $this->tokenSize = $tokenSize;
    }

    public function generate(): string
    {
        return \bin2hex(\random_bytes($this->tokenSize));
    }
}
