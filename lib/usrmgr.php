<?php

// The User class represents people who use ProblemRoulette. 
// When someone logs in, a User object is created and initialized
// with information that has been obtained from the campus 
// directory and/or saved from earlier sessions. As people 
// are active in PR, information about their activity is 
// saved in the database through their User object.
//
// Simple permissions are enforced based on information saved 
// about Users.  Permissions can be described as follows:
//
// 1) All users can select courses and topics, work
//    problems and view information about their own 
//    performance.
// 2) Users can be designated as "staff", which gives
//    them permission to create or modify classes, topics
//    and problems.  People designated as "staff" can also
//    view information about other users' performance.
// 3) Users can be designated as "researcher", which gives 
//    them permission to export statistical information 
//    about users.
//
class MUser
{
    var $id;
    var $username;
    var $prefs = null;
    var $staff = 0;
    var $researcher = 0;
    var $admin = 0;
    var $selected_course_id;
    var $page_loads;
    var $last_activity;
    var $selection_id;
    var $selected_topics_list;

    function __construct($username, $staff=0)
    {
        $this->username = $username;
        // look up user - create user (if not found)
        if($this->read())
            return;
        else
            $this->create();
    }

    function create()
    {
        try 
        {
            global $dbmgr;
            date_default_timezone_set('America/New_York');
            $query =
                "INSERT INTO user(username, staff, prefs, created_at) ".
                "VALUES ( :username, :staff, :prefs, :created_at )";
            $bindings = array(
                ":username" => $this->username,
                ":staff"    => $this->staff,
                ":prefs"    => $this->package(Array()),
                ":created_at" => date('Y-m-d H:i:s'));

            $dbmgr->exec_query( $query, $bindings );
            
            $this->get_id();
        } 
        catch(Exception $e)
        {
            global $app_log;
            $app_log->msg("MUser->create() ERROR while attempting to create user record for ".$this->username);
            $app_log->msg("MUser->create() Exception:\n".print_r($e, true));
            $app_log->msg("MUser->create() backtrace: \n".print_r(debug_backtrace(), true));
        }
    }
  
    function package($input)
    {
        return addslashes(serialize($input));
    }

    function unpackage($input)
    {
        return unserialize(stripslashes($input));
    }
 
    function get_id()
    {
        global $dbmgr;
        $username = $this->username;
        $query = "SELECT id FROM user WHERE username = :username order by id asc";
        $bindings = array(':username' => $username);
        $res = $dbmgr->fetch_assoc( $query , $bindings );
        // populate user (if found)
        if(count($res) > 0)
        {
            $this->id = $res[0]['id'];

            if(count($res) > 1)
            {
                global $app_log;
                $app_log->msg("MUser->get_id() ERROR: More than one user ID found for ".
                    $this->username.
                    ". IDs found:\n".
                    print_r($res, true));
                $app_log->msg("MUser->get_id() backtrace: \n".
                    print_r(debug_backtrace(), true));
            }

            return True;
        }

        return False;
    }
 
