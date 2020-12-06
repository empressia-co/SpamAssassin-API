<?php

namespace App\Client\Manager;

use App\Client\Model\ClientInterface;

interface ClientManagerInterface
{
    public function save(ClientInterface $client): void;
}
