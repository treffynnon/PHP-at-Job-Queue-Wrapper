<?php

namespace Treffynnon\At;

use Exceptions as E;

/**
 * A simple class for storing a jobs details and some methods for manipulating
 * it. A job model if you will.
 *
 * @author Simon Holywell <treffynnon@php.net>
 */
class Job {
    /**
     * Data store for the job details
     * @var array
     */
    protected $data = array();

    /**
     * Magic method to set a value in the $data
     * property of the class
     * @param string $name
     * @param mixed $value 
     */
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * Magic method to get a value in the $data property
     * of the class
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        throw new E\UndefinedProperty(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line']
        );
    }

    /**
     * Magic method to check for the existence of an
     * index in the $data property of the class
     * @param string $name
     * @return bool
     */
    public function __isset($name) {
        return isset($this->data[$name]);
    }

    /**
     * Magic method to unset an index in the $data property
     * of the class
     * @param string $name 
     */
    public function __unset($name) {
            unset($this->data[$name]);
    }

    /**
     * Remove this job from the queue
     * @uses $this->remove()
     */
    public function rem() {
        return $this->remove();
    }

    /**
     * Remove this job from the queue
     */
    public function remove() {
        if(isset($this->job_number)) {
            Wrapper::removeJob((int)$this->job_number);
        }
    }

    /**
     * Get a DateTime object for date and time extracted from
     * the output of `at`
     * @example echo $job->date()->format('d-m-Y');
     * @uses DateTime
     * @return DateTime A PHP DateTime object
     */
    public function date() {
        return new \DateTime($this->date);
    }
}

