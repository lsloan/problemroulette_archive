<?php
require_once 'setup.php';
require_once 'vendor/autoload.php';

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
        /** @var $VIADUTOO_REDIS_ENABLED bool */
        global $VIADUTOO_REDIS_ENABLED;
        /** @var $VIADUTOO_REDIS_QUEUE_NAME string */
        global $VIADUTOO_REDIS_QUEUE_NAME;

        if (!isset($VIADUTOO_REDIS_ENABLED) ||
            $VIADUTOO_REDIS_ENABLED !== true
        ) {
            die('Configuration setting "VIADUTOO_REDIS_ENABLED" must be set to "true".' . "\n");
        }

        // Set the QUEUE environment variable to the queue name if not already set
        if (empty(getenv('QUEUE'))) {
            if (!isset($VIADUTOO_REDIS_QUEUE_NAME) ||
                empty($VIADUTOO_REDIS_QUEUE_NAME)
            ) {
                die('Configuration setting "VIADUTOO_REDIS_QUEUE_NAME" or environment variable "QUEUE" must be set to name of Redis queue.' . "\n");
            }

            putenv('QUEUE=' . $VIADUTOO_REDIS_QUEUE_NAME);
        }

        // Since this PHP file has executable code at its root, it runs immediately.
        include_once 'vendor/bin/resque';
    }
}

// Run only when invoked from commandline
if (php_sapi_name() === 'cli') {
    ResqueWorker::run();
}
