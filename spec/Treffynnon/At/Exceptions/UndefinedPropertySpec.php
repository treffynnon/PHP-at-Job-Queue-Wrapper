<?php

namespace spec\Treffynnon\At\Exceptions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UndefinedPropertySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\Exceptions\UndefinedProperty');
    }
}
