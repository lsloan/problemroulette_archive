<?php

class AddGlobalAlertsTable extends Migration {

  function init() {
$this->add_table =<<<SQL
create table if not exists global_alerts (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) not null,
  `priority` tinyint(2) unsigned not null,
  `start_time` datetime not null,
  `end_time` datetime not null,
  PRIMARY KEY (`id`),
  index `global_alerts_times_idx` using btree (`start_time`, `end_time`, `message`)
)
SQL;


$this->verify =<<<SQL
show columns FROM global_alerts
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

