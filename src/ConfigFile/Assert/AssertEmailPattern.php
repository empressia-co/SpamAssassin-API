<?php

namespace App\ConfigFile\Assert;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class AssertEmailPattern
{
    /** @throws AssertionFailedException */
    public static function email(string $email): void
    {
        try {
            Assertion::email($email);
        } catch (AssertionFailedException $exception) {
            if (!\str_contains($email, '@')) {
                throw $exception;
            }

            $parts = explode('@', $email);

            if (2 !== count($parts)) {
                throw $exception;
            }

            $domain = \end($parts);
            $domain = str_replace('*.', '', $domain);

            Assertion::email($parts[0].'@'.$domain);
        }
    }
}
