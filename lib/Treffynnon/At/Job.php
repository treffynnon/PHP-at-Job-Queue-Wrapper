<?php

namespace Treffynnon\At;

/**
 * A simple class for storing a jobs details and some methods for manipulating
 * it. A job model if you will.
 *
 * @author Simon Holywell <treffynnon@php.net>
 *
 * @version 16.11.2010
 */
class Job
{
    /**
     * Data store for the job details.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Magic method to set a value in the $data
     * property of the class.
     *
     * @param string $name The key of data array
     * @param mixed $value The value of data array
     *
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    /**
     * Magic method to get a value in the $data property
     * of the class.
     *
     * @param string $name The key of data array
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        throw new UndefinedPropertyException(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line']
        );
    }

    /**
     * Magic method to check for the existence of an
     * index in the $data property of the class.
     *
     * @param string $name The key of data array
     *
     * @return bool
     */
    public function __isset($name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * Magic method to unset an index in the $data property
     * of the class.
     *
     * @param string $name The key of data array
     *
     * @return void
     */
    public function __unset($name): void
    {
        unset($this->data[$name]);
    }

    /**
     * Remove this job from the queue.
     *
     * @uses $this->remove()
     *
     * @return void
     */
    public function rem()
    {
        $this->remove();
    }

    /**
     * Remove this job from the queue.
     *
     * @return void
     */
    public function remove()
    {
        if (isset($this->job_number)) {
            Wrapper::removeJob((int)$this->job_number);
        }
    }

    /**
     * Get a DateTime object for date and time extracted from
     * the output of `at`.
     *
     * @example echo $job->date()->format('d-m-Y');
     *
     * @param string $date The date string
     *
     * @uses DateTime
     *
     * @return \DateTime A PHP DateTime object
     */
    public function date(string $date)
    {
        return new \DateTime($date);
    }
}
