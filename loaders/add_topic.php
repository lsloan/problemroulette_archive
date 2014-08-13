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
$query = "INSERT INTO topic (name) VALUES (:name)";
$bindings = array(":name" => $topic);
$dbmgr->exec_query( $query , $bindings );

//get new topic id
$query = "SELECT * FROM topic ORDER BY id DESC";
$res=$dbmgr->fetch_assoc( $query );
$topic_id = $res[0]['id'];

//insert into 12m_class_topic
$query =
"INSERT INTO 12m_class_topic (class_id, topic_id) ".
"VALUES (:class_id, :topic_id)";
$bindings = array(
	":class_id" => $course_id,
	":topic_id" => $topic_id);
$dbmgr->exec_query( $query , $bindings );

echo $topic." added to ".$course;

?>
