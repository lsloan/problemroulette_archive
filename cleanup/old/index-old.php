<?php
// paths
require_once("./paths.inc.php");
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");
require_once( $DIR_LIB."usrmgr.php" );
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
//$GLOBALS["dbmgr"] = new CDbMgr( "host", "user", "password", "database" );
$GLOBALS["dbmgr"] = new CDbMgr( "localhost", "pr_user", "pr_user", "prexpansion" );
// session
//require_once( $DIR_LIB."sessions.php" );
//$GLOBALS["sessionmgr"] = new CSessMgr( "session_table", 3600);
// url arguments
$args = GrabAllArgs();
// permission
//$args["myid"]="1694654"; // fake the login HACK-ALERT must remove!!!
//$args["myid"]="1234567"; // fake the login HACK-ALERT must remove!!!
//$GLOBALS["usrmgr"] = new CUserManager($args);


echo "hi from php";

?>
