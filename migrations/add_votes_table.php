<?php

class AddVotesTable extends Migration {

    function init() {
$this->add_table =<<<SQL
CREATE TABLE IF NOT EXISTS `votes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `problem_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `topic` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `vote_key` (`problem_id`, `user_id`, `topic`)
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

