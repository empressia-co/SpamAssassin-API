<?php

namespace App\Client\Factory;

use App\Client\Generator\TokenGeneratorInterface;
use App\Client\Model\AllowedActions;
use App\Client\Model\ClientInterface;
use App\Document\Client;
use Assert\Assertion;

class ClientFactory implements ClientFactoryInterface
{
    private TokenGeneratorInterface $tokenGenerator;

    public function __construct(TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    public function create(string $name, AllowedActions $allowedActions): ClientInterface
    {
        Assertion::notBlank($name);

        return new Client($name, $this->tokenGenerator->generate(), $allowedActions, new \DateTimeImmutable(), false);
    }
}
