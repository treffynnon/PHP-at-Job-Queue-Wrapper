<?php

namespace Treffynnon\At;

class Job
{
    protected $when;
    protected $task;

    public function setWhen(WhenInterface $when)
    {
        $this->when = $when;
    }

    public function getWhen()
    {
        return $this->when;
    }

    public function setTask(Task\TaskInterface $task)
    {
        $this->task = $task;
    }

    public function getTask()
    {
        return $this->task;
    }
}
