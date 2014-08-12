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
$updateQuery = "update user set %s=%s where id=%s";
$updatePrefsQuery = "update user set prefs='%s' where id='%s'";


if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
}

$handle = fopen("init_user_columns.log", "w");

$user_count = 0;

if ($result = $dbmgr->exec_query($selectQuery)) {
  printf("init_current_course_id query fetched %s user records\n", $result->num_rows);
  while ($row = mysqli_fetch_row($result)) {

    $user_id = $row[0];
    $prefs = unpackage($row[1]);
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
          $dbmgr->exec_query(sprintf($updateQuery, 'current_course_id', $value, $user_id));
          $dead_prefs[] = $key;
        }
      } elseif ($key == 'page_loads') {
        $dbmgr->exec_query(sprintf($updateQuery, $key, $value, $user_id));
        $dead_prefs[] = $key;
      } elseif ($key == 'last_activity') {
        $dbmgr->exec_query(sprintf($updateQuery, $key, sprintf("'%s'",date("Y-m-d H:i:s", $value)), $user_id));
        $dead_prefs[] = $key;
      } elseif (preg_match("/^(bugcheck[0-9]+)$/", $key, $matches)) {
        $dead_prefs[] = $matches[1];
      } elseif (preg_match("/^(omitted_problems_list\[[0-9]+\])$/", $key, $matches)) {
        $dead_prefs[] = $matches[1];
      } elseif ($key == 'start_time' || $key == 'end_time' || $key == 'current_problem' || $key == 'problem_submitted') {
        $dead_prefs[] = $key;
      }
    }
    if (count($dead_prefs) > 0) {
      foreach ($dead_prefs as $key => $value) {
        unset($prefs[$value]);
      }
      $dbmgr->exec_query(sprintf($updatePrefsQuery, package($prefs), $user_id));
    }
    $user_count++;
    if ($user_count % 1000 == 0) {
      printf("  Migrated %s users.\n", $user_count);
    }
  }
  mysqli_free_result($result);
} else {
  print "init_current_course_id query failed\n";
}


printf("  Migrated %s users.\n", $user_count);

print "init_current_course_id done\n";


?>