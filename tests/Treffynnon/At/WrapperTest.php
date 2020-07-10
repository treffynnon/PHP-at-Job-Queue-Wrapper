<?php

namespace Treffynnon\At\Tests;

use PHPUnit\Framework\TestCase;
use Treffynnon\At\JobNotFoundException;
use Treffynnon\At\Wrapper as At;

class WrapperTest extends TestCase
{
    /**
     * The tested file name.
     *
     * @var string
     */
    protected $test_file = '';

    /**
     * PHPUnit fixtures for setUp.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->test_file = tempnam(sys_get_temp_dir(), 'php');
    }

    /**
     * Test that can create a scheduled job with at.
     *
     * @return void
     */
    public function testAtCmd()
    {
        $job = 'echo "hello" | wall';
        $time = 'now + 1min';
        $obj = At::cmd($job, $time);
        $this->assertInstanceOf('Treffynnon\At\Job', $obj);
        $this->cleanUpJobs($obj);
    }

    /**
     * Test that can use file to create scheduled job with at.
     *
     * @return void
     */
    public function testAtFile()
    {
        $file = $this->test_file;
        $time = 'now + 1min';
        $obj = At::file($file, $time);
        $this->assertInstanceOf('Treffynnon\At\Job', $obj);
        $this->cleanUpJobs($obj);
    }

    /**
     * Test that can get the queued job lists with at.
     *
     * @return void
     */
    public function testAtLq()
    {
        $this->setDependencies(['testAtFile', 'testAtCmd']);
        $job = 'echo "hello" | wall';
        $time = 'now + 1min';
        At::cmd($job, $time, 't');
        At::cmd($job, $time, 't');
        $array = At::lq('t');
        $this->assertIsArray($array);
        $this->assertGreaterThanOrEqual(2, count($array));
        $this->cleanUpJobs($array);
    }

    /**
     * Test that can use regular expression to parse queued job lists with at.
     *
     * @return void
     */
    public function testRegressionIssue2UsernameRegexDoesntSupportHyphens()
    {
        $regex = TestableAtWrapper::getQueueRegex();
        $test_strings = [
            '17      Mon Nov 15 10:55:00 2010 a simon',
            '18      Mon Nov 15 10:55:00 2010 a simons-username',
            '2       2010-11-15 10:53 a root',
            '3       2010-11-15 10:54 a root-username-',
        ];
        $m = 0;
        foreach ($test_strings as $test) {
            $m += preg_match($regex, $test);
        }
        $this->assertSame($m, count($test_strings));
    }

    /**
     * PHPUnit fixtures for tearDown.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unlink($this->test_file);
    }

    /**
     * Clean up job lists.
     *
     * @param mixed $jobs The queued job lists
     *
     * @return void
     */
    private function cleanUpJobs($jobs)
    {
        if (!is_array($jobs)) {
            $jobs = [$jobs];
        }
        foreach ($jobs as $job) {
            try {
                $job->rem();
            } catch (JobNotFoundException $e) {
            }
        }
    }
}
