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

function handle_omitted_problems(&$topic_map, $key, $value) {

  if (preg_match("/^omitted_problems_list\[([0-9]+)\]$/", $key, $matches)) {
    $topic_id = $matches[1];

    if (! array_key_exists($topic_id, $topic_map)) {
      $topic_map[$topic_id] = array();
    }
    
    if (is_array($value)) {
      foreach ($value as $index => $problem_id) {
        if (is_numeric($problem_id)) {
          if (array_key_exists($problem_id, $topic_map[$topic_id])) {
            $topic_map[$topic_id][$problem_id] += 1;
          } else {
            $topic_map[$topic_id][$problem_id] = 1;
          }
        }
      }    
    } else {
      if (is_numeric($value)) {
        if (array_key_exists($value, $topic_map[$topic_id])) {
          $topic_map[$topic_id][$value] += 1;
        } else {
          $topic_map[$topic_id][$value] = 1;
        }
      }
    }
  }

}
 
function unpackage($input) {
    return unserialize(stripslashes($input));
}

print "init_omitted_problems started\n";

$selectQuery = "select id, prefs from user";

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
}

$user_map = array();
if ($result = $dbmgr->exec_query($selectQuery)) {
  print "init_omitted_problems query worked\n";
  while ($row = mysqli_fetch_row($result)) {

    $user_id = $row[0];
    $prefs = unpackage($row[1]);
    #printf("   %s\n", $user_id);

    $user_map[$user_id] = array();


    foreach ($prefs as $key => $value) {
      handle_omitted_problems($user_map[$user_id], $key, $value);
    }
  }
  mysqli_free_result($result);
} else {
  print "init_omitted_problems query failed\n";
}

$insertQuery = "insert into omitted_problems (user_id, topic_id, problem_id) values (%s, %s, %s)";

foreach ($user_map as $user_id => $topic_map) {
  foreach ($topic_map as $topic_id => $problem_map) {
    foreach ($problem_map as $problem_id => $count) {
      # printf("%s,%s,%s,%s\n", $user_id, $topic_id, $problem_id, $count);
      $dbmgr->exec_query(sprintf($insertQuery, $user_id, $topic_id, $problem_id));
    }
  }
}



print "init_omitted_problems ended\n";


?>