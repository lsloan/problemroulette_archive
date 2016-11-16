<?php
require_once 'setup.php';
require_once 'vendor/autoload.php';
require_once 'ViadutooJob.php';

/**
 * Set up the environment and run Resque worker(s).
 * Invoked as:
 *
 *     php ResqueWorker.php
 *
 * Since PHP Resque uses environment variables to configure the worker, they
 * will work with this program, too.  There's one difference:  If the queue
 * name is not specified in the "QUEUE" environment variable, it will be set
 * to 'default'.  That allows a short commandline when invoking this program.
 *
 * Other possibly useful environment variables used by Resque workers:
 *
 *     COUNT (default: 1)
 *         Number of worker processes to fork.
 *
 *     PIDFILE (default: none)
 *         Pathname of file to write PID.
 *
 * See the PHP Resque documentation in README.md and the source of bin/resque
 * for further details on available configuration options:
 *
 *     https://github.com/chrisboulton/php-resque/blob/master/README.md
 *
 *     https://github.com/chrisboulton/php-resque/blob/master/bin/resque
 */
class ResqueWorker {
    public static function run() {
        putenv('QUEUE=' . (getenv('QUEUE') ?: 'default'));

        // Since this PHP file has executable code at its root, it runs immediately.
        include_once 'vendor/bin/resque';
    }
}

// Run only when invoked from commandline
if (php_sapi_name() === 'cli') {
    ResqueWorker::run();
}