<?php

namespace App\Client\Model;

use App\Model\Entity;

final class Client implements ClientInterface, Entity
{
    private string $name;
    private string $token;
    private AllowedActions $allowedActions;
    private \DateTimeImmutable $createdAt;
    private bool $disabled;

    public function __construct(
        string $name,
        string $token,
        AllowedActions $allowedActions,
        \DateTimeImmutable $createdAt,
        bool $disabled
    ) {
        $this->name = $name;
        $this->token = $token;
        $this->allowedActions = $allowedActions;
        $this->createdAt = $createdAt;
        $this->disabled = $disabled;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function allowedActions(): AllowedActions
    {
        return $this->allowedActions;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function enabled(): bool
    {
        return !$this->disabled;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function setAllowedActions(AllowedActions $actions): void
    {
        $this->allowedActions = $actions;
    }

    public function disable(): void
    {
        $this->disabled = true;
    }

    public function sameAs(Entity $other): bool
    {
        return $other instanceof Client && $this->name === $other->name;
    }
}
