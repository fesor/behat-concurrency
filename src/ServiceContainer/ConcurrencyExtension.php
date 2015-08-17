<?php

namespace Fesor\Behat\Concurrency\ServiceContainer;

use Behat\Testwork\Cli\ServiceContainer\CliExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Behat\Testwork\Tester\ServiceContainer\TesterExtension;
use Fesor\Behat\Concurrency\Cli\ConcurrencyController;
use Fesor\Behat\Concurrency\Cli\WorkerController;
use Fesor\Behat\Concurrency\Tester\ConcurrentExercise;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

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
        $this->loadConcurrentExersiceTester($container);
    }

    private function loadControllers(ContainerBuilder $container)
    {
        $concurrencyController = new Definition(ConcurrencyController::class, [
            new Reference(TesterExtension::EXERCISE_ID)
        ]);
        $concurrencyController->addTag(CliExtension::CONTROLLER_TAG);

        $workerController = new Definition(WorkerController::class);
        $workerController->addTag(CliExtension::CONTROLLER_TAG);

        $container->setDefinition(self::COMMAND_CONCURRENCY_ID, $concurrencyController);
        $container->setDefinition(self::COMMAND_WORKER_ID, $workerController);
    }

    private function loadConcurrentExersiceTester(ContainerBuilder $container)
    {
        $definition = new Definition(ConcurrentExercise::class, [
            new Reference(TesterExtension::EXERCISE_ID)
        ]);
        $definition->addTag(TesterExtension::EXERCISE_WRAPPER_TAG, [
            'priority' => -9999
        ]);

        $container->setDefinition(TesterExtension::SUITE_TESTER_WRAPPER_TAG . '.concurrent', $definition);
    }


}