    function read()
    {
        global $dbmgr;
        // $query = "SELECT id, staff, prefs, page_loads, last_activity, selection_id, FROM user WHERE username = :username";
        $query = "SELECT t1.id id, t1.staff staff, t1.researcher researcher, t1.admin admin, 
            t1.prefs prefs, t1.page_loads page_loads, t1.last_activity last_activity, 
            t1.selection_id selection_id, t2.class_id selected_course_id 
            FROM user t1 left join selections t2 
            on t1.selection_id=t2.id 
            WHERE t1.username = :username 
            order by t1.id asc";
        $bindings = array(":username" => $this->username);
        try {
            $res = $dbmgr->fetch_assoc( $query , $bindings );
        } catch (Exception $e) {
            error_log(sprintf("UserManager.read ERROR: %s", print_r($e, true)));
            var_dump($e->getTrace());
        }
        // populate user (if found)
        if(count($res) > 0)
        {
            $this->id = $res[0]['id'];
            $this->staff = $res[0]['staff'];
            $this->researcher = $res[0]['researcher'];
            $this->admin = $res[0]['admin'];
            $this->prefs = $this->unpackage($res[0]['prefs']);
            $this->page_loads = $res[0]['page_loads'];
            if ($res[0]['last_activity'] != Null) {
                $this->last_activity = strtotime($res[0]['last_activity']);
            }
            $this->selection_id = $res[0]['selection_id'];
            $this->selected_course_id = $res[0]['selected_course_id'];
            $this->LoadSelectedTopics();

            if(count($res) > 1)
            {
                global $app_log;
                $app_log->msg("MUser->read() ERROR: More than one user record found for ".$this->username.". Records found:\n".print_r($res, true));
                $app_log->msg("MUser->read() backtrace: \n".print_r(debug_backtrace(), true));
            }

            return True;
        }

        return False;

    }
 
    function WritePrefs()
    {   // write all prefs back to user table
        global $dbmgr;
        $query = "UPDATE user SET prefs = :prefs WHERE username = :username";
        $bindings = array(
            ":prefs"    => $this->package($this->prefs),
            ":username" => $this->username);
        $dbmgr->exec_query( $query, $bindings );
    }

    function GetPref($key)
    {
        // return specific requested pref
        if ($this->prefs != Null) {
            if (array_key_exists($key, $this->prefs)) {
                $this->verifyPrefsValues($key, $this->prefs[$key]);
                return $this->prefs[$key];
            }
            return Null;
        } else {
            return Null;
        }
    }

    function SetPref($key, $val)
    {
        $this->validatePref($key, $val);
        if (! $this->SavePrefsInNewColumns($key, $val)) {
            # skip saving in prefs if saved to other column(s).
            $this->prefs[$key] = $val;
            $this->WritePrefs();
        }
    }

    function SetSelectedCourseId($selected_course_id) {
        global $dbmgr;
        $selectQuery = "select id from selections where (user_id = :user_id and class_id = :class_id) limit 1";
        $updateQuery = "update user set selection_id = :selection_id where id = :user_id";

        if ($selected_course_id != Null && $selected_course_id > 0) {
            try {
                $insertQuery = "insert ignore into selections (user_id, class_id) values (:user_id,:class_id)";
                $bindings = array(":user_id" => $this->id, ":class_id" => $selected_course_id);
                $dbmgr->exec_query($insertQuery, $bindings);
                $res = $dbmgr->fetch_assoc( $selectQuery , $bindings );
                if(count($res) == 1) {
                    $selection_id = $res[0]['id'];
                    $bindings = array(":selection_id" => $selection_id, ":user_id" => $this->id);
                    $dbmgr->exec_query($updateQuery, $bindings); 
                    $this->selected_course_id = $selected_course_id;
                    $this->selection_id = $selection_id;
                    $this->LoadSelectedTopics();
                    return True;
                }
            } catch (Exception $e) {
                var_dump($e->getTrace());
            }
        } else {
            
            $bindings = array(":selection_id" => Null, ":user_id" => $this->id);
            $dbmgr->exec_query($updateQuery, $bindings); 
            $this->selected_course_id = Null;
            $this->selection_id = Null;
            $this->LoadSelectedTopics();
            return True;
        }
        error_log(sprintf("UserManager.SetSelectedCourseId ERROR for class_id: %s, user_id: %s", $selected_course_id, $this->id));

        return False;
    }

    function SetPageLoads($page_loads) {
        global $dbmgr;
        $query = "update user set page_loads = :page_loads where id = :user_id";
        $bindings = array(":page_loads" => $page_loads, ":user_id" => $this->id);
        $dbmgr->exec_query($query, $bindings);
        $this->page_loads = $page_loads;
    }

