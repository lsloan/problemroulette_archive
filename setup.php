<?php
// paths
require_once(dirname(__FILE__)."/paths.inc.php");
// error
require_once($GLOBALS["DIR_LIB"]."errors.php");

// logger
if (isset($GLOBALS['DIR_LOGGER']))
{
  # TODO: Verify that php_uname('n') gives server name in production.
  #       For php v5.3+ could use gethostname().
  $hostname = php_uname('n');
  $log_file = $GLOBALS['DIR_LOGGER']."problem_roulette_".$hostname.".log";
} else {
  $log_file = "/var/tmp/problem_roulette.log";
}
require_once( $GLOBALS["DIR_LIB"]."logger.php" );
$GLOBALS['app_log'] = new AppLogger($log_file);
global $app_log;

$GLOBALS['DEBUG'] = (isset($GLOBALS['DEBUG']) ? ((bool) $GLOBALS['DEBUG']) : false);

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

if (!extension_loaded('json')) {
    dl('json.so');
}

global $dbmgr;
global $usrmgr;

session_start();
$_SESSION['sesstest'] = 1;

?>
