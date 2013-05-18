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
		if( !$lnk = mysql_connect( $this->m_host, $this->m_user, $this->m_pswd ) )
		{
			trigger_error( 'dbmgr: Failed to connect to MySql - '. mysql_error(),
				E_USER_ERROR );
			return false;
		}
		if( !mysql_select_db( $this->m_db, $lnk ) )
		{
			trigger_error( 'dbmgr: Unable to select database $aDatabase - '. mysql_error(),
				E_USER_ERROR );
			return false;
		}
		$this->m_link = $lnk;
	}



	//	Primitives
	function db_query( $x )
	{
		$result = mysql_query( $x, $this->m_link );
		if ( !$result )
		{
			$str = "<br/>DB query error: <br/>Query string: " . $x . "<br/>Returned message: " . mysql_error() . "<br/>";
			// make sure all the tables are unlocked
			$this->Unlock();
			die ( $str );
		}
		return $result;
	}
	function db_errno() { return mysql_errno( $this->m_link ); }
	function db_error() { return mysql_error( $this->m_link ); }
	function db_fetch_array( $x, $y ) { return mysql_fetch_array( $x, $y ); }
	function db_num_rows( $x ) { return mysql_num_rows( $x ); }
	function db_insert_id() { return mysql_insert_id( $this->m_link ); }
	function db_addslashes( $x ) { return addslashes( $x ); }
	function db_stripslashes( $x ) { return stripslashes( $x ); }
	function db_free_result( $x ) { return mysql_free_result( $x ); }
	function db_affected_rows() { return mysql_affected_rows( $this->m_link ); }

	//	Functions
	function Close() { mysql_close( $this->m_link ); }
	function StartTransaction() { return $this->db_query( "START TRANSACTION" ); }
	function Commit() { return $this->db_query( "COMMIT" ); }
	function Rollback() { return $this->db_query( "ROLLBACK" ); }
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
		return $this->db_query( $sql );
	}
	function Unlock() { return $this->db_query( "UNLOCK TABLES" ); }
	function GetServerHost() { return mysql_get_server_info( $this->m_link ); }

	//	Accessors
	function GetHost() { return $this->m_host; }
	function GetUser() { return $this->m_user; }
	function GetPswd() { return $this->m_pswd; }
	function GetDB()   { return $this->m_db; }
	function GetLink() { return $this->m_link; }
}
?>
