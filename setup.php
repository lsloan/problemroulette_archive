<?php
// paths
require_once("./paths.inc.php");
// error
require_once($GLOBALS["DIR_LIB"]."errors.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
$GLOBALS["dbmgr"] = new CDbMgr();
// user manager
require_once( $DIR_LIB."usrmgr.php" );
$GLOBALS["usrmgr"] = new UserManager();
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.php");
$args = GrabAllArgs();
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");

require_once( $GLOBALS["DIR_LIB"]."logger.php" );
# TODO: add server name to name of app-logger file
# $GLOBALS['app_log'] = new AppLogger($GLOBALS['DIR_LOGGER']."probroul_".$server_name.".log");
$GLOBALS['app_log'] = new AppLogger($GLOBALS['DIR_LOGGER']."problem_roulette.log");

if (!extension_loaded('json')) {
    dl('json.so');
}

global $dbmgr;
global $usrmgr;
global $app_log;

session_start();
$_SESSION['sesstest'] = 1;

?>
