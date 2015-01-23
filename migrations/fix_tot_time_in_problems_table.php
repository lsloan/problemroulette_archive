<?php

class FixTotTimeInProblemsTable extends Migration {

	function init() {

$this->update_tot_time =<<<SQL
update problems t1 join (
    select prob_id,sum(timestampdiff(SECOND, start_time,end_time)) tot_time from responses where answer > 0 group by prob_id
    ) t2 on t2.prob_id=t1.id set t1.tot_time=t2.tot_time;
SQL;

}

	function migrate() {
		$res = $this->db->exec_query($this->update_tot_time);
	    ob_start();
	    print_r($res);
	    $msg = ob_get_clean();
	    $this->info($msg);
    }
}

?>