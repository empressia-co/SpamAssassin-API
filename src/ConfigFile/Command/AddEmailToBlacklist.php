<?php

namespace App\ConfigFile\Command;

use Assert\Assert;
use Assert\Assertion;

final class AddEmailToBlacklist
{
    private string $email;

    public function __construct(string $email)
    {
        Assertion::notBlank($email);
        Assertion::email($email);

        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }
}
