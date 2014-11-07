<?php

class AddResearcherToUser extends Migration {

    function init() {
$this->add_researcher =<<<SQL
alter table user add column `researcher` int(1) NOT NULL default 0
SQL;

$this->add_values =<<<SQL
update user set researcher=1 where username='evrard' or username='meliwu' or username='jleasia' or username='botimer' or username='ericeche' or username='jimeng'
SQL;

$this->verify =<<<SQL
SELECT count(*) FROM user where researcher=1
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_researcher);
        $this->db->exec_query($this->add_values);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>