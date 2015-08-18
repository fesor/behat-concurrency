<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

class FeatureContext implements Context, SnippetAcceptingContext
{

    private $supportDir;

    private $featuresDir;

    private $workerLogsDir;

    private $features;

    public function __construct()
    {
        $this->supportDir = __DIR__ . '/../support';
        $this->featuresDir = __DIR__ . '/../../.tmp/features';
        $this->workerLogsDir = __DIR__ . '/../../.tmp';
        $this->features = [];
    }

    /**
     * @AfterScenario
     */
    public function cleanup()
    {
        $dir_iterator = new RecursiveDirectoryIterator($this->workerLogsDir);
        $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
        $regexIterator = new RegexIterator($iterator, '/^.+\.(feature|log)$/i', RecursiveRegexIterator::MATCH);
        foreach($regexIterator as $file){ // iterate files
            if(is_file($file))
                unlink($file);
        }
    }

    /**
     * @param TableNode $feature
     * @Given I have feature ":featureName" with:
     */
    public function iHaveFeatureSpecification($featureName, TableNode $feature)
    {
        $this->features[$featureName] = [
            'name' => $featureName,
            'scenarios' => $this->getScenarios($feature)
        ];
    }

    /**
     * @When I run behat in concurrent mode using :workers worker(s)
     * @When I run behat without concurency
     */
    public function iRunBehat($workers = 1)
    {
        $this->generateSpecifications();

        exec(sprintf(
            '%s --config %s/behat.yml --concurrent %d --format progress --no-colors',
            $_SERVER['argv'][0],
            $this->supportDir,
            $workers
        ));
    }

    /**
     * @Then no workers should be created
     */
    public function noWorkers()
    {
        if (!$this->logExistsForWorker(0)) {
            throw new \LogicException('Some workers has been executed');
        }
    }

    private function logExistsForWorker($id)
    {
        return file_exists(sprintf('%s/worker_%d.log', $this->workerLogsDir, $id));
    }

    private function generateSpecifications()
    {
        if (!is_dir($this->featuresDir)) {
            mkdir($this->featuresDir, 0777, true);
        }

        foreach ($this->features as $name => $feature) {
            file_put_contents(
                sprintf('%s/%s.feature', $this->featuresDir, strtolower($name)),
                $this->render('feature', $feature)
            );
        }
    }

    private function getScenarios(TableNode $scenarios)
    {
        $result = [];
        foreach($scenarios->getRowsHash() as $scenarioType => $amount)
        {
            $result = array_merge($result, $this->times([
                'name' => $scenarioType,
                'steps' => $this->getSteps($scenarioType)
            ], $amount));
        }

        return $result;
    }

    private function getSteps($scenarioType)
    {
        $step = preg_replace('/scenario$/i', 'step', $scenarioType);

        return [
            'when' => [$step],
            'then' => [$step],
        ];
    }

    private function times($arr, $amount)
    {
        return array_map(function () use ($arr) {
            return $arr;
        }, range(1, $amount));
    }

    private function render($_template, $_context) {
        extract($_context);

        ob_start();
        $_templatePath = sprintf('%s/templates/%s.php', $this->supportDir, $_template);
        if (!is_file($_templatePath)) {
            throw new \InvalidArgumentException(sprintf('Unable to find template "%s"', $_template));
        }

        require $_templatePath;

        return ob_get_clean();
    }

}
