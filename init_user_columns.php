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

function init_new_columns($user_id, $prefs) {

  global $dbmgr;
  global $usrmgr;


  $updateQuery = "update user set %s=:value where id=:user_id";
  $updatePrefsQuery = "update user set prefs=:prefs where id=:user_id";
  $selectedTopicQuery = "insert into selected_topics (user_id, topic_id) values (:user_id, :topic_id)";

  foreach ($prefs as $key => $value) {
    
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
}

function step_through_users() {

  global $dbmgr;
  global $usrmgr;

  print "<p>step_through_users started</p><hr>\n";

  $selectQuery = "select id, prefs from user";

  $user_count = 0;

  $result = $dbmgr->fetch_assoc($selectQuery);
  if (is_array($result)) {
    printf ("<p>  fetched %s user records</p>\n", count($result));

    foreach ($result as $row_num => $row) {

      $user_id = $row["id"];
      $prefs = unpackage($row["prefs"]);

      init_new_columns($user_id, $prefs);

      $user_count = $user_count + 1;
      if ($user_count % 200 == 0) {
        printf("<p>Migrated %s users.</p>\n", $user_count);
      }
    }
  } else {
    print "<p>step_through_users query failed</p>\n";
  }
  
  printf("<hr>\n<p>Migrated %s users.</p>\n", $user_count);

  print "<p>step_through_users done.</p>\n";
}

step_through_users();

?>
