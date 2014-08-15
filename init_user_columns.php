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


global $dbmgr;
global $usrmgr;
 
function unpackage($input) {
  return unserialize(stripslashes($input));
}

function package($input) {
  return addslashes(serialize($input));
}


print "init_current_course_id started\n";

$selectQuery = "select id, prefs from user";
$updateQuery = "update user set %s=:value where id=:user_id";
$updatePrefsQuery = "update user set prefs=:prefs where id=:user_id";
$selectedTopicQuery = "insert into selected_topics (user_id, topic_id) values (:user_id, :topic_id)";


$handle = fopen("init_user_columns.log", "w");

$user_count = 0;

$result = $dbmgr->fetch_assoc($selectQuery);
if (is_array($result)) {
  printf("init_current_course_id query fetched %s user records\n", count($result));

  foreach ($result as $row_num => $row) {

    $user_id = $row["id"];
    $prefs = unpackage($row["prefs"]);
    #printf("   %s\n", $user_id);

    $dead_prefs = [];

    foreach ($prefs as $key => $value) {
      if (is_null($value)) {

      }  elseif (is_array($value)) {
        fprintf($handle, "%s => %s => %s\n", $user_id, $key, 'an array' );
      } else {
        fprintf($handle, "%s => %s => %s\n", $user_id, $key, $value );
      }
      
      if ($key == 'selected_course') {
        if (is_null($value) || $value == '') {
          
        } else {
          $bindings = array(":value" => $value, ":user_id" => $user_id);
          $dbmgr->exec_query(sprintf($updateQuery, 'selected_course_id'), $bindings);
        }
        # $dead_prefs[] = $key;
      } elseif ($key == 'selected_topics_list') {
        if (is_array($value)) {
          foreach ($value as $index => $topic_id) {
            $bindings = array(':user_id' => $user_id, ':topic_id' => $topic_id);
            $dbmgr->exec_query($selectedTopicQuery, $bindings);
          }
          # $dead_prefs[] = $key;
        } else {
          $bindings = array(':user_id' => $user_id, ':topic_id' => $value);
          $dbmgr->exec_query($selectedTopicQuery, $bindings);
          # $dead_prefs[] = $key;
        }
      } elseif ($key == 'page_loads') {
        $bindings = array(":value" => $value, ":user_id" => $user_id);
        $dbmgr->exec_query(sprintf($updateQuery, $key), $bindings);
        # $dead_prefs[] = $key;
      } elseif ($key == 'last_activity') {
        $bindings = array(":value" => date("Y-m-d H:i:s", $value), ":user_id" => $user_id);
        $dbmgr->exec_query(sprintf($updateQuery, $key), $bindings);
        # $dead_prefs[] = $key;
      } elseif (preg_match("/^(bugcheck[0-9]+)$/", $key, $matches)) {
        # $dead_prefs[] = $matches[1];
      } elseif (preg_match("/^(omitted_problems_list\[[0-9]+\])$/", $key, $matches)) {
        # $dead_prefs[] = $matches[1];
      } elseif ($key == 'start_time' || $key == 'end_time' || $key == 'current_problem' || $key == 'problem_submitted') {
        # $dead_prefs[] = $key;
      }
    }
    // if (count($dead_prefs) > 0) {
    //   foreach ($dead_prefs as $key => $value) {
    //     unset($prefs[$value]);
    //   }
    //   $bindings = array(":prefs" => package($prefs), ":user_id" => $user_id);
    //   $dbmgr->exec_query($updatePrefsQuery, $bindings);
    // }
    $user_count++;
    if ($user_count % 1000 == 0) {
      printf("  Migrated %s users.\n", $user_count);
    }
  }
  
} else {
  print "init_current_course_id query failed\n";
}


printf("  Migrated %s users.\n", $user_count);

print "init_current_course_id done\n";


?>