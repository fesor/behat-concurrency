<?php

namespace Fesor\Behat\Concurrency\Cli;

use Behat\Testwork\Cli\Controller;
use Fesor\Behat\Concurrency\Exception\InvalidNumberOfWorkersException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConcurrencyController implements Controller
{

    /**
     * @inheritdoc
     */
    public function configure(SymfonyCommand $command)
    {
        $command
            ->addOption('concurrent', null, InputOption::VALUE_OPTIONAL, 'Execute specs using N processes')
        ;
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === ($processes = $input->getOption('concurrent'))) {
            return;
        }

        if (!is_numeric($processes) || $processes < 1) {
            throw new InvalidNumberOfWorkersException($processes);
        }

        $processes = (int) $processes;
        if ($processes === 1) {
            return;
        }

        return 0;
    }



}
