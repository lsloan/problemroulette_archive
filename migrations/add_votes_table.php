<?php

class AddVotesTable extends Migration {

    function init() {
$this->add_table =<<<SQL
CREATE TABLE IF NOT EXISTS votes (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `problem_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `topics` TEXT NOT NULL DEFAULT '',
    `created_at` timestamp DEFAULT '0000-00-00 00:00:00',
    `updated_at` timestamp ON UPDATE current_timestamp,
    PRIMARY KEY (`id`),
    UNIQUE INDEX (`problem_id`, `user_id`)
)
SQL;

$this->verify =<<<SQL
show columns FROM votes
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_table);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

