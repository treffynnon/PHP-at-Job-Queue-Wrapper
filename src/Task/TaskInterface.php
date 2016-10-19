<?php

namespace Treffynnon\At\Task;

interface TaskInterface
{
    public function set($task);
    public function get();
    public function validate($task);
}
