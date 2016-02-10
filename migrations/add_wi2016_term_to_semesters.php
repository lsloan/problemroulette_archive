<?php

class AddWi2016TermToSemesters extends Migration {

    function init() {
$this->add_values =<<<SQL
insert into semesters (name, abbreviation, start_time, end_time) values
    ('Winter 2016', 'wi16','2015-12-24 00:00:00', '2016-04-28 23:59:59');
SQL;

$this->verify =<<<SQL
SELECT count(*) FROM semesters
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_values);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>
