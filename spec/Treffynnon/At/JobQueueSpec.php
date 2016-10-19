<?php

namespace spec\Treffynnon\At;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JobQueueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\JobQueue');
    }

    function it_can_set_and_get_queue_id_key()
    {
        $this->setQueue('a');
        $this->getQueue()->shouldBeString();
        $this->getQueue()->shouldReturn('a');
    }
}
