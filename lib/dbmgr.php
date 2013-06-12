<?php
////////////////////////////////////////////////////////////////////////////////
//	dbmgr.php
//------------------------------------------------------------------------------
//	Database Management Module
//
//

class CDbMgr
{
	//	Members
	var $m_host;
	var $m_user;
	var $m_pswd;
	var $m_db;
	var $m_link;

	//	Constructor
    function CDbMgr( $host, $user, $pswd, $db )
	{
		//	Save the database variables.
		$this->m_host = $host;
		$this->m_user = $user;
		$this->m_pswd = $pswd;
		$this->m_db   = $db;
		$this->m_link = false;

		//	Connect to the database
		$lnk =   new mysqli( $this->m_host, $this->m_user, $this->m_pswd, $this->m_db );
		if ($lnk->connect_errno) 
        {
			trigger_error("Failed to connect to MySQL: (' . $mysqli->connect_errno . ') " . $mysqliCreate->connect_error);
			return false;
		}
		if( !mysqli_select_db( $lnk, $this->m_db ) )
		{
			trigger_error( 'dbmgr: Unable to select database $aDatabase - '. mysqli_error(),
				E_USER_ERROR );
			return false;
		}
		$this->m_link = $lnk;
	}

	//	Primitives
	function exec_query( $x )
	{
		$result = $this->m_link->query($x);
		if ( !$result )
		{
			$str = "<br/>DB query error: <br/>Query string: " . $x . "<br/>Returned message: " . mysqli_error() . "<br/>";
			// make sure all the tables are unlocked
			$this->Unlock();
			die ( $str );
		}
		return $result;
	}
    function fetch_num( $query )
    {
		$res = $this->m_link->query($query);
        # return $res->fetch_all(MYSQL_NUM);
        $results = array();
        foreach ($res as $value) {
        	array_push($results, $value);
        }
		return $results;
    }
    function fetch_assoc( $query )
    {
		$res = $this->m_link->query($query);
        # return $res->fetch_all(MYSQL_ASSOC);
        $results = array();
        foreach ($res as $value) {
        	array_push($results, $value);
        }
		return $results;
    }
	function db_num_rows( $x ) { return mysqli_stmt_num_rows( $x ); }
	function db_addslashes( $x ) { return addslashes( $x ); }
	function db_stripslashes( $x ) { return stripslashes( $x ); }

	//	Functions
	function Close() { mysqli_close( $this->m_link ); }
	function StartTransaction() { return $this->exec_query( "START TRANSACTION" ); }
	function Commit() { return $this->exec_query( "COMMIT" ); }
	function Rollback() { return $this->exec_query( "ROLLBACK" ); }
	function Lock( $tbls )
	{
		$sql = "LOCK TABLES ";

		for( $i = 0; $i < count( $tbls ); $i++ )
		{
			$sql .= $tbls[$i] . " WRITE";
			// using WRITE privilages
			if( $i != (count( $tbls ) - 1) )
				// all but the last
				$sql .= ", ";
		}
		return $this->exec_query( $sql );
	}
	function Unlock() { return $this->exec_query( "UNLOCK TABLES" ); }
	function GetServerHost() { return mysqli_get_server_info( $this->m_link ); }

	//	Accessors
	function GetHost() { return $this->m_host; }
	function GetUser() { return $this->m_user; }
	function GetPswd() { return $this->m_pswd; }
	function GetDB()   { return $this->m_db; }
	function GetLink() { return $this->m_link; }
}
?>
