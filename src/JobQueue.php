<?php

namespace Treffynnon\At;

class JobQueue
{
    protected $queue = '';

    public function __construct($queue = '')
    {
        $this->setQueue($queue);
    }

    public function setQueue($queue)
    {
        $this->queue = $queue;
    }

    public function getQueue()
    {
        return $this->queue;
    }
}
