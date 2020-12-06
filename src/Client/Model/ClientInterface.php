<?php

namespace App\Client\Model;

interface ClientInterface
{
    public function name(): string;
    public function token(): string;
    public function allowedActions(): AllowedActions;
    public function createdAt(): \DateTimeImmutable;
    public function enabled(): bool;

    public function setToken(string $token): void;
    public function setAllowedActions(AllowedActions $actions): void;
    public function disable(): void;
}
