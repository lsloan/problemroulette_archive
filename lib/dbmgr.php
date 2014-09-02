<?php
////////////////////////////////////////////////////////////////////////////////
//	dbmgr.php
//------------------------------------------------------------------------------
//	Database Management Module
//
//

if (!extension_loaded('pdo')) {
	dl('pdo.so');
}

if (!extension_loaded('pdo_mysql')) {
	dl('pdo_mysql.so');
}

class CDbMgr
{
	//	Members
	var $m_host;
	var $m_user;
	var $m_pswd;
	var $m_db;
	var $m_link;
	
	//	Constructor
    function CDbMgr()
	{
		//	Save the database variables.
		$this->m_host = $GLOBALS["SQL_SERVER"];
		$this->m_user = $GLOBALS["SQL_USER"];
		$this->m_pswd = $GLOBALS["SQL_PASSWORD"];
		$this->m_db   = $GLOBALS["SQL_DATABASE"];
		$this->m_link = false;

		//	Connect to the database
		$lnk = new PDO( "mysql:dbname={$this->m_db};host={$this->m_host}", $this->m_user, $this->m_pswd  );
		if ($lnk->errorCode())
		{
			echo "Failed to connect to MySQL: (" . $lnk->errorCode() . ") " . print_r($lnk->errorInfo(),true);
		}
		else
		{
			$this->m_link = $lnk;
			#echo $lnk->host_info . "\n";
		}
	}

	// call user_func_array
	function prepare()
	{
		return call_user_func_array(array($this->m_link,"prepare"), func_get_args());
	}

	//	Primitives
	function exec_query( $query, $bindings = array() )
	{
		$result = $this->m_link->prepare($query);
		if (!$result)
		{
			echo "query failed: " .$query. "(" . $this->m_link->errno . ") " . $this->m_link->error;
		}
		$result->execute($bindings);
		return $result;
	}

	function fetch_num( $query , $bindings = array() )
	{
		$res = $this->exec_query($query, $bindings);
		return $res->fetchAll(PDO::FETCH_NUM);
	}

	function fetch_assoc( $query, $bindings = array() )
	{
		$res = $this->exec_query($query,$bindings);
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}

	function fetch_column( $query, $bindings = array(), $column = 0 )
	{
		$res = $this->exec_query($query, $bindings);
		return $res->fetchAll(PDO::FETCH_COLUMN, $column);
	}

	function db_addslashes( $x ) { return addslashes( $x ); }
	function db_stripslashes( $x ) { return stripslashes( $x ); }

	//	Functions
	function Close() { $this->m_link->close(); }
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

	//	Accessors
	function GetHost() { return $this->m_host; }
	function GetUser() { return $this->m_user; }
	function GetPswd() { return $this->m_pswd; }
	function GetDB()   { return $this->m_db; }
	function GetLink() { return $this->m_link; }
}
?>
