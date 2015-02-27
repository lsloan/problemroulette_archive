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
create unique index responses_uniqueness_idx on responses(user_id, prob_id, start_time);
create index responses_answer_idx on responses(answer);
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
insert into 12m_prob_ans (prob_id, ans_num, count) (
    select prob_id, answer ans_num, count(*) count 
    from responses where answer > 0 group by prob_id, answer);
SQL;

$this->create_12m_prob_ans_ans_num_idx =<<<SQL
create index 12m_prob_ans_ans_num_idx on 12m_prob_ans(ans_num);
SQL;

# =========== QUERIES TO COUNT RECORDS =================================================\

$this->count_responses =<<<SQL
select count(*) num_non_skip_responses from responses where answer > 0
SQL;

$this->count_sum_of_12m_prob_ans_count =<<<SQL
select sum(count) counts_from_12m_prob_ans from 12m_prob_ans
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

$this->count_errors_in_tot_tries =<<<SQL
select count(*) errors_in_tot_tries from (
    select t1.id, t1.tot_tries-count(t2.id) diff 
    from problems t1 join responses t2 on t1.id=t2.prob_id 
    where t2.answer > 0 group by t2.prob_id
) t3 where t3.diff <> 0
SQL;

$this->count_errors_in_tot_correct =<<<SQL
select count(*) errors_in_tot_correct from (
    select t1.id, t1.tot_correct-count(t2.id) diff 
    from problems t1 join responses t2 on t1.id=t2.prob_id 
    where t2.answer=t1.correct group by t2.prob_id
) t3 where t3.diff <> 0
SQL;

$this->count_errors_in_tot_time =<<<SQL
select count(*) errors_in_tot_time from (
    select t1.id, t1.tot_time-sum(timestampdiff(SECOND, t2.start_time,t2.end_time)) diff 
    from problems t1 join responses t2 on t1.id=t2.prob_id 
    where t2.answer > 0 group by t2.prob_id
) t3 where t3.diff <> 0
SQL;

$this->count_responses_by_answer =<<<SQL
select answer,count(*) response_count_by_ans from responses where answer > 0 group by answer
SQL;

$this->count_sum_of_12m_prob_ans_count_by_ans_num =<<<SQL
select ans_num, sum(count) sum_count_by_ans from 12m_prob_ans group by ans_num
SQL;

    }

    function migrate() {
        global $app_log;
        $app_log->msg("AddUniqueIndexForResponses starting migration");
        $num_responses_before = $this->db->fetch_assoc($this->count_responses);
        $num_records_with_duplicates_before = $this->db->fetch_assoc($this->count_records_with_duplicates);
        $app_log->msg("AddUniqueIndexForResponses collected initial record counts");

        $this->db->exec_query($this->create_duplicate_responses_table);
        $app_log->msg("AddUniqueIndexForResponses created duplicate_responses table");
        $this->db->exec_query($this->create_records_to_remove_table);
        $app_log->msg("AddUniqueIndexForResponses created records_to_remove table");

        $num_duplicates_to_remove = $this->db->fetch_assoc($this->count_duplicates_to_remove);
        $num_records_to_remove = $this->db->fetch_assoc($this->count_records_to_remove);

        $this->db->exec_query($this->delete_duplicate_responses);
        $app_log->msg("AddUniqueIndexForResponses deleted duplicate responses");
        $this->db->exec_query($this->add_responses_uniqueness_idx);
        $app_log->msg("AddUniqueIndexForResponses added responses_uniqueness_idx index");
        $this->db->exec_query($this->drop_temp_tables);
        $app_log->msg("AddUniqueIndexForResponses dropped temp tables");

        $num_responses_after = $this->db->fetch_assoc($this->count_responses);
        $num_records_with_duplicates_after = $this->db->fetch_assoc($this->count_records_with_duplicates);

        $this->db->exec_query($this->update_problems_tot_time_and_tot_tries);
        $app_log->msg("AddUniqueIndexForResponses updated tot_time and tot_tries");
        $this->db->exec_query($this->update_problems_tot_correct);
        $app_log->msg("AddUniqueIndexForResponses updated tot_correct");

        $this->db->exec_query($this->backup_12m_prob_ans_table);
        $this->db->exec_query($this->clear_12m_prob_ans_table);
        $this->db->exec_query($this->rebuild_12m_prob_ans_table);
        $this->db->exec_query($this->create_12m_prob_ans_ans_num_idx);
        $app_log->msg("AddUniqueIndexForResponses rebuilt 12m_prob_ans table");

        $num_errors_in_tot_tries = $this->db->fetch_assoc($this->count_errors_in_tot_tries);
        $num_errors_in_tot_correct = $this->db->fetch_assoc($this->count_errors_in_tot_correct);
        $num_errors_in_tot_time = $this->db->fetch_assoc($this->count_errors_in_tot_time);
        $app_log->msg("AddUniqueIndexForResponses retrieved status of responses updates");

        $responses_by_answer = $this->db->fetch_assoc($this->count_responses_by_answer);
        $sum_of_12m_prob_ans_count_by_ans_num = $this->db->fetch_assoc($this->count_sum_of_12m_prob_ans_count_by_ans_num);

        $sum_of_12m_prob_ans_count = $this->db->fetch_assoc($this->count_sum_of_12m_prob_ans_count);
        $app_log->msg("AddUniqueIndexForResponses retrieved status of 12m_prob_ans updates");



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
        print_r($num_errors_in_tot_tries);
        print_r($num_errors_in_tot_correct);
        print_r($num_errors_in_tot_time);

        for ($i = 0; $i < count($responses_by_answer); $i++) {
            print("  answer ".$responses_by_answer[$i]['answer']."  responses table: ".$responses_by_answer[$i]['response_count_by_ans']."  12m_prob_ans table: ".$sum_of_12m_prob_ans_count_by_ans_num[$i]['sum_count_by_ans']."\n");
        }
        print_r($num_responses_after[0]);
        print_r($sum_of_12m_prob_ans_count[0]);

        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>