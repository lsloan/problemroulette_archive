<?php
// This is an example migration, showing the two portions of the base class
// that will typically be overridden: init() and migrate(). It also shows
// the capture and logging of some output at the INFO level.
//
// Be sure to register the class name and filename in upgrade.php when
// implementing real migrations; otherwise, they will not be run.
//
// The Migration class and the usual utilities will already be loaded, so
// boilerplate require statements are not required. The database manager will
// be available as $this->db.
//
// The default init() and migrate() implementations do nothing, so there is no
// need to call the super class.
//
// The init() method is called during the constructor, and should be used to set
// up any instance variables needed.  A common pattern here is to set up
// parameterized queries as heredocs (which do not work as static members).
//
// The migrate() method represents the bulk of the migration operation. Any work
// to be done should be called during this method. Any output should be done
// through the logging methods (e.g., info(), error()).

class Example extends Migration {

    function init() {
$this->setup =<<<SQL
CREATE TABLE test (
    id INT NOT NULL auto_increment PRIMARY KEY,
    name VARCHAR(100)
)
SQL;

$this->newval =<<<SQL
INSERT INTO test VALUES(NULL, 'foo')
SQL;

$this->select =<<<SQL
SELECT id, name FROM test
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->setup);
        $this->db->exec_query($this->newval);
        $res = $this->db->fetch_assoc($this->select);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

