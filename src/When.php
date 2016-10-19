<?php

namespace Treffynnon\At;

use Treffynnon\At\Exceptions\InvalidWhenArgument;

class When implements WhenInterface
{
    /**
     * @var string|\DateTimeInterface
     */
    public $time;

    /**
     * When constructor.
     * @param \DateTimeInterface|string $time
     */
    public function __construct($time)
    {
         $this->set($time);
    }

    public function set($time)
    {
        if (is_string($time)) {
            $time = $this->coalesceNow($time);
            $this->validateTimeString($time);
            $time = new \DateTimeImmutable($time);
        } elseif (!($time instanceof \DateTimeInterface)) {
            throw new InvalidWhenArgument(
                'You must provide either a string or a DateTimeInterface'
            );
        }
        $this->time = $time;
    }

    /**
     * commands are only run every minute so you must
     * set it for the minute after now to ensure you're
     * now creating a job in the past and that would never
     * run
     * @param string $time
     * @return string
     */
    protected function coalesceNow($time)
    {
        if ('now' == trim($time) || 'next' == trim($time)) {
            $time = 'now + 1 minute';
        }
        return $time;
    }

    /**
     * @param string $time
     */
    protected function validateTimeString($time)
    {
        $this->validateDateTime(new \DateTimeImmutable($time));
    }

    /**
     * @param \DateTimeInterface $time
     * @throws \Treffynnon\At\Exceptions\InvalidWhenArgument
     */
    protected function validateDateTime(\DateTimeInterface $time)
    {
        $t = new \DateTimeImmutable();
        $diff = $t->diff($time);
        if ($diff->i < 1 ||
            1 === $diff->invert) {
            throw new InvalidWhenArgument(
                'the date you have supplied is from the ' .
                'past so the job would never run. you must ' .
                'specify a time of `now + 1 minute` or greater.'
            );
        }
        return true;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->time->format('YmdHi');
    }
}
