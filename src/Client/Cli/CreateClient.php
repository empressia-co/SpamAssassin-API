<?php

namespace App\Client\Cli;

use App\Client\Repository\ClientRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateClient extends Command
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
        $this->setName('client:create')
            ->setDescription('Creates client')
            ->addArgument('name', InputArgument::REQUIRED, 'Client name')
            ->addOption(
                'allowed-action',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Allowed actions (read, write, remove)'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (null !== $this->repository->find($name)) {
            $output->writeln(\sprintf('<error>Client "%s" already exists.</error>', $name));

            return Command::FAILURE;
        }

        $this->messageBus->dispatch(
            new \App\Client\Command\CreateClient(
                $name,
                $input->getOption('allowed-action')
            )
        );

        // we can return the token only if message has been handled synchronously
        if (null !== $client = $this->repository->find($name)) {
            $output->writeln(
                \sprintf(
                    '<info>Client "%s" has been created with a token: "%s".</info>',
                    $name,
                    $client->token()
                )
            );
        } else {
            $output->writeln(\sprintf('<info>Client "%s" has been created.</info>', $name));
        }

        return Command::SUCCESS;
    }
}
