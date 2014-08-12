<?php

class MUser
{
    var $id;
    var $username;
    var $prefs = null;
    var $staff = 0;

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
        $query = "INSERT INTO user(username, staff, prefs) VALUES('".$this->username."', ".$this->staff.", '" .$this->package(Array()). "')";
		$dbmgr->exec_query($query);
        get_id();
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
        $res = $dbmgr->fetch_assoc("SELECT id FROM user WHERE username=:username",array(':username'=>$username));
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

        $query = "SELECT id, staff, prefs FROM user where username='".$this->username."'";
        $res = $dbmgr->fetch_assoc($query);
        // populate user (if found)
        if(count($res) == 1)
        {
            $this->id = $res[0]['id'];
            $this->staff = $res[0]['staff'];
            $this->prefs = $this->unpackage($res[0]['prefs']);
            return True;
        }
        return False;

    }
 
    function WritePrefs()
    {   // write all prefs back to user table
        global $dbmgr;
        $query = "UPDATE user set prefs='".$this->package($this->prefs). "' where username='".$this->username."'";
		$dbmgr->exec_query($query);
    }

    function GetPref($key)
    {
        // return specific requested pref
		if ($this->prefs != Null)
			{
			if (array_key_exists($key, $this->prefs))
				return $this->prefs[$key];
			else
				return Null;
			}
		else
		{
			return Null;
		}
    }

    function SetPref($key, $val)
    {
        $this->validatePref($key, $val);
        $this->prefs[$key] = $val;
        $this->WritePrefs();
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
