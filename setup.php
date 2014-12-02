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

if (!extension_loaded('json')) {
    dl('json.so');
}

global $dbmgr;
global $usrmgr;

session_start();
$_SESSION['sesstest'] = 1;

?>
