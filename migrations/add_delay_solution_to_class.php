<?php

class AddDelaySolutionToClass extends Migration {

    function init() {
$this->add_delay_solution =<<<SQL
alter table class add column `delay_solution` int(2) NOT NULL default 0
SQL;

$this->verify =<<<SQL
show columns from class
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_delay_solution);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>