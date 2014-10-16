<?php
class Bar extends Migration {

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