    function SetLastActivity($last_activity) {
        global $dbmgr;
        $query = "update user set last_activity = FROM_UNIXTIME(:last_activity) where id = :user_id";
        $bindings = array(":last_activity" => $last_activity, ":user_id" => $this->id);
        $dbmgr->exec_query($query, $bindings);
        $this->last_activity = $last_activity;
    }

    function ResetSelectedTopicsForClass($class_id) {
        global $dbmgr;
        $query = "delete from selected_topics where selection_id in (select id from selections where user_id = :user_id and class_id = :class_id)";
        $bindings = array(":user_id" => $this->id, ":class_id" => $class_id);
        $dbmgr->exec_query($query, $bindings);
        if ($this->selected_course_id == $class_id) {
            $this->selected_topics = array();
        }
    }

    function AddSelectedTopics($topic_id_list) {
        global $dbmgr;
        $bindings = array(':user_id' => $this->id);
        $bind_string = $dbmgr->BindParamArray("topic", $topic_id_list, $bindings);
        $ensureSelectionsQuery = sprintf("insert ignore into selections (user_id, class_id) select :user_id, class_id from 12m_class_topic where topic_id in (%s)",$bind_string);

        $dbmgr->exec_query($ensureSelectionsQuery, $bindings);

        $insertTopicIdsQuery = "insert ignore into selected_topics (selection_id, topic_id) select t1.id selection_id, t2.topic_id topic_id from selections t1 join 12m_class_topic t2 on t1.class_id=t2.class_id where t2.topic_id in ($bind_string) and t1.user_id=:user_id";
        $dbmgr->exec_query($insertTopicIdsQuery, $bindings);
    }

    function SetSelectedTopicsForClass($class_id, $topic_id_list) {

        $this->ResetSelectedTopicsForClass($class_id);
        $this->AddSelectedTopics($topic_id_list);
    }

    function LoadSelectedTopics() {
        global $dbmgr;
        if ($this->selection_id != Null && $this->selection_id > 0) {
            $query = "select topic_id from selected_topics where selection_id = :selection_id";
            $bindings = array(":selection_id" => $this->selection_id);
            $this->selected_topics_list = $dbmgr->fetch_column($query, $bindings, 0);
        } else {
            $this->selected_topics_list = array();
        }
    }

    // This function persists certain prefs in a new way --
    // either in their own columns in the user table or in 
    // the new selected_topics table. It returns true if 
    // the prefs are saved.   
    function SavePrefsInNewColumns($key, $val) {
        global $dbmgr;
        $value = $val;
        $saved = true;
        if ($value == '') {
            $value = Null;
        }
 
        if ($key == Null) {
            # log error?
        } elseif ($key == 'selected_course') {
           $query = "update user set selected_course_id = :value where id = :user_id";
            $bindings = array(":value" => $value, ":user_id" => $this->id);
            $dbmgr->exec_query($query, $bindings);
            $this->selected_course_id = $value;
            $saved = true;
        } elseif ($key == 'page_loads') {
            $query = "update user set page_loads = :value where id = :user_id";
            $bindings = array(":value" => $value, ":user_id" => $this->id);
            $dbmgr->exec_query($query, $bindings);
            $this->page_loads = $value;
            $saved = true;
        } elseif ($key == 'last_activity') {
            $query = "update user set last_activity = :value where id = :user_id";
            $bindings = array(":value" => date("Y-m-d H:i:s", $value), ":user_id" => $this->id);
            $dbmgr->exec_query($query, $bindings);
            $this->last_activity = $value;
            $saved = true;
        } elseif ($key == 'selected_topics_list') {
            $query = "delete selected_topics from selected_topics,user where selected_topics.selection_id = user.selection_id and user_id = :user_id";
            $bindings = array(':user_id' => $this->id);
            $dbmgr->exec_query($query, $bindings);
            $query = "insert into selected_topics (user_id, selection_id, topic_id) values (:user_id, :selection_id, :topic_id)";
            if (is_array($value)) {
                foreach ($value as $index => $topic_id) {
                    $bindings = array(':user_id' => $this->id, ':selection_id' => $this->selection_id, ':topic_id' => $topic_id);
                    $dbmgr->exec_query($query, $bindings);
                }
            } else {
                $bindings = array(':user_id' => $this->id, ':selection_id' => $this->selection_id, ':topic_id' => $value);
                $dbmgr->exec_query($query, $bindings);
            }
            $saved = true;
        }
    }

