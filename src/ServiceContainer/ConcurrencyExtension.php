<?php

namespace Fesor\Behat\Concurrency\ServiceContainer;

use Behat\Testwork\Cli\ServiceContainer\CliExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Fesor\Behat\Concurrency\Cli\ConcurrencyController;
use Fesor\Behat\Concurrency\Cli\WorkerController;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class ConcurrencyExtension implements Extension
{

    const COMMAND_CONCURRENCY_ID = 'concurrency.cli.command';
    const COMMAND_WORKER_ID = 'concurrency.cli.worker';

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {

    }

    /**
     * @inheritdoc
     */
    public function getConfigKey()
    {
        return 'concurrency';
    }

    /**
     * @inheritdoc
     */
    public function initialize(ExtensionManager $extensionManager)
    {

    }

    /**
     * @inheritdoc
     */
    public function configure(ArrayNodeDefinition $builder)
    {

    }

    /**
     * @inheritdoc
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadControllers($container);
    }

    private function loadControllers(ContainerBuilder $container)
    {
        $concurrencyController = new Definition(ConcurrencyController::class);
        $concurrencyController->addTag(CliExtension::CONTROLLER_TAG);

        $workerController = new Definition(WorkerController::class);
        $workerController->addTag(CliExtension::CONTROLLER_TAG);

        $container->setDefinition(self::COMMAND_CONCURRENCY_ID, $concurrencyController);
        $container->setDefinition(self::COMMAND_WORKER_ID, $workerController);
    }


}
