<?php
// paths
require_once("./paths.inc.php");
// application objects
require_once($GLOBALS["DIR_LIB"]."models.php");
require_once($GLOBALS["DIR_LIB"]."views.php");
require_once( $DIR_LIB."usrmgr.inc.php" );
// utilities
require_once($GLOBALS["DIR_LIB"]."utilities.inc.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.inc.php" );
//$GLOBALS["dbmgr"] = new CDbMgr( "host", "user", "password", "database" );
$GLOBALS["dbmgr"] = new CDbMgr( "localhost", "pr_user", "pr_user", "prexpansion" );
// session
require_once( $DIR_LIB."session.inc.php" );
//$GLOBALS["sessionmgr"] = new CSessMgr( "session_table", 3600);
// url arguments
$args = GrabAllArgs();
// permission
//$args["myid"]="1694654"; // fake the login HACK-ALERT must remove!!!
//$args["myid"]="1234567"; // fake the login HACK-ALERT must remove!!!
//$GLOBALS["usrmgr"] = new CUserManager($args);



/*
if(!$GLOBALS["usrmgr"]->GetAccess()){
	echo "no access roadmap page";
	 direct to the login page :)
	$pageid = "";
	if(isset($args["pageid"]))
		$pageid = "?pageid=".$args["pageid"];
	$url = "Location:".$GLOBALS["DOMAIN"]."login.php".$pageid;
	header($url);
}else{
    $loginout = new CLogout();
}
*/
//business logic

# business logic
$model = new MProblem();

//$pageid = isset($args["pageid"])?$args["pageid"]:null;

// page construction
$head = new CHeadCSSJavascript("Problem Roulette",
    array(
        #$GLOBALS["DOMAIN_CSS"]."test.css",
    ),

    array(
        #$GLOBALS["DOMAIN_JS"]."test.js.php",
    )
);

$body = new VProblemEditReview($model);
$page = new CPageBasic($head, $body);

# delivery the html
echo $page->Deliver();

?>