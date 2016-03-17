<?php

namespace spec\Treffynnon\At\Job;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\Job\Command');
    }

    function it_can_construct_new_file_job()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringSetTask('');
        $this->getTask()->shouldReturn('');

        $this->setTask('echo "hello" | wall');
        $this->getTask()->shouldReturn('echo "hello" | wall');
    }

    function it_can_set_when($when)
    {
        $when->beADoubleOf('Treffynnon\At\When');
        $when->getTime()->willReturn('201603171225');
        $this->setWhen($when)->getWhen()->shouldReturn($when);
    }
}
