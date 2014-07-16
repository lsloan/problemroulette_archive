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
$dbmgr->exec_query("INSERT INTO topic VALUES (:null_value,:topic)",array(":null_value"=>Null,":topic"=>$topic));

//get new topic id
$res=$dbmgr->fetch_assoc("SELECT * FROM topic ORDER BY id DESC");
$topic_id = $res[0]['id'];

//insert into 12m_class_topic
$dbmgr->exec_query("INSERT INTO 12m_class_topic VALUES (:null_value,:course_id,:topic_id)"array(":null_value"=>Null,":course_id"=>$course_id,":topic_id"=>$topic_id));

echo $topic." added to ".$course;

?>