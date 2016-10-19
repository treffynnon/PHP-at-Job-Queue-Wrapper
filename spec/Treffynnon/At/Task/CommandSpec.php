<?php

namespace spec\Treffynnon\At\Task;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\Task\Command');
    }

    function it_can_construct_new_file_job()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringSet('');
        $this->get()->shouldReturn('');

        $this->set('echo "hello" | wall');
        $this->get()->shouldReturn('echo "hello" | wall');
    }
}
