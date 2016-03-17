<?php

namespace Treffynnon\At;

use Treffynnon\At\Exceptions\InvalidWhenArgument;

class When
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
         $this->setTime($time);
    }

    public function setTime($time)
    {
        if (is_string($time)) {
            $time = $this->coalesceNow($time);
            $this->validateTimeString($time);
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
        if ('now' == trim($time)) {
            $time = trim($time) . ' + 1min';
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
                'specify a time of `now + 1min` or greater.'
            );
        }
    }

    /**
     * @return string
     */
    public function getTime()
    {
        if ($this->time instanceof \DateTimeInterface) {
            $this->validateDateTime($this->time);
            return $this->time->format('YmdHi');
        }
        return $this->time;
    }
}
