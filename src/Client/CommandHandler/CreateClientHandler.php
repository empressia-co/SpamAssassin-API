<?php

namespace App\Client\CommandHandler;

use App\Client\Command\CreateClient;
use App\Client\Exception\ClientAlreadyExists;
use App\Client\Factory\ClientFactoryInterface;
use App\Client\Manager\ClientManagerInterface;
use App\Client\Model\AllowedActions;
use App\Client\Repository\ClientRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateClientHandler implements MessageHandlerInterface
{
    private ClientFactoryInterface $clientFactory;
    private ClientRepositoryInterface $clientRepository;
    /**
     * @var ClientManagerInterface
     */
    private ClientManagerInterface $clientManager;

    public function __construct(
        ClientFactoryInterface $clientFactory,
        ClientRepositoryInterface $clientRepository,
        ClientManagerInterface $clientManager
    ) {
        $this->clientFactory = $clientFactory;
        $this->clientRepository = $clientRepository;
        $this->clientManager = $clientManager;
    }

    public function __invoke(CreateClient $command): void
    {
        if (null !== $this->clientRepository->find($command->name())) {
            throw ClientAlreadyExists::withName($command->name());
        }

        $client = $this->clientFactory->create($command->name(), new AllowedActions($command->allowedActions()));

        $this->clientManager->save($client);
    }
}
