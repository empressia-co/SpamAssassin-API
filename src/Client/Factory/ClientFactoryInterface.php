<?php

namespace App\Client\Factory;

use App\Client\Model\AllowedActions;
use App\Client\Model\ClientInterface;

interface ClientFactoryInterface
{
    public function create(string $name, AllowedActions $allowedActions): ClientInterface;
}
