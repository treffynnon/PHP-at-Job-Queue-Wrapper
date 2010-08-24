<?php
require_once 'at.wrapper.php';

// Alias see: http://www.php.net/manual/en/language.namespaces.importing.php
use Treffynnon\At\Wrapper as At;

/**
 * As per docs in class you can use:
 * - cmd() or addCommand()
 * - file() or addFile()
 * - lq() or listQueue()
 *
 * interchangeably as they are effectively aliases of each other.
 */

$email = 'example@example.org';
$subject = 'Testing at wrapper';
$body = '';

$job_cmd = "php -r 'mail($email, $subject, $body);'";
$job_file = __FILE__;
$job_time = 'now + 1min';
try {
    var_dump('---Command: Adding to at---');
    $cmd = At::cmd($job_cmd, $job_time);
    var_dump($cmd);

    var_dump('---File: Adding to at---');
    $file = At::file($job_file, $job_time);
    var_dump($file);

    $list = At::lq();
    var_dump('---Get Queue List---');
    var_dump($list);

    var_dump('---File: Remove from queue---');
    $file->remove();


    $list = At::listQueue();
    var_dump('---Get Queue List---');
    var_dump($list);
} catch(\Exception $e) {
    var_dump($e);
}