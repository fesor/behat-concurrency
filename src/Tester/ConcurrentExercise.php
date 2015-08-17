<?php

namespace Fesor\Behat\Concurrency\Tester;

use Behat\Testwork\Specification\GroupedSpecificationIterator;
use Behat\Testwork\Specification\SpecificationIterator;
use Behat\Testwork\Tester\Exercise;
use Behat\Testwork\Tester\Result\TestResult;
use Behat\Testwork\Tester\Result\TestResults;
use Behat\Testwork\Tester\Setup\SuccessfulSetup;
use Behat\Testwork\Tester\Setup\SuccessfulTeardown;

final class ConcurrentExercise implements Exercise
{

    /**
     * @var Exercise
     */
    private $baseTester;

    /**
     * @var bool
     */
    private $enabled;

    public function __construct($baseTester)
    {
        $this->baseTester = $baseTester;
        $this->enabled = false;
    }

    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * @inheritdoc
     */
    public function setUp(array $iterators, $skip)
    {
        if (!$this->enabled) {
            return $this->baseTester->setUp($iterators, $skip);
        }

        return new SuccessfulSetup();
    }

    /**
     * @inheritdoc
     */
    public function test(array $iterators, $skip)
    {
        if (!$this->enabled) {
            return $this->baseTester->test($iterators, $skip);
        }

        $results = array();
        foreach (GroupedSpecificationIterator::group($iterators) as $iterator) {
            $suite = $iterator->getSuite();
        }

        return new TestResults($results);
    }

    /**
     * @inheritdoc
     */
    public function tearDown(array $iterators, $skip, TestResult $result)
    {
        if (!$this->enabled) {
            return $this->baseTester->tearDown($iterators, $skip, $result);
        }

        return new SuccessfulTeardown();
    }


}
