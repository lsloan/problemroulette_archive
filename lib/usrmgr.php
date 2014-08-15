<?php

class MUser
{
    var $id;
    var $username;
    var $prefs = null;
    var $staff = 0;
    var $selected_course_id;
    var $page_loads;
    var $last_activity;

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
		global $dbmgr;
		$query =
			"INSERT INTO user(username, staff, prefs) ".
			"VALUES ( :username, :staff, :prefs )";
		$bindings = array(
			":username" => $this->username,
			":staff"    => $this->staff,
			":prefs"    => $this->package(Array()));
		$dbmgr->exec_query( $query , $bindings );
        $this->get_id();
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
		$query = "SELECT id FROM user WHERE username = :username";
		$bindings = array(':username' => $username);
		$res = $dbmgr->fetch_assoc( $query , $bindings );
		// populate user (if found)
		if(count($res) == 1)
		{
			$this->id = $res[0]['id'];
			return True;
		}
		return False;
	}
 
    function read()
    {
        global $dbmgr;
        $query = "SELECT id, staff, prefs, selected_course_id, page_loads, last_activity FROM user WHERE username = :username";
        $bindings = array(":username" => $this->username);
        $res = $dbmgr->fetch_assoc( $query , $bindings );
        // populate user (if found)
        if(count($res) == 1)
        {
            $this->id = $res[0]['id'];
            $this->staff = $res[0]['staff'];
            $this->prefs = $this->unpackage($res[0]['prefs']);
            $this->selected_course_id = $res[0]['selected_course_id'];
            $this->page_loads = $res[0]['page_loads'];
            $this->last_activity = $res[0]['last_activity'];

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
        $saved = $this->SavePrefsInNewColumns($key, $val);
        # later to stop saving values in both ways we can  
        # skip the following steps if $saved is true.
        $this->prefs[$key] = $val;
        $this->WritePrefs();
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
            $bindings = array(":value" => $value, ":user_id" => $this->id);
            $dbmgr->exec_query($query, $bindings);
            $this->last_activity = $value;
            $saved = true;
        } elseif ($key == 'selected_topics_list') {
            $query = "insert into selected_topics (user_id, topic_id) values (:user_id, :topic_id)";
            if (is_array($value)) {
                foreach ($value as $index => $topic_id) {
                    $bindings = array(':user_id' => $this->id, ':topic_id' => $topic_id);
                    $dbmgr->exec_query($query, $bindings);
                }
            } else {
                $bindings = array(':user_id' => $this->id, ':topic_id' => $value);
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
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $value);
            } else {
                $result[] = $value;
            }
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
            if ($this->last_activity != $prefs_val) {
                error_log(sprintf("ERROR IN PREFS -- last_activity (%s) is not same as last_activity in prefs (%s).", $this->last_activity, $prefs_val));
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
