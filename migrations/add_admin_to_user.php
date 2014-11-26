<?php

class AddAdminToUser extends Migration {

    function init() {
$this->add_admin =<<<SQL
alter table user add column `admin` int(1) NOT NULL default 0
SQL;

$this->verify =<<<SQL
show columns from user
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_admin);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>