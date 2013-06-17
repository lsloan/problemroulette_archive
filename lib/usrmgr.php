<?php

class CLoginWsso{
	var $m_direct;

	function CLoginWsso($direct){
		$this->m_direct = $direct;
	}
	function Deliver(){
		return "
<form method='GET' action=''
	enctype='application/x-www-form-urlencoded' name='wssolite'>
	<input type='hidden' name='LogonSuccessURL' value='".$this->m_direct."'>
	<input type='hidden' name='LogonAppID' value='roadmapapplication'>
	<input type='hidden' name='ApplicationParam' value=''>
	<input type='submit' name='wssoLogin' value='Login'>
</form>
		";
	}
}

class CUser{
	// this is an object because concevably we would want to store more than just an id...
	var $m_id;

	function CUser($id){
		// fill out the user info - not much right now :)
		$this->m_id = $id;
	}
}

class CUserManager{
    var $m_user = null;
    var $m_valid = array(
	"694654", 	// jared
	"781631", 	// rebecca
	"782435", 	// sarah
    "785412", 	// aaron
    "1342", 	// jagdish
    "1707",	    // frank
	);  // HACK-ALERT this goes away

    function CUserManager($args){

		// decide if the user is trying to log out and if they are, then of course ablige them.
        if(isset($args["mylogout"]))
		{
			$this->Logout();
		}
		// if the user has not tried to logout then maybe they already have a session?
        else if(isset($_SESSION["USER"]))
        {
        	$this->m_user = $_SESSION["USER"];
        }
        // if the user is not trying to logout and they don't have a session then decide if they are trying to log in and if so of course ablidge them.
        else if(isset($args["LogonCallBackID"]))
		{
			// this is a one shot deal i guess, you get the callbackid and use it once!
		}
		else if(isset($args["myid"]))
		{
			$this->Login($args["myid"]);
		}
    }
    function GetAccess(){ return isset($this->m_user); }
	function GetId(){ return $this->m_user->m_id; }
	// private
	function Login($id){
		// EVERYONE GETS TO LOGIN!
		//$_SESSION["USER"] = $this->m_user = new CUser($id);

		// HACK-ALERT look up user
		$exists = in_array($id, $this->m_valid); // HACK-ALERT set from database
		if($exists)
		{
			$_SESSION["USER"] = $this->m_user = new CUser($id);
		}
		else if(strlen($id) != 7)
		{
			echo "<br> login default?";
		}
	}
	function Logout(){
		unset($_SESSION["USER"]);
	}
}

?>
