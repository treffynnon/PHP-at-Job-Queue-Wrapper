<?php

namespace spec\Treffynnon\At;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WhenSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('now + 1 minute');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Treffynnon\At\When');
    }

    function it_can_be_constructed_with_a_date_time()
    {
        $this->set(new \DateTimeImmutable('now + 10min'));
        $this->get()->shouldBeString();

        $in_t = date('Y-m-d H:i', strtotime('tomorrow'));
        $out_t = preg_replace('/[^\d]/', '', $in_t);
        $this->set(new \DateTimeImmutable($in_t));
        $this->get()->shouldBeString();
        $this->get()->shouldReturn($out_t);
    }

    public function it_should_coalesce_now()
    {
        $this->set('now ');
        $this->get()->shouldBeString();
        $time = new \DateTimeImmutable('now + 1 minute');
        $this->get()->shouldReturn($time->format('YmdHi'));
    }

    public function it_should_coalesce_next()
    {
        $this->set('next');
        $this->get()->shouldBeString();
        $time = new \DateTimeImmutable('now + 1 minute');
        $this->get()->shouldReturn($time->format('YmdHi'));
    }

    public function it_should_raise_an_exception_when_invalid_date()
    {
        $this->set(new \DateTimeImmutable());
        $this->shouldThrow('Treffynnon\At\Exceptions\InvalidWhenArgument')->duringset('yesterday');
    }
}
