<?php

class FixStatsInProblemsTable extends Migration {

    function init() {
$this->add_check_stats_table =<<<SQL
create table check_stats (
    prob_id int(11),
    tot_tries int(11),
    tot_correct int(11),
    tot_time int(11),
    tot_tries_changed int(1) default 0,
    tot_correct_changed int(1) default 0,
    tot_time_changed int(1) default 0
) select id prob_id, tot_tries, tot_correct, tot_time from problems;
SQL;

$this->update_tot_correct =<<<SQL
update problems t1 join (
    select prob_id,count(*) tot_correct from responses where ans_correct > 0 group by prob_id
    ) t2 on t2.prob_id=t1.id set t1.tot_correct=t2.tot_correct;
SQL;

$this->update_tot_tries =<<<SQL
update problems t1 join (
    select prob_id,count(*) tot_tries from responses where answer > 0 group by prob_id
    ) t2 on t2.prob_id=t1.id set t1.tot_tries=t2.tot_tries;
SQL;

$this->update_tot_time =<<<SQL
update problems t1 join (
    select prob_id,sum(end_time - start_time) tot_time from responses where answer > 0 group by prob_id
    ) t2 on t2.prob_id=t1.id set t1.tot_time=t2.tot_time;
SQL;

$this->update_check_stats_table =<<<SQL
update check_stats t1 join problems t2 on t2.id=t1.prob_id set t1.tot_tries_changed=1 where t1.tot_tries != t2.tot_tries;
update check_stats t1 join problems t2 on t2.id=t1.prob_id set t1.tot_correct_changed=1 where t1.tot_correct != t2.tot_correct;
update check_stats t1 join problems t2 on t2.id=t1.prob_id set t1.tot_time_changed=1 where t1.tot_time != t2.tot_time;
SQL;

$this->show_changes =<<<SQL
SELECT count(*) total_problems, sum(tot_tries_changed) changes_in_tot_tries, sum(tot_correct_changed) changes_in_tot_correct, 
    sum(tot_time_changed) changes_in_tot_time FROM check_stats
SQL;

$this->compare_tot_tries =<<<SQL
select count(*) from
    (select t1.id prob_id, t1.tot_tries != sum(t2.count) error 
    from problems t1 join 12m_prob_ans t2 on t1.id=t2.prob_id where t1.id > 9 group by t1.id) v1 
where v1.error > 0
SQL;

$this->compare_tot_correct =<<<SQL
select count(*) from
    (select t1.id prob_id, t1.tot_correct != sum(t2.count) error 
    from problems t1 join 12m_prob_ans t2 on t1.id=t2.prob_id and t1.correct=t2.ans_num where t1.id > 9 group by t1.id) v1 
where v1.error > 0
SQL;

$this->drop_check_stats_table =<<<SQL
drop table if exists check_stats
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_check_stats_table);
        
        $this->db->exec_query($this->update_tot_tries);
        $this->db->exec_query($this->update_tot_correct);
        $this->db->exec_query($this->update_tot_time);
        $this->db->exec_query($this->update_check_stats_table);

        $res1 = $this->db->fetch_assoc($this->show_changes);
        $res2 = $this->db->fetch_assoc($this->compare_tot_tries);
        $res3 = $this->db->fetch_assoc($this->compare_tot_correct);
        
        $this->db->exec_query($this->drop_check_stats_table);

        ob_start();
        print_r($res1);
        print "Comparing total tries:\n";
        print_r($res2);
        print "Comparing total correct:\n";
        print_r($res3);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>
