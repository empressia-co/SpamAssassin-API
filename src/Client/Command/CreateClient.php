<?php

namespace App\Client\Command;

use App\Client\Model\AllowedActions;

final class CreateClient
{
    private string $name;

    /**
     * @var string[]
     */
    private array $allowedActions;

    public function __construct(string $name, array $allowedActions)
    {
        $this->name = $name;
        $this->allowedActions = $allowedActions;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function allowedActions(): array
    {
        return $this->allowedActions;
    }
}
