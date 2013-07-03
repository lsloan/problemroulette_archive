<?php

class UserManager{
    var $user_id = null;
    var $prefs = null;

    function __construct(){
        $this->Login();
        #$this->ReadPrefs();
    }

    function GetAccess(){ return isset($this->user_id); }
	function GetUserId(){ return $this->user_id; }

	function Login()
    {
        $this->user_id = 'None';
        // check if the user just logged in through cosign
        if(isset($_SERVER['REMOTE_USER']))
		{
        	$this->user_id = $_SERVER["REMOTE_USER"];
		}
	}
    
    function ReadPrefs()
    {   // read all prefs from user table
       
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


?>
