<?php

namespace Treffynnon\At\Job;

interface JobInterface
{
    public function setTask($task);
    public function getTask();
    public function validateTask($task);
}
