<?php

namespace App\Document;

use App\Client\Model\AllowedActions;
use App\Client\Model\ClientInterface;
use App\Model\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Client implements ClientInterface, Entity
{
    /**
     * @MongoDB\Id(strategy="NONE", type="string")
     */
    private string $name;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\Index(unique=true)
     */
    private string $token;

    /**
     * @MongoDB\Field(type="allowed_actions")
     */
    private AllowedActions $allowedActions;

    /**
     * @MongoDB\Field(type="date_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @MongoDB\Field(type="bool")
     */
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
