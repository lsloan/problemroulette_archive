<?php

class CombineDuplicateProblemData extends Migration {

    function init() {
$this->add_check_totals_table =<<<SQL
create table check_totals (
    name varchar(10), 
    problems_count int(11),
    records_with_duplicates int(11),
    problems_ans_count bigint(21), 
    problems_tot_tries bigint(21), 
    problems_tot_correct bigint(21), 
    problems_tot_time bigint(21), 
    ans_count_1 int(11), 
    ans_count_2 int(11), 
    ans_count_3 int(11), 
    ans_count_4 int(11), 
    ans_count_5 int(11), 
    ans_count_6 int(11), 
    ans_count_7 int(11)
    )
SQL;

$this->add_record_to_check_totals =<<<SQL
insert into check_totals (name, problems_ans_count, problems_tot_tries, problems_tot_correct, problems_tot_time) 
    select :name, sum(ans_count) problems_ans_count, sum(tot_tries) problems_tot_tries, 
    sum(tot_correct) problems_tot_correct, sum(tot_time) problems_tot_time 
    from problems;
update check_totals set problems_count=(select count(*) from problems) where name=:name;
update check_totals set records_with_duplicates=(select count(*) from  
    (select url, count(*) count from problems group by url) t1 where t1.count > 1) where name=:name;
update check_totals set ans_count_1=(select sum(count) from 12m_prob_ans where ans_num=1) where name=:name;
update check_totals set ans_count_2=(select sum(count) from 12m_prob_ans where ans_num=2) where name=:name;
update check_totals set ans_count_3=(select sum(count) from 12m_prob_ans where ans_num=3) where name=:name;
update check_totals set ans_count_4=(select sum(count) from 12m_prob_ans where ans_num=4) where name=:name;
update check_totals set ans_count_5=(select sum(count) from 12m_prob_ans where ans_num=5) where name=:name;
update check_totals set ans_count_6=(select sum(count) from 12m_prob_ans where ans_num=6) where name=:name;
update check_totals set ans_count_7=(select sum(count) from 12m_prob_ans where ans_num=7) where name=:name;
SQL;

$this->show_check_totals =<<<SQL
select * from check_totals
SQL;

$this->count_records_to_be_removed =<<<SQL
select (sum(t1.count) - count(*)) records_to_be_removed from  
    (select url, count(*) count from problems group by url) t1 
    where t1.count > 1
SQL;

$this->add_dupes_table =<<<SQL
CREATE TABLE duplicates (
    problem_id int(11), 
    dupes int(4), 
    url varchar(300), 
    ans_count int(11), 
    tot_tries int(11), 
    tot_correct int(11), 
    tot_time int(11)
) select t1.url, t1.problem_id, t1.dupes, t1.ans_count, t1.tot_tries, t1.tot_correct, t1.tot_time from  
    (select url, min(id) problem_id, count(*) dupes, sum(ans_count) ans_count, 
        sum(tot_tries) tot_tries, sum(tot_correct) tot_correct, sum(tot_time) tot_time 
        from problems group by url) t1 
where t1.dupes > 1
SQL;

$this->add_extras_table =<<<SQL
create table extra_records (primary_id int(11), other_id int(11)) 
select t1.url, t1.problem_id primary_id, t2.id other_id from 
duplicates t1 join problems t2 on t1.url=t2.url
SQL;

$this->update_12m_topic_prob =<<<SQL
update 12m_topic_prob t1 join extra_records t2 on t1.problem_id=t2.other_id set t1.problem_id=t2.primary_id
SQL;

$this->update_omitted_problems =<<<SQL
update ignore omitted_problems t1 join extra_records t2 on t1.problem_id=t2.other_id set t1.problem_id=t2.primary_id
SQL;

$this->update_responses =<<<SQL
update responses t1 join extra_records t2 on t1.prob_id=t2.other_id set t1.prob_id=t2.primary_id
SQL;

$this->find_dupes_in_12m_prob_ans_table =<<<SQL
create table dup_12m_prob_ans 
    (select t1.primary_id problem_id, t2.ans_num, sum(t2.count) count from 
    extra_records t1 join 12m_prob_ans t2 on t2.prob_id=t1.other_id 
    group by t1.primary_id, t2.ans_num)
SQL;

$this->update_12m_prob_ans_table =<<<SQL
update 12m_prob_ans t1 join dup_12m_prob_ans t2 on (t1.prob_id=t2.problem_id and t1.ans_num=t2.ans_num) set t1.count=t2.count
SQL;

$this->update_problems_table =<<<SQL
update problems t1 join duplicates t2 on t1.id=t2.problem_id 
    set t1.ans_count=t2.ans_count, t1.tot_tries=t2.tot_tries, t1.tot_correct=t2.tot_correct, t1.tot_time=t2.tot_time
SQL;

$this->remove_duplicate_records_from_12m_prob_ans =<<<SQL
delete from 12m_prob_ans where prob_id in (select other_id from extra_records where other_id <> primary_id)
SQL;

$this->remove_duplicate_records_from_problems =<<<SQL
delete from problems where id in (select other_id from extra_records where other_id <> primary_id)
SQL;
    }

    function migrate() {
        $before3 = $this->db->fetch_assoc($this->count_records_to_be_removed);
        $this->db->exec_query($this->add_check_totals_table);
        $this->db->exec_query($this->add_record_to_check_totals, array('name' => 'before'));

        $this->db->exec_query($this->add_dupes_table);
        $this->db->exec_query($this->add_extras_table);

        $this->db->exec_query($this->update_12m_topic_prob);
        $this->db->exec_query($this->update_omitted_problems);
        $this->db->exec_query($this->update_responses);
        $this->db->exec_query($this->find_dupes_in_12m_prob_ans_table);
        $this->db->exec_query($this->update_12m_prob_ans_table);
        $this->db->exec_query($this->update_problems_table);
        $this->db->exec_query($this->remove_duplicate_records_from_12m_prob_ans);
        $this->db->exec_query($this->remove_duplicate_records_from_problems);

        $this->db->exec_query($this->add_record_to_check_totals, array('name' => 'after'));
        $after3 = $this->db->fetch_assoc($this->show_check_totals);
        ob_start();
        print_r($before3);
        print_r($after3);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

