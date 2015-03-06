<?php

class AddRatingsTable extends Migration {

    function init() {
$this->add_table =<<<SQL
create table if not exists ratings (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) not null,
  `rating_scale_id` int(11),
  `user_id` int(11), 
  `rating` int(4),
  `created_at` timestamp default current_timestamp,
  PRIMARY KEY (`id`)
)
SQL;

$this->verify =<<<SQL
show columns FROM ratings
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

?>