<?php

namespace spec\Treffynnon\At\Job;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
    protected $temp_file = '';

    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\Job\File');
    }

    function let()
    {
        $this->temp_file = tempnam('/tmp', 'test_');
    }

    function letgo()
    {
        unlink($this->temp_file);
    }

    function it_can_construct_new_file_job()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringSetTask($this->temp_file);
        $this->getTask()->shouldReturn('');

        $this->setTask('/usr/bin/env');
        $this->getTask()->shouldReturn('/usr/bin/env');
    }

    function it_can_set_time_to_run_the_job()
    {
    }
}
