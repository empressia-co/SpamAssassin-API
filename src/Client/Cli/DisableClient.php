<?php

namespace App\Client\Cli;

use App\Client\Repository\ClientRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DisableClient extends Command
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
        $this->setName('client:disable')
            ->setDescription('Disables client')
            ->addArgument('name', InputArgument::REQUIRED, 'Client name');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (null === $this->repository->find($name)) {
            $output->writeln(\sprintf('<error>Client "%s" does not exist.</error>', $name));

            return Command::FAILURE;
        }

        $this->messageBus->dispatch(new \App\Client\Command\DisableClient($name));

        $output->writeln(\sprintf('<info>Client "%s" has been disabled.</info>', $name));

        return Command::SUCCESS;
    }
}
