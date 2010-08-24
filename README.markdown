PHP at Job Queue Wrapper
======
A PHP class to wrap the Unix/Linux at/atd job queue. At allows you to specify a job that the system should run at certain point in time. For more information on at either run `man at` in your console or visit [man page](http://unixhelp.ed.ac.uk/CGI/man-cgi?at "at Man page on Edinburgh University servers"). This class lets you add new items to the queue either as a command or a path to a file.

PHP 5.3 is required to support the use of the namespace, but this could easily be removed to make it backwards compatible with PHP 5.1 and above.


Requires
--------

* Unix/Linux
* at (you will already have this installed as it comes with Linux distributions)
* PHP 5.3 and above due to the use of namespaces in the code