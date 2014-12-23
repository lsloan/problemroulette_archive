<?php

class DropTempTables extends Migration {

    function init() {
$this->drop_tables =<<<SQL
drop table if exists 12m_prob_ans_backup;
drop table if exists check_totals;
drop table if exists duplicates;
drop table if exists extra_records;
drop table if exists user_backup;
SQL;

$this->show_tables =<<<SQL
select table_name, table_rows from information_schema.tables where table_schema=database()
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->drop_tables);
        $res = $this->db->fetch_assoc($this->show_tables);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>
