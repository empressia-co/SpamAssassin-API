<?php

namespace App\Client\Manager;

use App\Client\Model\ClientInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

final class DoctrineClientManager implements ClientManagerInterface
{
    private DocumentManager $manager;

    public function __construct(DocumentManager $manager)
    {
        $this->manager = $manager;
    }

    public function save(ClientInterface $client): void
    {
        $this->manager->persist($client);
        $this->manager->flush();
    }
}
