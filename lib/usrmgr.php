<?php

class UserManager{
    var $user_id = null;
    var $prefs = null;

    function __construct(){
        $this->Login();
        $this->ReadPrefs();
    }

    function GetAccess(){ return isset($this->user_id); }
	function GetUserId(){ return $this->user_id; }

	function Login()
    {
        // check if the user just logged in through cosign
        if(isset($_SERVER['REMOTE_USER']))
		{
        	$this->user_id = $_SERVER["REMOTE_USER"];
		}
        // else redirect... but cosign may be (undesireably?) configured to disallow getting here...
        else
        { 
            // rediret to login
            $url = "Location:".$GLOBALS["DOMAIN"]."login.php";
            header($url);
        }
	}
    
    function Fake_Login($user_id)
    {   // FAKE LOGIN IN DEVELOPMENT MODE AND RETURN
        $this->user_id = $user_id;
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
