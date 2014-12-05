<?php

require_once("setup.php");

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
    } elseif ($key == 'selected_topics_list') {
      if (is_array($value)) {
        foreach ($value as $index => $topic_id) {
          $bindings = array(':user_id' => $user_id, ':topic_id' => $topic_id);
          $dbmgr->exec_query($selectedTopicQuery, $bindings);
        }
      } else {
        $bindings = array(':user_id' => $user_id, ':topic_id' => $value);
        $dbmgr->exec_query($selectedTopicQuery, $bindings);
      }
    } elseif ($key == 'page_loads') {
      $bindings = array(":value" => $value, ":user_id" => $user_id);
      $dbmgr->exec_query(sprintf($updateQuery, $key), $bindings);
    } elseif ($key == 'last_activity') {
      $bindings = array(":value" => date("Y-m-d H:i:s", $value), ":user_id" => $user_id);
      $dbmgr->exec_query(sprintf($updateQuery, $key), $bindings);
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
