<?php

namespace App\Client\Repository;

use App\Client\Model\ClientInterface;

interface ClientRepositoryInterface
{
    public function find(string $name): ?ClientInterface;

    public function findByToken(string $token): ?ClientInterface;

    /**
     * @return ClientInterface[]
     */
    public function findAll(): array;

    /**
     * @return ClientInterface[]
     */
    public function findEnabled(): array;

    /**
     * @return ClientInterface[]
     */
    public function findDisabled(): array;
}
