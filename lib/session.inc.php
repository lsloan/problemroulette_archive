<?php
////////////////////////////////////////////////////////////////////////////////
//	sessmgr.inc.php
//------------------------------------------------------------------------------
//	Session Management Module
//
//	Database:
//		<session_table> = session table name
//		CREATE TABLE <session_table>
//			(
//			id     			varchar(255) not null,
//			last_update		datetime     not null,
//			data_value		longtext,
//			PRIMARY KEY ( id ),
//			INDEX ( last_update )
//			)
/*
 CREATE TABLE session_table (
id varchar( 255 ) NOT NULL ,
last_update datetime NOT NULL ,
data_value longtext,
PRIMARY KEY ( id ) ,
INDEX ( last_update )
)
*/
//------------------------------------------------------------------------------
//

//	Session Manager


class CSessMgr
{
	//	Members
	var $m_table;
	var $m_life;


	//	Accessors
	function GetTable() { return $this->m_table; }
	function GetLife()  { return $this->m_life; }

	//	Session Callback Functions
	function sess_open( $aSavaPath, $aSessionName )
	{
		$this->sess_gc( $this->m_life );
		return true;
	}
	function sess_close()
	{
		return true;
	}
	function sess_read( $aKey )
	{
		global $dbmgr;

		$res = $dbmgr->db_query( "SELECT data_value FROM " . $this->m_table . "
		WHERE id='$aKey'" );
		if( $dbmgr->db_num_rows( $res ) == 1 )
		{
			$r = $dbmgr->db_fetch_array( $res, MYSQL_ASSOC );
			return $r['data_value'];
		}
		else
		{
			$dbmgr->db_query(
				"INSERT INTO " . $this->m_table . "
				(id, last_update, data_value) VALUES ('$aKey', NOW(), '')" );
			return "";
		}
	}
	//	Write session data
	function sess_write( $aKey, $aVal )
	{
		// HACK ALERT since php 5 I guess the objects are destroyed before the session
		// stuff is finished.  So thus we must create a new object here to finish session work!
		$tmpDbmgr = new CDbMgr( $GLOBALS["SQL_SERVER"], "roadmap", "r05dm5p", "roadmaps2" );
		$aVal = $tmpDbmgr->db_addslashes( $aVal );
		$tmpDbmgr->db_query(
			"UPDATE ". $this->m_table . " SET data_value = '$aVal', last_update =
			NOW() WHERE id = '$aKey'" );
		unset($tmpDbmgr); // complete the hack :(
		return true;
	}
	//	Destroy session data
	function sess_destroy( $aKey )
	{
		global $dbmgr;

		$dbmgr->db_query( "DELETE FROM " . $this->m_table . " WHERE id = '$aKey'" );
		return true;
	}
	//	Garbage collect session data
	function sess_gc( $aLife )
	{
		global $dbmgr;

		$dbmgr->db_query(
			"DELETE FROM " . $this->m_table . " WHERE (UNIX_TIMESTAMP(NOW()) -
			UNIX_TIMESTAMP(last_update)) > $aLife" );
		return true;
	}

	//	Constructor
	function CSessMgr( $table, $life )
	{
		$this->m_table = $table;
		$this->m_life = $life;

		//	Set the session handlers
		session_set_save_handler(
			array( & $this, "sess_open" ),
			array( & $this, "sess_close" ),
			array( & $this, "sess_read" ),
			array( & $this, "sess_write" ),
			array( & $this, "sess_destroy" ),
			array( & $this, "sess_gc" ) );

		//	Start the session
		session_start();
	}
}

?>
