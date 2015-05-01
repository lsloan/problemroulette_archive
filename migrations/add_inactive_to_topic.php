<?php

class AddInactiveToTopic extends Migration {

    function init() {
$this->add_inactive =<<<SQL
alter table topic add column `inactive` int(1) NOT NULL default 0
SQL;

$this->verify =<<<SQL
show columns FROM topic
SQL;

$this->verify0 =<<<SQL
SELECT count(*) as active_count FROM topic where inactive=0
SQL;

$this->verify1 =<<<SQL
SELECT count(*) as inactive_count FROM topic where inactive=1
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_inactive);
        $res = $this->db->fetch_assoc($this->verify);
        $res0 = $this->db->fetch_assoc($this->verify0);
        $res1 = $this->db->fetch_assoc($this->verify1);
        ob_start();
        print "\nColumns in topic table:\n";
        print_r($res);
        print "\n  Count of active topics: ".$res0[0]['active_count']."\n";
        print "Count of inactive topics: ".$res1[0]['inactive_count']."\n";
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>


