<?php

class AddAdminToUser extends Migration {

    function init() {
$this->add_admin =<<<SQL
alter table user add column `admin` int(1) NOT NULL default 0
SQL;

$this->add_values =<<<SQL
update user set admin=1 where username='jleasia' or username='botimer' or username='ericeche' or username='jimeng'
SQL;

$this->verify =<<<SQL
SELECT count(*) FROM user where admin=1
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_admin);
        $this->db->exec_query($this->add_values);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>