<?php

namespace App\Client\CommandHandler;

use App\Client\Command\CreateClient;
use App\Client\Command\DisableClient;
use App\Client\Command\RegenerateToken;
use App\Client\Exception\ClientAlreadyExists;
use App\Client\Exception\ClientDoesNotExist;
use App\Client\Factory\ClientFactoryInterface;
use App\Client\Generator\TokenGeneratorInterface;
use App\Client\Manager\ClientManagerInterface;
use App\Client\Model\AllowedActions;
use App\Client\Repository\ClientRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RegenerateTokenHandler implements MessageHandlerInterface
{
    private ClientRepositoryInterface $clientRepository;
    private TokenGeneratorInterface $tokenGenerator;
    private ClientManagerInterface $clientManager;

    public function __construct(
        ClientRepositoryInterface $clientRepository,
        TokenGeneratorInterface $tokenGenerator,
        ClientManagerInterface $clientManager
    ) {
        $this->clientRepository = $clientRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->clientManager = $clientManager;
    }

    public function __invoke(RegenerateToken $command): void
    {
        if (null === $client = $this->clientRepository->find($command->name())) {
            throw ClientDoesNotExist::withName($command->name());
        }

        $client->setToken($this->tokenGenerator->generate());

        $this->clientManager->save($client);
    }
}
