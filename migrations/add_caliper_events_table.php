<?php

class AddCaliperEventsTable extends Migration
{
    function init(){
        $this->setup = <<<SQL
CREATE TABLE IF NOT EXISTS caliper_events (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                           message_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                           headers text NOT NULL, body text NOT NULL)
SQL;


    }

    function migrate(){
        $this->db->exec_query($this->setup);
        ob_start();
        $msg = ob_get_clean();
        $this->info($msg);
    }
}
