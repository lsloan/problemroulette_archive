<?php

class AddUniqueIndexForResponses extends Migration {

    function init() {

# =========== QUERIES TO CREATE/DROP TEMPORARY TABLES =================================================\

$this->create_duplicate_responses_table =<<<SQL
create table duplicate_responses select * from (
    select user_id, prob_id, start_time, min(id) keeper, count(*) dupes 
    from responses group by user_id, prob_id, start_time 
    order by user_id, prob_id, start_time
) t1 where t1.dupes > 1
SQL;

$this->create_records_to_remove_table =<<<SQL
create table records_to_remove select r.id from responses r join duplicate_responses dr 
    on r.user_id=dr.user_id and r.prob_id=dr.prob_id and r.start_time=dr.start_time 
    where r.id <> dr.keeper;
create index rtr_idx on records_to_remove(id);
SQL;

$this->drop_temp_tables =<<<SQL
drop table duplicate_responses;
drop table records_to_remove;
SQL;

# =========== QUERIES TO DELETE RECORDS AND ADD UNIQUE INDEX =================================================\

$this->delete_duplicate_responses =<<<SQL
delete from responses where id in (select id from records_to_remove order by id)
SQL;

$this->add_responses_uniqueness_idx =<<<SQL
create unique index responses_uniqueness_idx on responses(user_id, prob_id, start_time)
SQL;

# =========== QUERIES TO FIX STATS =============================================

$this->update_problems_tot_time_and_tot_tries =<<<SQL
update problems t1 join (
    select prob_id,sum(timestampdiff(SECOND, start_time,end_time)) tot_time,count(*) tot_tries from responses where answer > 0 group by prob_id
    ) t2 on t2.prob_id=t1.id set t1.tot_time=t2.tot_time, t1.tot_tries=t2.tot_tries;
SQL;

$this->update_problems_tot_correct =<<<SQL
update problems t1 join (
    select prob_id, answer, count(*) tot_correct from responses where answer > 0 group by prob_id, answer
    ) t2 on t2.prob_id=t1.id and t1.correct=t2.answer set t1.tot_correct=t2.tot_correct;
SQL;

$this->backup_12m_prob_ans_table =<<<SQL
create table 12m_prob_ans_backup like 12m_prob_ans;
insert into 12m_prob_ans_backup (select * from 12m_prob_ans)
SQL;

$this->clear_12m_prob_ans_table =<<<SQL
delete from 12m_prob_ans
SQL;

$this->rebuild_12m_prob_ans_table =<<<SQL
insert into 12m_prob_ans (prob_id, ans_num, count) (select prob_id, answer ans_num, count(*) count from responses where answer > 0 group by prob_id, answer)
SQL;

# =========== QUERIES TO COUNT RECORDS =================================================\

$this->count_responses =<<<SQL
select count(*) num_responses from responses
SQL;

$this->count_records_with_duplicates =<<<SQL
select count(*) num_records_with_duplicates from (
    select user_id, prob_id, start_time, count(*) dupes from responses 
    group by user_id, prob_id, start_time 
    order by user_id, prob_id, start_time
) t1 where t1.dupes > 1
SQL;

$this->count_duplicates_to_remove =<<<SQL
select sum(dupes)-count(*) num_dupes_to_remove from duplicate_responses
SQL;

$this->count_records_to_remove =<<<SQL
select count(*) num_records_to_remove from records_to_remove
SQL;


    }

    function migrate() {
        $num_responses_before = $this->db->fetch_assoc($this->count_responses);
        $num_records_with_duplicates_before = $this->db->fetch_assoc($this->count_records_with_duplicates);

        $this->db->exec_query($this->create_duplicate_responses_table);
        $this->db->exec_query($this->create_records_to_remove_table);

        $num_duplicates_to_remove = $this->db->fetch_assoc($this->count_duplicates_to_remove);
        $num_records_to_remove = $this->db->fetch_assoc($this->count_records_to_remove);

        $this->db->exec_query($this->delete_duplicate_responses);
        $this->db->exec_query($this->add_responses_uniqueness_idx);
        $this->db->exec_query($this->drop_temp_tables);

        $num_responses_after = $this->db->fetch_assoc($this->count_responses);
        $num_records_with_duplicates_after = $this->db->fetch_assoc($this->count_records_with_duplicates);

        $update1 = $this->db->exec_query($this->update_problems_tot_time_and_tot_tries);
        $update2 = $this->db->exec_query($this->update_problems_tot_correct);

        $this->db->exec_query($this->backup_12m_prob_ans_table);
        $this->db->exec_query($this->clear_12m_prob_ans_table);
        $update3 = $this->db->exec_query($this->rebuild_12m_prob_ans_table);


        ob_start();
        print("\n=========== BEFORE ================================================\n");
        print_r($num_responses_before[0]);
        print_r($num_records_with_duplicates_before[0]);
        print_r($num_duplicates_to_remove[0]);
        print_r($num_records_to_remove[0]);
        print("\n=========== AFTER =================================================\n");
        print_r($num_responses_after[0]);
        print_r($num_records_with_duplicates_after[0]);
        print("\n=========== FIX STATS =============================================\n");
        print_r($update1);
        print_r($update2);
        print_r($update3);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>