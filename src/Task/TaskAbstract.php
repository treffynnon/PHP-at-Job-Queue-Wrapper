<?php

namespace Treffynnon\At\Task;

abstract class TaskAbstract implements TaskInterface
{
    protected $task = '';
    protected $when = 'now + 1min';

    public function set($task)
    {
        if ($this->validate($task)) {
            $this->task = $task;
        }
    }

    public function get()
    {
        return $this->task;
    }

    public function validate($task)
    {
        if (empty($task)) {
            throw new \InvalidArgumentException(
                "You must give `at` a task to perform."
            );
        }
        return true;
    }
}
