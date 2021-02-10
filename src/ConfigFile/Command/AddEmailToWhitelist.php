<?php

namespace App\ConfigFile\Command;

use App\ConfigFile\Assert\AssertEmailPattern;
use Assert\Assert;
use Assert\Assertion;

final class AddEmailToWhitelist
{
    private string $email;

    public function __construct(string $email)
    {
        $email = \trim($email);

        Assertion::notBlank($email);
        AssertEmailPattern::email($email);

        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }
}
