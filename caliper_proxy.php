<?php
/*
 * A web service that uses Viadutoo to receive a Caliper event
 * (a JSON payload) to be sent to an endpoint.  If the sending fails,
 * the event is stored in the local database.
 */

require_once 'setup.php';
require_once 'vendor/autoload.php';
require_once 'ViadutooController.php';

/** @global $app_log AppLogger */
global $app_log;
/** @global $dbmgr CDbMgr */
global $dbmgr;

(new ViadutooController($caliper_config, $app_log, $dbmgr))->run();