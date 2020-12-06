<?php

namespace App\Client\Exception;

use Throwable;

final class ClientAlreadyExists extends \RuntimeException
{
    public static function withName(string $name): self
    {
        return new self(\sprintf('Client "%s" already exists', $name));
    }

    public function __construct($message = 'Client already exists', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
