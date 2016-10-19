<?php

namespace Treffynnon\At\Task;

class File extends TaskAbstract
{
    /**
     * @throws \Treffynnon\At\Exceptions\InvalidWhenArgument
     * @param string $task
     * @return bool
     */
    public function validate($task)
    {
        parent::validate($task);

        if (!file_exists($task) ||
            !is_executable($task)) {
            throw new \InvalidArgumentException(
                "File ($task) does not exist or is not executable."
            );
        }
        return true;
    }
}
