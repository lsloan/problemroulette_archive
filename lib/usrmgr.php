<?php

class MUser
{
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
        $query = "INSERT INTO user(username,staff) VALUES('".$this->username."', ".$this->staff.")";
		$dbmgr->exec_query($query);
    }
   
    function read()
    {
        global $dbmgr; 

        //$query = "SELECT username, staff, prefs FROM user";
        $query = "SELECT username, staff FROM user";
        $res = $dbmgr->fetch_assoc($query);
        // populate user (if found)
        if(count($res) == 1)
        {
            $this->staff = $res['staff'];
            //$this->prefs = $this->ReadPrefs($res['prefs']);
            return True;
        }
        return False;

    }
 
    function ReadPrefs()
    {   // read all prefs from user table
        global $dbmgr; 
         
        // unserialize the dict of prefs
        $this->prefs = array(); 
    }

    function WritePrefs()
    {   // write all prefs back to user table
        // serialize the dict of prefs

        // persist to user table
    }

    function GetPref($key)
    {
        // return specific requested pref
        return $this->prefs[$key];
    }

    function SetPref($key, $val)
    {
        $this->prefs[$key] = $val;
        $this->WritePrefs();
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
        $username = 'test_user';
        // check if the user just logged in through cosign
        if(isset($_SERVER['REMOTE_USER']))
            $username = $_SERVER["REMOTE_USER"];
        $this->m_user = new MUser($username);
	}
}


?>
