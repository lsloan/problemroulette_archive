<?php
// paths
require_once("./paths.inc.php");
// database
require_once( $GLOBALS["DIR_LIB"]."dbmgr.php" );
$GLOBALS["dbmgr"] = new CDbMgr();

global $dbmgr;
//COURSE TO ADD TO
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWW(OPTIONAL)
$course = "MCDB 310";
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
$course_id = 8;

//TOPIC NAME
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
$topic = "Chapter 25, 26, 27";

//insert new topic
$insertquery = "INSERT INTO topic VALUES (Null,'".$topic."')";
$dbmgr->exec_query($insertquery);

//get new topic id
$selectquery = "SELECT * FROM topic ORDER BY id DESC";
$res=$dbmgr->fetch_assoc($selectquery);
$topic_id = $res[0]['id'];

//insert into 12m_class_topic
$insertquery = "INSERT INTO 12m_class_topic VALUES (Null,'".$course_id."','".$topic_id."')";
$dbmgr->exec_query($insertquery);

echo $topic." added to ".$course;

?>