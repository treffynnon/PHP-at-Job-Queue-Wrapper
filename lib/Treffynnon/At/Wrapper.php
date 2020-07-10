<?php
namespace Treffynnon\At;

/**
 * The class that wraps the at binary. It is not feature complete as the
 * native at binary does offer more, but it does contain the commonly used
 * functions and is enough for my purposes.
 *
 * @author Simon Holywell <treffynnon@php.net>
 * @license BSD
 */
class Wrapper
{
    /**
     * The path to the `at` binary.
     *
     * @var string
     */
    protected static $binary = 'at';

    /**
     * Regular expression to get the details of a job from the add job response
     *
     * @var string
     */
    protected static $addRegex = '/^job (\d+) at ([\w\d\- :]+)$/';

    /**
     * A map of the regex matches to their descriptive names
     *
     * @var array
     */
    protected static $addMap = [
        1 => 'job_number',
        2 => 'date',
    ];

    /**
     * Regex to get the vitals from the queue
     *
     * @var string
     */
    protected static $queueRegex = '/^(\d+)\s+([\w\d\- :]+) (\w) ([\w-]+)$/';

    /**
     * A map of the regex matches to their descriptive names
     *
     * @var array
     */
    protected static $queueMap = [
        1 => 'job_number',
        2 => 'date',
        3 => 'queue',
        4 => 'user',
    ];

    /**
     * Location to pipe the output of at commands to
     *
     * I need to combine STDERR and STDOUT for my machine as when adding a new
     * job `at` responds over STDERR because it wants to warn me
     * "warning: commands will be executed using /bin/sh". When getting a list
     * of jobs in the queue however it comes back over STDOUT.
     *
     * Combining the two allows me to use the same pipe command for both types
     * of interaction with `at`. I think it is also the safest way of
     * accommodating users who do not the have the problem of warning being
     * triggered when adding a new job.
     *
     * @var string
     */
    protected static $pipeTo = '2>&1';

    /**
     * Switches/arguments that at uses on the `at` command
     *
     * @var array
     */
    protected static $atSwitches = [
        'queue' => '-q',
        'list_queue' => '-l',
        'file' => '-f',
        'remove' => '-d',
    ];

    /**
     * @param string $command The current command
     * @param string $time Please see `man at`
     * @param string $queue Please look at a-zA-Z and see `man at`
     * @uses self::addCommand
     */
    public static function cmd($command, $time, $queue = null)
    {
        return self::addCommand($command, $time, $queue);
    }

    /**
     * @param string $file Full path to the file to be executed
     * @param string $time Please see `man at`
     * @param string $queue Please look at a-zA-Z and see `man at`
     *
     * @uses self::addFile
     */
    public static function file($file, $time, $queue = null)
    {
        return self::addFile($file, $time, $queue);
    }

    /**
     * @param string $queue Please look at a-zA-Z and see `man at`
     * @uses self::listQueue
     */
    public static function lq($queue = null)
    {
        return self::listQueue($queue);
    }

    /**
     * Add a job to the `at` queue
     *
     * @param string $command The current command
     * @param string $time Please see `man at`
     * @param string $queue Please look at a-zA-Z and see `man at`
     *
     * @return Treffynnon\At\Job
     */
    public static function addCommand($command, $time, $queue = null)
    {
        $command = self::escape($command);
        $time = self::escape($time);
        $exec_string = "echo '$command' | " . self::$binary;
        if (null !== $queue) {
            $exec_string .= ' ' . self::$atSwitches['queue']  . " {$queue[0]}";
        }
        $exec_string .= " $time ";
        return self::addJob($exec_string);
    }

    /**
     * Add a file job to the `at` queue
     *
     * @param string $file Full path to the file to be executed
     * @param string $time Please see `man at`
     * @param string $queue Please look at a-zA-Z and see `man at`
     *
     * @return Treffynnon\At\Job
     */
    public static function addFile($file, $time, $queue = null)
    {
        $file = self::escape($file);
        $time = self::escape($time);
        $exec_string = self::$binary . ' ' . self::$atSwitches['file'] . " $file";
        if (null !== $queue) {
            $exec_string .= ' ' . self::$atSwitches['queue'] . " {$queue[0]}";
        }
        $exec_string .= " $time ";

        return self::addJob($exec_string);
    }

    /**
     * Return a list of the jobs currently in the queue. If you do not specify
     * a queue to look at then it will return all jobs in all queues.
     *
     * @param string $queue Please look at a-zA-Z and see `man at`
     *
     * @return array of Treffynnon\At\Job objects
     */
    public static function listQueue($queue = null)
    {
        $exec_string = self::$binary . ' ' . self::$atSwitches['list_queue'];
        if (null !== $queue) {
            $exec_string .= ' ' . self::$atSwitches['queue'] . " {$queue[0]}";
        }
        $result = self::exec($exec_string);
        return self::transform($result, 'queue');
    }

    /**
    * Remove a job by job number

    * @param int $job_number The current job number
    */
    public static function removeJob($job_number)
    {
        $job_number = self::escape($job_number);
        $exec_string = self::$binary . ' ' . self::$atSwitches['remove'] . " $job_number";
        $output = self::exec($exec_string);
        if (count($output)) {
            throw new JobNotFoundException("The job number $job_number could not be found");
        }
    }

    /**
     * Add a job to the at queue and return the
     *
     * @param string $job_exec_string The job execution string
     *
     * @return Treffynnon\At\Job
     */
    protected static function addJob($job_exec_string)
    {
        $output = self::exec($job_exec_string);
        $job = self::transform($output);
        $failedMessageFormat = 'The job has failed to be added to the queue. Exec command: %s';
        $failedMessage = sprintf($failedMessageFormat, $job_exec_string);
        if (!count($job)) {
            throw new JobAddException($failedMessage);
        }

        return reset($job);
    }

    /**
     * Transform the output of `at` into an array of objects
     *
     * @param array $output_array The output with array
     * @param string $type Is this an add or list we are transforming?
     *
     * @return array An array of Treffynnon\At\Job objects
     *
     * @uses Treffynnon\At\Job
     */
    protected static function transform($output_array, $type = 'add')
    {
        $jobs = [];

        // Get the appropriate regex class property for the type
        // of `at` switch/command being run at this point in time.
        $regex = $type . 'Regex';
        $regex = self::$$regex;

        $map = $type .'Map';
        $map = self::$$map;

        foreach ($output_array as $line) {
            $matches = [];
            preg_match($regex, $line, $matches);
            if (count($matches) > count($map)) {
                $jobs[] = self::mapJob($matches, $map);
            }
        }
        return $jobs;
    }

    /**
     * Map the details matched with the regex to descriptively named properties
     * in a new Treffynnon\At\Job object
     *
     * @param array $details The details about job description
     * @param array $map The mapped job array
     *
     * @return Treffynnon\At\Job
     */
    protected static function mapJob($details, $map)
    {
        $Job = new Job();
        foreach ($details as $key => $detail) {
            if (isset($map[$key])) {
                $Job->{$map[$key]} = $detail;
            }
        }
        return $Job;
    }

    /**
     * Escape a string that will be passed to exec
     *
     * @param string $string The executed command
     *
     * @return string
     */
    protected static function escape($string)
    {
        return escapeshellcmd($string);
    }

    /**
     * Run the command via exec() and return each line of the output as an
     * array
     *
     * @param string $string The executed string
     *
     * @return array Each line of output is an element in the array
     */
    protected static function exec($string)
    {
        $output = [];
        $string .= ' ' . self::$pipeTo;
        exec($string, $output);
        return $output;
    }
}
