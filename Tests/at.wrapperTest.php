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
    }

    public function testAtFile() {
        $file = $this->test_file;
        $time = 'now + 1min';
        $obj = At::file($file, $time);
        $this->assertType('Treffynnon\At\Job', $obj);
    }
    
    public function tearDown() {
        unlink($this->test_file);
    }
}
