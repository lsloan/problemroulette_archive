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
	var $queries = 0;
	var $traces = array();
	
	//	Constructor
  function CDbMgr()
	{
		//	Save the database variables.
		$this->m_host = $GLOBALS["SQL_SERVER"];
		$this->m_user = $GLOBALS["SQL_USER"];
		$this->m_pswd = $GLOBALS["SQL_PASSWORD"];
		$this->m_db   = $GLOBALS["SQL_DATABASE"];
		$this->m_port = $GLOBALS["SQL_PORT"];
		$this->m_link = false;

		//	Connect to the database
		$lnk = new PDO( "mysql:dbname={$this->m_db};host={$this->m_host};port={$this->m_port}", $this->m_user, $this->m_pswd  );
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
	function exec_query( $query, $bindings = null )
	{
		try
		{
			$prepared_statement = $this->m_link->prepare($query);
			if ($prepared_statement)
			{
				// success! keep going
			}
			else
			{
				$this->log_error("CDbMgr->exec_query() Call to PDO prepare failed: " .$query. "\nps->errorCode: " . $prepared_statement->errorCode() . "\nps->errorInfo: " . print_r($prepared_statement->errorInfo(), true));

				# $this->log_error("CDbMgr->exec_query() Call to PDO prepare failed");
			}
		}
		catch(PDOException $e)
		{
			$this->log_error("CDbMgr->exec_query() PDOException thrown preparing statement", $e);
		}

		try
		{
			$res = $prepared_statement->execute($bindings);
			if ($res && ($prepared_statement->errorCode() == '00000'))
			{
				// success! keep going
			}
			elseif ($prepared_statement->errorCode() == '23000')
			{
				// uniqueness constraint violation -- log a backtrace!
				$this->log_error("CDbMgr->exec_query() Call to PDO execute failed\n   query: ".$query."\n    bindings:\n".print_r($bindings, true). "\nps->errorCode: " . $prepared_statement->errorCode() . "\nps->errorInfo: " . print_r($prepared_statement->errorInfo(), true)."\nback_trace:\n".print_r(debug_backtrace(), true));
			}
			else
			{
				$this->log_error("CDbMgr->exec_query() Call to PDO execute failed\n   query: ".$query."\n    bindings:\n".print_r($bindings, true). "\nps->errorCode: " . $prepared_statement->errorCode() . "\nps->errorInfo: " . print_r($prepared_statement->errorInfo(), true));
			}
		}
		catch(PDOException $e)
		{
			$this->log_error("CDbMgr->exec_query() PDOException thrown executing statement", $e);
		}

		return $prepared_statement;
	}

	function fetch_num( $query , $bindings = null )
	{
		$res = $this->exec_query($query, $bindings);
		return $res->fetchAll(PDO::FETCH_NUM);
	}

	function fetch_assoc( $query, $bindings = null )
	{
		if ($GLOBALS['DEBUG']) {
			$this->queries += 1;
			$bt = debug_backtrace();
			$bt = $bt[1];
			$where = (isset($bt['class']) ? $bt['class'] . "::" : "") . $bt['function'] . " - " . $bt['file'] . ":" . $bt['line'];
			if (!isset($this->traces[$where])) {
				$this->traces[$where] = 0;
			}
			$this->traces[$where] += 1;
		}
		$res = $this->exec_query($query,$bindings);
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}

	function fetch_column( $query, $bindings = null, $column = 0 )
	{
		$res = $this->exec_query($query, $bindings);
		return $res->fetchAll(PDO::FETCH_COLUMN, $column);
	}

	function handle_insert($insert_query, $bindings = null) {
		$this->m_link->beginTransaction();
		$this->exec_query($insert_query, $bindings);
		$last_id = $this->fetch_assoc('SELECT LAST_INSERT_ID() last_id');
		$this->m_link->commit();
		return intval($last_id[0]['last_id']);
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

	// PDO does not support binding arrays as parameters
	// like you might want to do with an "in" clause, 
	// so here is a "BindParamArray" function, based on: 
	// http://stackoverflow.com/a/22663617/1786958
	// (with some modifications).
	// Example usage:
	//   $bindString = helper::bindParamArray("id", array(3,6,9), $bindArray);
	//   $userConditions .= " AND users.id IN($bindString)";
	// That returns a string ":id1, :id2, :id3" and also updates 
	// $bindArray with ":id1" => 3, ":id2" => 6, ":id3" => 9
	function BindParamArray($prefix, $values, &$bindArray){
    $str = "";
    if (is_scalar($values)) {
    	$values = array($values);
    }
    foreach($values as $index => $value){
    	$name = ":".$prefix.$index;
    	if ($index == 0) {
    		$str .= $name;
    	} else {
    		$str .= ", ".$name;
    	}
      $bindArray[$name] = $value;
    }
    return $str;     
	}

	function dump_stats_table($tablename, $filepath)
	{
		$cmd = 'mysqldump --user='.$this->m_user.' --password='.$this->m_pswd.' --host='.$this->m_host.' '.$this->m_db.' '.$tablename.' > '.$filepath;
		exec($cmd);
	}

	function dump_csv_file($tablename, $filepath, $column_names = null)
	{
		$handle = fopen($filepath, 'x');
		if(isset($column_names)) {
			fputcsv($handle, $column_names);
		}
		$query = "select * from ".$tablename;
		$res = $this->fetch_num($query, array());

		foreach ($res as $key => $value) { 
			fputcsv($handle, $value);
		}
		fclose($handle);
	}

	function log_error($message, $exception = null)
	{
		global $app_log;
		$app_log->msg($message);
		$app_log->msg("  errorCode: ".$this->m_link->errorCode());
		$app_log->msg("  errorInfo: \n".print_r($this->m_link->errorInfo(), true));
		if ($exception)
		{
			$app_log->msg("  Exception: \n".print_r($e, true));
			$app_log->msg("  backtrace: \n".print_r(debug_backtrace(), true));
		}
	}

}
?>
