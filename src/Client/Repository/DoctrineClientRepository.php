<?php

namespace App\Client\Repository;

use App\Client\Model\ClientInterface;
use App\Document\Client;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectRepository;

final class DoctrineClientRepository implements ClientRepositoryInterface
{
    private ObjectRepository $repository;

    public function __construct(DocumentManager $manager)
    {
        $this->repository = $manager->getRepository(Client::class);
    }

    public function find(string $name): ?ClientInterface
    {
        return $this->repository->find($name);
    }

    public function findByToken(string $token): ?ClientInterface
    {
        return $this->repository->findOneBy(['token' => $token]);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findEnabled(): array
    {
        return $this->repository->findBy(['disabled' => false]);
    }

    public function findDisabled(): array
    {
        return $this->repository->findBy(['disabled' => true]);
    }
}
