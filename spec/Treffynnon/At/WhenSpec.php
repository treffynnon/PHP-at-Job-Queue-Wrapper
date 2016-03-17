<?php

namespace spec\Treffynnon\At;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WhenSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('now + 1min');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\When');
    }

    function it_can_be_constructed_with_a_date_time()
    {
        $this->setTime(new \DateTimeImmutable('now + 10min'));
        $this->getTime()->shouldBeString();

        $in_t = date('Y-m-d H:i', strtotime('tomorrow'));
        $out_t = preg_replace('/[^\d]/', '', $in_t);
        $this->setTime(new \DateTimeImmutable($in_t));
        $this->getTime()->shouldBeString();
        $this->getTime()->shouldReturn($out_t);
    }

    public function it_should_coalesce_now()
    {
        $this->setTime('now ');
        $this->getTime()->shouldBeString();
        $this->getTime()->shouldReturn('now + 1min');
    }

    public function it_should_raise_an_exception_when_invalid_date()
    {
        $this->setTime(new \DateTimeImmutable());
        $this->shouldThrow('Treffynnon\At\Exceptions\InvalidWhenArgument')->duringGetTime();
        $this->shouldThrow('Treffynnon\At\Exceptions\InvalidWhenArgument')->duringSetTime('yesterday');
    }
}
