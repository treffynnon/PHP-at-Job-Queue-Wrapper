<?php

namespace Treffynnon\At\Job;

abstract class JobAbstract implements JobInterface
{
    protected $task = '';
    protected $when = 'now + 1min';

    public function setTask($task)
    {
        if ($this->validateTask($task)) {
            $this->task = $task;
        }
    }

    public function getTask()
    {
        return $this->task;
    }

    public function validateTask($task)
    {
        if (empty($task)) {
            throw new \InvalidArgumentException(
                "You must give `at` a task to perform."
            );
        }
        return true;
    }
}
