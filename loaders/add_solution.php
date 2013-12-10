<?php
// paths
require_once("./paths.inc.php");
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

global $dbmgr;

$row = 1;
if (($handle = fopen("csvProbs/stats250v3.csv","r")) !== FALSE)
{
	while (($data = fgetcsv($handle,10000,", ")) !== FALSE)
	{
		$num = count($data);

		$url = $data[2];
		$solution = $data[5];

		//SEARCH TO SEE IF PROBLEM EXISTS
		$selectquery = "SELECT * FROM problems WHERE url='".$url."'";
		$res=$dbmgr->fetch_assoc($selectquery);
		$p_id = $res[0]['id'];

		//UPDATE PROBLEM IN PROBLEM TABLE
		$updatequery = "UPDATE problems SET solution='".$solution."' WHERE id=$p_id";
		$dbmgr->exec_query($updatequery);

		$row++;
	}
	fclose($handle);
}

