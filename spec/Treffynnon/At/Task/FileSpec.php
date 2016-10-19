<?php

namespace spec\Treffynnon\At\Task;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
    protected $temp_file = '';

    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\Task\File');
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
        $this->shouldThrow('\InvalidArgumentException')->duringSet($this->temp_file);
        $this->get()->shouldReturn('');

        $this->set('/usr/bin/env');
        $this->get()->shouldReturn('/usr/bin/env');
    }
}
