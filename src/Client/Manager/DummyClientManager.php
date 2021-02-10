<?php

namespace App\Client\Manager;

use App\Client\Model\ClientInterface;

final class DummyClientManager implements ClientManagerInterface
{
    public function save(ClientInterface $client): void
    {
        // TODO: Implement save() method.
    }
}
