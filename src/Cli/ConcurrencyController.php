<?php

namespace Fesor\Behat\Concurrency\Cli;

use Behat\Testwork\Cli\Controller;
use Fesor\Behat\Concurrency\Exception\InvalidNumberOfWorkersException;
use Fesor\Behat\Concurrency\Tester\ConcurrentExercise;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ConcurrencyController implements Controller
{

    /**
     * @var ConcurrentExercise
     */
    private $exercise;

    /**
     * @param ConcurrentExercise $exercise
     */
    public function __construct(ConcurrentExercise $exercise)
    {
        $this->exercise = $exercise;
    }


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

        $this->exercise->enable();

        return 0;
    }



}
