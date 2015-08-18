<?php

class DummyContext implements \Behat\Behat\Context\Context
{

    private $workerID;

    private $logPath;

    public function __construct()
    {
        $this->workerID = getenv('BEHAT_WORKER_NUMBER');
        $this->logPath = sprintf('%s/../../.tmp/worker_%d.log', __DIR__, $this->workerID);
    }

    /**
     * @Given noop step
     */
    public function noopStep()
    {
        $this->rememberThatStepExecuted('noop');
    }

    /**
     * @Given long running step
     */
    public function longRunningStep()
    {
        usleep(100);
        $this->rememberThatStepExecuted('long running step');
    }

    /**
     * @When failing step
     */
    public function failingStep()
    {
        $this->rememberThatStepGoingToFail('fail');
    }

    /**
     * @When fatal error step
     */
    public function fatalErrorStep()
    {
        $this->rememberThatStepGoingToFail('fail');
    }

    public function rememberThatStepGoingToFail($reason)
    {
        $this->rememberThat('Step failed', $reason);
    }

    private function rememberThatStepExecuted($step)
    {
        $this->rememberThat('Step executed', $step);
    }

    private function rememberThatHookExecuted($hookName)
    {
        $this->rememberThat('Hook executed', $hookName);
    }

    private function rememberThat($event, $data)
    {
        file_put_contents($this->logPath, sprintf("%s: %s\n", $event, $data), FILE_APPEND);
    }
}
