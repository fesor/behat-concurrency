<?php

namespace Fesor\Behat\Concurrency\Exception;


class InvalidNumberOfWorkersException extends \InvalidArgumentException
{
    public function __construct($n)
    {
        parent::__construct(
            sprintf('Number of processes should be integer greater than zero, "%s" given', $n)
        );
    }
}
