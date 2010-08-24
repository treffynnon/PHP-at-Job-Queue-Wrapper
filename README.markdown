PHP at Job Queue Wrapper
======
A PHP class to wrap the Unix/Linux at/atd job queue. At allows you to specify a job that the system should run at certain point in time. For more information on at either run `man at` in your console or visit [man page](http://unixhelp.ed.ac.uk/CGI/man-cgi?at "at Man page on Edinburgh University servers"). This class lets you add new items to the queue either as a command or a path to a file and it can also give you back a list of the jobs already in the queue. You have the option to supply a queue letter that the wrapper should use so you can seperate out your jobs. For example:

	<?php
	require_once 'at.wrapper.php';
	use Treffynnon\At\Wrapper as At
	
	// create a command job
	$job = 'echo "hello" | wall';
	$time = 'now + 1min';
	// save command job to queue letter c
	$queue = 'c';
	At::cmd($job, $time, $queue);
	
	// create a file job
	$job = '/home/example/example.sh';
	// save file job to queue letter f
	$queue = 'f';
	At::file($job, $time, $queue);
	
	// create a file job and send it to
	// the default queue (determined by at)
	At::file($job, $time);
	
	// get a list of all the jobs in the queue
	var_dump(At::lq());
	
	// get a list of all the jobs in the f queue
	var_dump(At::lq('f'));
	
	// get a list of all the jobs in the c queue
	var_dump(At::listQueue('c'));
	?>

PHP 5.3 is required to support the use of the namespace, but this could easily be removed to make it backwards compatible with PHP 5.1 and above.


Requires
--------

* Unix/Linux
* at (you will already have this installed as it comes with Linux distributions)
* PHP 5.3 and above due to the use of namespaces in the code