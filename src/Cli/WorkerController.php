<?php

namespace Fesor\Behat\Concurrency\Cli;

use Behat\Testwork\Cli\Controller;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class WorkerController implements Controller
{

    const ENV_WORKER_NUMBER = 'BEHAT_WORKER_NUMBER';

    /**
     * @inheritdoc
     */
    public function configure(SymfonyCommand $command)
    {
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $jobNumber = getenv(self::ENV_WORKER_NUMBER);
        if (empty($jobNumber)) {
            return;
        }

        return 0;
    }
}
