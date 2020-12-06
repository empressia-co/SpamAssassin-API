<?php

namespace App\Client\CommandHandler;

use App\Client\Command\CreateClient;
use App\Client\Command\SetAllowedActions;
use App\Client\Exception\ClientAlreadyExists;
use App\Client\Exception\ClientDoesNotExist;
use App\Client\Factory\ClientFactoryInterface;
use App\Client\Manager\ClientManagerInterface;
use App\Client\Model\AllowedActions;
use App\Client\Repository\ClientRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SetAllowedActionsHandler implements MessageHandlerInterface
{
    private ClientRepositoryInterface $clientRepository;
    private ClientManagerInterface $clientManager;

    public function __construct(
        ClientRepositoryInterface $clientRepository,
        ClientManagerInterface $clientManager
    ) {
        $this->clientRepository = $clientRepository;
        $this->clientManager = $clientManager;
    }

    public function __invoke(SetAllowedActions $command): void
    {
        if (null === $client = $this->clientRepository->find($command->name())) {
            throw ClientDoesNotExist::withName($command->name());
        }

        $client->setAllowedActions(new AllowedActions($command->allowedActions()));

        $this->clientManager->save($client);
    }
}
