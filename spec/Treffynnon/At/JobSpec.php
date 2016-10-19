<?php

namespace spec\Treffynnon\At;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JobSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\Job');
    }

    function it_can_set_and_get_when($when)
    {
        $when->beADoubleOf('Treffynnon\At\When');
        $when->get()->willReturn('201603171225');
        $this->setWhen($when);
        $this->getWhen()->get()->shouldBeString();
        $this->getWhen()->get()->shouldReturn('201603171225');
    }

    function it_can_set_and_get_task($task) {
        $task->beADoubleOf('Treffynnon\At\Task\Command');
        $task->get()->willReturn('echo "hello" | wall');
        $this->setTask($task);
        $this->getTask()->get()->shouldBeString();
        $this->getTask()->get()->shouldReturn('echo "hello" | wall');
    }
}
