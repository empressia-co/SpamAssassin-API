<?php

namespace App\Client\Cli;

use App\Client\Repository\ClientRepositoryInterface;
use Assert\Assertion;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RegenerateToken extends Command
{
    private MessageBusInterface $messageBus;
    private ClientRepositoryInterface $repository;

    public function __construct(MessageBusInterface $messageBus, ClientRepositoryInterface $repository)
    {
        parent::__construct();

        $this->messageBus = $messageBus;
        $this->repository = $repository;
    }

    public function configure()
    {
        $this->setName('client:regenerate-token')
            ->setDescription('Regenerates client\'s token')
            ->addArgument('name', InputArgument::REQUIRED, 'Client name');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (null === $client = $this->repository->find($name)) {
            $output->writeln(\sprintf('<error>Client "%s" does not exist.</error>', $name));

            return Command::FAILURE;
        }

        $oldToken = $client->token();

        $this->messageBus->dispatch(new \App\Client\Command\RegenerateToken($name));

        // we can return the token only if message has been handled synchronously
        $client = $this->repository->find($name);
        Assertion::notNull($client);
        if ($client->token() !== $oldToken) {
            $output->writeln(
                \sprintf(
                    '<info>Client "%s" token has been regenerated: "%s".</info>',
                    $name,
                    $client->token()
                )
            );
        } else {
            $output->writeln(\sprintf('<info>Client "%s" token has been regenerated.</info>', $name));
        }

        return Command::SUCCESS;
    }
}
