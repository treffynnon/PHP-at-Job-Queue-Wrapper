<?php

namespace spec\Treffynnon\At\Exceptions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JobNotFoundSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\Exceptions\JobNotFound');
    }
}
