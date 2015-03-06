<?php

class AddRatingScalesTable extends Migration {

    function init() {
$this->add_table =<<<SQL
create table if not exists rating_scales (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30),
  `min_label` varchar(16),
  `max_label` varchar(16),
  `min_icon` varchar(128),
  `max_icon` varchar(128),
  PRIMARY KEY (`id`),
  UNIQUE KEY `rating_scales_name_idx` (`name`)
)
SQL;

$this->add_values =<<<SQL
insert into rating_scales (name, min_label, max_label, min_icon, max_icon) 
  values ('Clarity', 'Opaque', 'Transparent', 'unclear.svg', 'clear.svg');
SQL;

$this->verify =<<<SQL
SELECT * FROM rating_scales
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_table);
        $this->db->exec_query($this->add_values);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>