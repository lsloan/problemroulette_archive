<?php

class CLogout{

	function CLogout(){}

	function Deliver(){
		global $usrmgr;
		return "
<form method=\"POST\">Welcome: ".$usrmgr->m_user->m_id."&nbsp;
<input type=\"button\" value=\"logout\" onclick=\"document.getElementById('mylogout').value='logout'; this.form.submit()\"></input>
<br>
<input type=\"hidden\" name=\"mylogout\" id=\"mylogout\" value=\"none\" ></input>
</form>
		";
	}
}
class CLoginWsso{
	var $m_direct;

	function CLoginWsso($direct){
		$this->m_direct = $direct;
	}
	function Deliver(){
		return "
<form method='GET' action='http://wsso-preprod.ca.boeing.com:7611/WSSOLITE/WssoLoginServlet'
	enctype='application/x-www-form-urlencoded' name='wssolite'>
	<input type='hidden' name='LogonSuccessURL' value='".$this->m_direct."'>
	<input type='hidden' name='LogonAppID' value='roadmapapplication'>
	<input type='hidden' name='ApplicationParam' value=''>
	<input type='submit' name='wssoLogin' value='Login'>
</form>
		";
	}
}
class CLogin{

    function CLogin(){

    }

    function Deliver(){
        // Two form variables are used.  password varible gets you in, mylogout variable gets you out.
        // mylogout variable only gets set to log out when you click logout.  password variable alwasy gets submitted.
        // entering a password and hitting logout does log you out.
        $str = "
<form method=\"POST\">
<br><br><br><br><br><br>
<br><br><br><br><br><br>
<table>
	<tr>
		<td>
login <input type=\"password\" name=\"myid\" id=\"myid\"></input>
		</td>
	</tr>
</table>
</form>
<hr>
<b>Note:</b> To test the labs appliction = '3'.
        ";
        return $str;
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
	"1694654", 	// jared
	"1781631", 	// rebecca
	"1782435", 	// sarah
    "1785412", 	// aaron
    "81342", 	// jagdish
    "31707",	// frank
    "6749", 	// mike l.

    "99325"		, //	ALLDREDGE JOEL DAVID
	"6402"		, //	ANTOS THOMAS
	"44450"		, //	BAKER RICHARD EUGENE
	"20964"		, //	BAXTER MATTHEW CHARLES
	"114202"	, //	BOSLEY GARY STEVEN
	"382922"	, //	CADWELL STANCIN LINDA ANN
	"124089"	, //	CHAKRABARTY JAMIE HOFFNAUER
	"101324"	, //	CHAN MARTIN KWAN WAH
	"354286"	, //	CHAN MICHAEL KWAN PUI
	"295054"	, //	CHONG DIANNE
	"148001"	, //	CLARK E CHARLES
	"136073"	, //	ELIASON MARGIE ARLENE
	"90212"		, //	FARANGE HOSSEIN
	"1649"		, //	FAY CHRISTOPHER WAYNE
	"106574"	, //	FENSTERMAKER ALAN THOMAS
	"81936"		, //	FISCHER-BENZON THOMAS VON
	"123320"	, //	GLICKSBERG SUSAN JEANNETTE
	"81433"		, //	GOODWIN KENNETH JOHN
	"42748"		, //	GRANT BRUCE DUANE
	"310394"	, //	GREENBERG CRAIG
	"32561"		, //	GROSS LARRY EARL
	"114673"	, //	HARRINGTON THOMAS ALAN
	"120232"	, //	HARRIS WALTER JEFFERSON
	"21978"		, //	HEALAS GEORGE WILLIAM
	"157935"	, //	HEFTI, LARRY D
	"198430"	, //	HENRY MARK LAWRENCE
	"82463"		, //	HERGERT KATHRYN SUE
	"114009"	, //	HIXSON WAYNE ELLORY
	"137083"	, //	HOLM DAVID BRUCE
	"103859"	, //	HUDSON JADE JIH-JU
	"139122"	, //	HUTCHINSON TERESA MARIE
	"43544"		, //	JOHNSON SONJA PREMACK
	"26414"		, //	KUNST KARL WALTER
	"48204"		, //	LALIBERTE BRIAN ALLEN
	"2192"		, //	LARIVIERE STEPHEN GREGORY
	"76359"		, //	LAWRENCE ROGER SCOTT
	"87093"		, //	LELAND TERRY DOUGLAS
	"802325"	, //	LONGCORE JEFF RAY MAHER
	"76477"		, //	LUND BRAD GARY
	"81851"		, //	MACLEAN BARBARA LORD
	"128200"	, //	MARSH JERRY LOU
	"74124"		, //	MCCORMICK CRAIG ALAN
	"27323"		, //	MCCORMICK MATTHEW GLENN
	"10575"		, //	MCGINNIS NANCY JEAN
	"7542"		, //	MICALE ANTONIO CARLO
	"128668"	, //	MORELAND, CRAIG
	"114792"	, //	MOTTAZ DONALD ARTHUR
	"341098"	, //	MOUNTAIN WILLIAM
	"152118"	, //	NAMDARAN AZITA NIKFAR
	"1074642"	, //	NGUYEN QUANG DUC
	"113479"	, //	PAIGE JAMES ROBERT
	"114275"	, //	PANG AUDREY MEI-QUE
	"103007"	, //	PENNELL ALAN JOSEPH
	"18312"		, //	PETERSON JAMES MACON
	"148783"	, //	PETERSON JAMES WHERRY
	"116190"	, //	PETTIT SCOTT ELLIOTT
	"48221"		, //	REDDY MAHENDER CHERKUPALLI
	"115553"	, //	RUSSELL JONATHAN PAUL
	"135069"	, //	SANDERS DANIEL GORDON
	"81706"		, //	SMITH BRIAN WHITNEY
	"132867"	, //	TREECE DENNIS CECIL
	"87580"		, //	URE, SUZI
	"26867"		, //	WANG SERAPHINE NOAH
	"112325"	, //	WINGERT A LEW
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
			$theid = $args["LogonCallBackID"];
			$file = fopen (("http://wsso-preprod.ca.boeing.com:7611/WSSOLITE/WssoCallBack?LogonCallBackID=".$theid), "r");
			if (false && !$file) {
			    echo "<p>Unable to open remote file.\n";
			    //exit;
			}
			$lines = array();
			while (!feof ($file)) {
			    $lines[] = fgets ($file, 1024);
			}
			fclose($file);
			//print_r($lines);
			$msg = explode("|", $lines[0]); // seems crude...but i guess that's how it works!?!
			//print_r($msg);
			if(trim($msg[0]) == "0"){
				$bems = trim($msg[1]);
				$this->Login($bems);
			}
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
		$_SESSION["USER"] = $this->m_user = new CUser($id);
		/*
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
		*/
	}
	function Logout(){
		unset($_SESSION["USER"]);
	}
}

?>