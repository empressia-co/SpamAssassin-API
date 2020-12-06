<?php

namespace App\Client\Exception;

use Throwable;

final class ClientDoesNotExist extends \RuntimeException
{
    public static function withName(string $name): self
    {
        return new self(\sprintf('Client "%s" does not exist', $name));
    }

    public function __construct($message = 'Client does not exist', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
