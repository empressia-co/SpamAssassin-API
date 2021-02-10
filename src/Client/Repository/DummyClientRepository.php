<?php

namespace App\Client\Repository;

use App\Client\Model\AllowedActions;
use App\Client\Model\Client;
use App\Client\Model\ClientInterface;

final class DummyClientRepository implements ClientRepositoryInterface
{
    public function find(string $name): ?ClientInterface
    {
        if ($name === 'test') {
            return self::getTestClient();
        }

        return null;
    }

    public function findByToken(string $token): ?ClientInterface
    {
        if ($token === 'test123') {
            return self::getTestClient();
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return [self::getTestClient()];
    }

    /**
     * @inheritDoc
     */
    public function findEnabled(): array
    {
        return [self::getTestClient()];
    }

    public function findDisabled(): array
    {
        return [];
    }

    private static function getTestClient(): ClientInterface
    {
        return new Client(
            'test',
            'test123',
            new AllowedActions([AllowedActions::ACTION_READ, AllowedActions::ACTION_WRITE, AllowedActions::ACTION_REMOVE]),
            new \DateTimeImmutable('2020-12-07 08:20:00'),
            false
        );
    }
}
