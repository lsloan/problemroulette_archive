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
    function CDbMgr()
	{
		//	Save the database variables.
		$this->m_host = "localhost";
		$this->m_user = "pr_user";
		$this->m_pswd = "pr_user";
		$this->m_db   = "pr_expansion";
		$this->m_link = false;

		//	Connect to the database
        $lnk = new mysqli( $this->m_host, $this->m_user, $this->m_pswd, $this->m_db );
        if ($lnk->connect_errno) {
            echo "Failed to connect to MySQL: (" . $lnk->connect_errno . ") " . $lnk->connect_error;
        }
        else
        {
            $this->m_link = $lnk;
            #echo $lnk->host_info . "\n";
        }
	}

	//	Primitives
	function exec_query( $query )
	{
        $result = $this->m_link->query($query);
		if (!$result)
		{
            echo "query failed: " .$query. "(" . $this->m_link->errno . ") " . $this->m_link->error;
		}
		return $result;
	}
    function fetch_num( $query )
    {
        $res = $this->exec_query($query);
        $results = array();
        while($value = $res->fetch_row())
        {
            array_push($results, $value);
        }
        return $results;
    }
    function fetch_assoc( $query )
    {
        $res = $this->exec_query($query);
        $results = array();
        while($value = $res->fetch_assoc())
        {
            array_push($results, $value);
        }
        return $results;
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
