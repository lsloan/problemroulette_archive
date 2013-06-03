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
require_once( $DIR_LIB."sessions.php" );
//$GLOBALS["sessionmgr"] = new CSessMgr( "session_table", 3600);
// url arguments
$args = GrabAllArgs();

// permission
/*
$GLOBALS["usrmgr"] = new CUserManager($args);
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

// business logic

// page construction
$head = new CHeadCSSJavascript("Problem Roulette",
    array(
        $GLOBALS["DOMAIN_CSS"]."tabs.css",
        #$GLOBALS["DOMAIN_CSS"]."test.css",
    ),

    array(
        #$GLOBALS["DOMAIN_JS"]."tabs.js",
        #$GLOBALS["DOMAIN_JS"]."test.js.php",
    )
);

//$body = new VProblemEditReview($model);
//$page = new CPageBasic($head, $body);
$content = new VHome();
$nav = new VTabMenu();
$page = new VPageTabs($head, $nav, $content);

# delivery the html
echo $page->Deliver();

?>
