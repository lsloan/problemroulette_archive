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

//business logic

// page construction
$head = new CHeadCSSJavascript("Problem Roulette",
    array(
        $GLOBALS["DOMAIN_CSS"]."css/bootstrap.css",
    ),

    array(
        $GLOBALS["DOMAIN_JS"]."js/bootstrap.js",
        $GLOBALS["DOMAIN_JS"]."js/jquery-1.10.1.js",
    )
);

$topic_nav =  new VCourseTopicNav(new MCourseTopicNav());
$tab_nav = new VTabNav(new MTabNav('Problems'));
$content = new VProblems();
$page = new VPageTabs($head, $tab_nav, $topic_nav, $content);

# delivery the html
echo $page->Deliver();

?>
