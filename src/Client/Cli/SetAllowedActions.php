<?php

namespace App\Client\Cli;

use App\Client\Repository\ClientRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SetAllowedActions extends Command
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
        $this->setName('client:set-allowed-actions')
            ->setDescription('Updates client\'s allowed actions')
            ->addArgument('name', InputArgument::REQUIRED, 'Client name')
            ->addOption(
                'allowed-action',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Allowed actions (read, write, remove). Omit to disallow all actions.'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $actions = $input->getOption('allowed-action');

        if (null === $client = $this->repository->find($name)) {
            $output->writeln(\sprintf('<error>Client "%s" does not exist.</error>', $name));

            return Command::FAILURE;
        }

        $this->messageBus->dispatch(new \App\Client\Command\SetAllowedActions($name, $actions));

        if (!empty($actions)) {
            $output->writeln(
                \sprintf(
                    '<info>Client "%s" actions updated: %s.</info>',
                    $name,
                    implode(', ', $actions)
                )
            );
        } else {
            $output->writeln(\sprintf('<info>Disallowed all Client "%s" actions.</info>', $name));
        }

        return Command::SUCCESS;
    }
}
