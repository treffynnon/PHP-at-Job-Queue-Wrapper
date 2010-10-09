<?php
//require_once 'PHPUnit/Framework.php';
require_once 'at.wrapper.php';
use Treffynnon\At\Wrapper as At;

class AtWrapperTest extends PHPUnit_Framework_TestCase {
    protected $test_file = '';
    public function setUp() {
        $this->test_file = tempnam(sys_get_temp_dir(), 'php');
    }
    public function testAtCmd() {
        $job = 'echo "hello" | wall';
        $time = 'now + 1min';
        $obj = At::cmd($job, $time);
        $this->assertType('Treffynnon\At\Job', $obj);
        $this->cleanUpJobs($obj);
    }

    public function testAtFile() {
        $file = $this->test_file;
        $time = 'now + 1min';
        $obj = At::file($file, $time);
        $this->assertType('Treffynnon\At\Job', $obj);
        $this->cleanUpJobs($obj);
    }
    
    public function testAtLq() {
        $this->setDependencies(array('testAtFile','testAtCmd'));
        $job = 'echo "hello" | wall';
        $time = 'now + 1min';
        At::cmd($job, $time);
        At::cmd($job, $time);
        $array = At::lq();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $array);
        $this->assertGreaterThanOrEqual(2, count($array));
        $this->cleanUpJobs($array);
    }
    
    public function tearDown() {
        unlink($this->test_file);
    }
    
    private function cleanUpJobs($jobs) {
        if(!is_array($jobs)) {
            $jobs = array($jobs);
        }
        foreach($jobs as $job) {
            try {
                $job->rem();
            } catch(JobNotFoundException $e) {
                
            }
        }
    }
}