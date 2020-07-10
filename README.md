# PHP at Job Queue Wrapper

![build](https://github.com/treffynnon/PHP-at-Job-Queue-Wrapper/workflows/build/badge.svg) [![Latest Stable Version](https://poser.pugx.org/treffynnon/php-at-job-queue-wrapper/v)](//packagist.org/packages/treffynnon/php-at-job-queue-wrapper) [![Total Downloads](https://poser.pugx.org/treffynnon/php-at-job-queue-wrapper/downloads)](//packagist.org/packages/treffynnon/php-at-job-queue-wrapper) [![Latest Unstable Version](https://poser.pugx.org/treffynnon/php-at-job-queue-wrapper/v/unstable)](//packagist.org/packages/treffynnon/php-at-job-queue-wrapper) [![License](https://poser.pugx.org/treffynnon/php-at-job-queue-wrapper/license)](//packagist.org/packages/treffynnon/php-at-job-queue-wrapper)

A PHP class to wrap the Unix/Linux at/atd job queue. At allows you to specify a job that the system should run at certain point in time. For more information on at either run `man at` in your console or visit [man page][man-page] This class lets you add new items to the queue either as a command or a path to a file and it can also give you back a list of the jobs already in the queue. You have the option to supply a queue letter that the wrapper should use so you can seperate out your jobs. For example::

# Migrate to v2

**Since the PHP 7.x version has been released for about whiles, the `1.x` version will not be active and please concern about `2.x` version.**

```php
require 'vendor/autoload.php';
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
```

PHP 5.3 is required to support the use of the namespace, but this could easily be removed to make it backwards compatible with PHP 5.1 and above.


## Requires

* Unix/Linux
* at (you will already have this installed as it comes with Linux distributions)
* If using PHP 5.3 and above due to the use of namespaces in the code, please use `1.x` versions.
* If using PHP 7.2+ version, please use `2.x` versions. **It will not support PHP 7.0 and PHP 7.1 versions**.

## Installation

With composer already setup/init in your project you can add this project with:

    composer require treffynnon/php-at-job-queue-wrapper

## Running the Tests

Clone this repository and install the development dependencies using composer:

    composer install

Once complete you can simply run:

    composer run-script test

from the root directory of the at Job Queue Wrapper.


## Troubleshooting Failed Tests

The tests may fail if your version of `at` outputs differently when a new job is added or when the `at` queue is listed. The class has to long but simple regular expressions defined as properties in it. With a little knowledge of regex you can modify these to suit the output of the `at` binary on your operating system.

If you find that you need to modify these regexs then please lodge a ticket on [github][github].


## Currently Tested Output Styles

### Redhat

#### Add job

    [root@server home]#  echo 'echo "hello" \| wall' | at now +10min
    job 3 at 2010-11-15 10:54


#### List queue

    [root@server home]# at -l
    2       2010-11-15 10:53 a root
    3       2010-11-15 10:54 a root


### Ubuntu

#### Add job

    user@server:~$ echo 'echo "hello" \| wall' | at now +10min
    warning: commands will be executed using /bin/sh
    job 17 at Mon Nov 15 10:55:00 2010


#### List queue

    user@server:~$ at -l
    17      Mon Nov 15 10:55:00 2010 a simon
    18      Mon Nov 15 10:55:00 2010 a simon


[github]: https://github.com/treffynnon/PHP-at-Job-Queue-Wrapper/issues
[man-page]: http://unixhelp.ed.ac.uk/CGI/man-cgi?at
