<?php

namespace Treffynnon\At\Job;

class File extends JobAbstract
{
    /**
     * @throws \Treffynnon\At\Exceptions\InvalidWhenArgument
     * @param string $task
     * @return bool
     */
    public function validateTask($task)
    {
        parent::validateTask($task);

        if (!file_exists($task) ||
            !is_executable($task)) {
            throw new \InvalidArgumentException(
                "File ($task) does not exist or is not executable."
            );
        }
        return true;
    }
}