    // this function flattens a multi-dimensional array into
    // a single list of values, making it easier to compare
    // two or more arrays.
    function flatten($array) {
        $result = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $result = array_merge($result, $value);
                } else {
                    $result[] = $value;
                }
            }    
        } else {
            $result[] = $array;
        }
        return $result;
    }

    // This function checks whether values from new columns in the 
    // user table and values from the selected_topics table match
    // values in user prefs. If not, it logs an error.  After a few
    // days we will know whether the new columns and the new table
    // can be relied on or need changes.
    function verifyPrefsValues($key, $prefs_val) {
        global $dbmgr;
        if ($key == Null) {
            # log error?
        } elseif ($key == 'selected_course') {
            if ($this->selected_course_id != $prefs_val) {
                error_log(sprintf("ERROR IN PREFS -- selected_course_id (%s) is not same as selected_course in prefs (%s).", $this->selected_course_id, $prefs_val));
            }
        } elseif ($key == 'page_loads') {
            if ($this->page_loads != $prefs_val) {
                error_log(sprintf("ERROR IN PREFS -- page_loads (%s) is not same as page_loads in prefs (%s).", $this->page_loads, $prefs_val));
            }
        } elseif ($key == 'last_activity') {
            if (strtotime($this->last_activity) != $prefs_val) {
                error_log(sprintf("ERROR IN PREFS -- last_activity (%s) is not same as last_activity in prefs (%s).", strtotime($this->last_activity), $prefs_val));
            }
        } elseif ($key == 'selected_topics_list') {
            $query = "select topic_id from selected_topics where user_id = :user_id order by topic_id";
            $bindings = array(':user_id' => $this->id);
            $values = $dbmgr->fetch_num($query, $bindings);

            $values = $this->flatten($values);
            sort($values);

            $prefs_array = $this->flatten($prefs_val);
            sort($prefs_array);

            if ($prefs_array != $values) {
                error_log(sprintf("ERROR IN PREFS -- selected_topics array and selected_topics_list in prefs are not the same for user %s.",$this->id));
                error_log(sprintf("Array from selected_topics_list in prefs: %s", print_r($prefs_array, true)));
                error_log(sprintf("Array from selected_topics table: %s", print_r($values, true)));
            }
        }        
    }

    function validatePref($key, $val)
    {
        if ($key != Null && $key == 'current_problem')
        {
            if ($val != Null && intval($val) < 1)
            {
                error_log("ERROR in SetPref: Invalid value for 'current_problem': {$val}\n");
                $backtrace = '';
                foreach (debug_backtrace() as $key => $value) {
                    $backtrace .= "{$key}: {$value['class']}.{$value['function']} ({$value['file']}  at {$value['line']})\n";
                }
                error_log($backtrace);
            }
        }
    }
}

class UserManager{
    var $m_user = null;

    function __construct(){
        $this->Login();
    }

    function GetAccess(){ return isset($this->m_user->staff); }
    function GetUserId(){ return $this->m_user->username; }

    function Login()
    {
        // set any default (in deveopement this will be the active user_id)        
        //$username = 'test_user8';
        $username = 'jtritz';
        // check if the user just logged in through cosign
        if(isset($_SERVER['REMOTE_USER']))
            $username = $_SERVER["REMOTE_USER"];
        $this->m_user = new MUser($username);
    }
}

?>
