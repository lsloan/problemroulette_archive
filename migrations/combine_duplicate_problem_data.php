<?php

class CombineDuplicateProblemData extends Migration {
    function report_results($summary, $records_to_be_removed) {
        print "Results of 'CombineDuplicateProblemData' Migration\n\n";
        print "  Started with ".$summary[0]['problems_count']." records.\n";
        print "  Found ".$records_to_be_removed." records to be removed.\n";
        print "  Ended with ".$summary[1]['problems_count']." records.\n\n";

        print "  Records with duplicates before: ".$summary[0]['records_with_duplicates']."\n";
        print "   Records with duplicates after: ".$summary[1]['records_with_duplicates']."\n\n";

        print "  Comparing responses from before and after:\n\n";
        print "    Total responses with answer A\n";
        print "             Before:   ".$summary[0]['responses_answer_1']."\n";
        print "              After:   ".$summary[1]['responses_answer_1']."\n";
        print "       12m_prob_ans:   ".$summary[1]['12m_prob_ans_ans_num_1']."\n\n";
        print "    Total responses with answer B\n";
        print "             Before:   ".$summary[0]['responses_answer_2']."\n";
        print "              After:   ".$summary[1]['responses_answer_2']."\n";
        print "       12m_prob_ans:   ".$summary[1]['12m_prob_ans_ans_num_2']."\n\n";
        print "    Total responses with answer C\n";
        print "             Before:   ".$summary[0]['responses_answer_3']."\n";
        print "              After:   ".$summary[1]['responses_answer_3']."\n";
        print "       12m_prob_ans:   ".$summary[1]['12m_prob_ans_ans_num_3']."\n\n";
        print "    Total responses with answer D\n";
        print "             Before:   ".$summary[0]['responses_answer_4']."\n";
        print "              After:   ".$summary[1]['responses_answer_4']."\n";
        print "       12m_prob_ans:   ".$summary[1]['12m_prob_ans_ans_num_4']."\n\n";
        print "    Total responses with answer E\n";
        print "             Before:   ".$summary[0]['responses_answer_5']."\n";
        print "              After:   ".$summary[1]['responses_answer_5']."\n";
        print "       12m_prob_ans:   ".$summary[1]['12m_prob_ans_ans_num_5']."\n\n";
        print "    Total responses with answer F\n";
        print "             Before:   ".$summary[0]['responses_answer_6']."\n";
        print "              After:   ".$summary[1]['responses_answer_6']."\n";
        print "       12m_prob_ans:   ".$summary[1]['12m_prob_ans_ans_num_6']."\n\n";
        print "    Total responses with answer G\n";
        print "             Before:   ".$summary[0]['responses_answer_7']."\n";
        print "              After:   ".$summary[1]['responses_answer_7']."\n";
        print "       12m_prob_ans:   ".$summary[1]['12m_prob_ans_ans_num_7']."\n\n";

    }

    function init() {
$this->drop_migration_tables =<<<SQL
drop table if exists check_totals;
drop table if exists duplicates;
drop table if exists dup_12m_prob_ans;
drop table if exists extra_records;
drop table if exists 12m_prob_ans_backup;
SQL;

$this->add_check_totals_table =<<<SQL
create table check_totals (
    name varchar(10), 
    problems_count int(11),
    records_with_duplicates int(11),
    problems_ans_count bigint(21), 
    problems_tot_tries bigint(21), 
    problems_tot_correct bigint(21), 
    problems_tot_time bigint(21),
    responses_answer_1 int(11),
    12m_prob_ans_ans_num_1 int(11),
    responses_answer_2 int(11),
    12m_prob_ans_ans_num_2 int(11), 
    responses_answer_3 int(11),
    12m_prob_ans_ans_num_3 int(11), 
    responses_answer_4 int(11),
    12m_prob_ans_ans_num_4 int(11), 
    responses_answer_5 int(11),
    12m_prob_ans_ans_num_5 int(11), 
    responses_answer_6 int(11),
    12m_prob_ans_ans_num_6 int(11), 
    responses_answer_7 int(11),
    12m_prob_ans_ans_num_7 int(11)
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
update check_totals set 12m_prob_ans_ans_num_1=(select sum(count) from 12m_prob_ans where ans_num=1) where name=:name;
update check_totals set 12m_prob_ans_ans_num_2=(select sum(count) from 12m_prob_ans where ans_num=2) where name=:name;
update check_totals set 12m_prob_ans_ans_num_3=(select sum(count) from 12m_prob_ans where ans_num=3) where name=:name;
update check_totals set 12m_prob_ans_ans_num_4=(select sum(count) from 12m_prob_ans where ans_num=4) where name=:name;
update check_totals set 12m_prob_ans_ans_num_5=(select sum(count) from 12m_prob_ans where ans_num=5) where name=:name;
update check_totals set 12m_prob_ans_ans_num_6=(select sum(count) from 12m_prob_ans where ans_num=6) where name=:name;
update check_totals set 12m_prob_ans_ans_num_7=(select sum(count) from 12m_prob_ans where ans_num=7) where name=:name;
update check_totals set responses_answer_1=(select count(*) from responses where answer=1) where name=:name;
update check_totals set responses_answer_2=(select count(*) from responses where answer=2) where name=:name;
update check_totals set responses_answer_3=(select count(*) from responses where answer=3) where name=:name;
update check_totals set responses_answer_4=(select count(*) from responses where answer=4) where name=:name;
update check_totals set responses_answer_5=(select count(*) from responses where answer=5) where name=:name;
update check_totals set responses_answer_6=(select count(*) from responses where answer=6) where name=:name;
update check_totals set responses_answer_7=(select count(*) from responses where answer=7) where name=:name;
SQL;

$this->show_check_totals =<<<SQL
select * from check_totals
SQL;

$this->count_records_to_be_removed =<<<SQL
select (sum(t1.count) - count(*)) records_to_be_removed from  
    (select url, count(*) count from problems group by url) t1 
    where t1.count > 1
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

$this->add_dupes_table =<<<SQL
CREATE TABLE duplicates (
    problem_id int(11), 
    dupes int(11), 
    url varchar(300), 
    ans_count int(11), 
    tot_tries int(11), 
    tot_correct int(11), 
    tot_time int(11)
) select t1.problem_id, t1.url, t1.dupes, t1.ans_count, t1.tot_tries, t1.tot_correct, t1.tot_time from  
    (select url, min(id) problem_id, count(*) dupes, sum(ans_count) ans_count, 
        sum(tot_tries) tot_tries, sum(tot_correct) tot_correct, sum(tot_time) tot_time 
        from problems group by url) t1 
where t1.dupes > 1
SQL;

$this->add_extras_table =<<<SQL
create table extra_records (primary_id int(11), other_id int(11)) 
select t1.url, t1.problem_id primary_id, t2.id other_id from 
duplicates t1 join problems t2 on t1.url=t2.url;
create index extra_records_primary_id_idx on extra_records(primary_id);
create index extra_records_other_id_idx on extra_records(other_id);
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

        $this->db->exec_query($this->drop_migration_tables);
        $this->db->exec_query($this->add_check_totals_table);
        $this->db->exec_query($this->add_record_to_check_totals, array('name' => 'before'));

        $this->db->exec_query($this->add_dupes_table);
        $this->db->exec_query($this->add_extras_table);

        $this->db->exec_query($this->update_12m_topic_prob);
        $this->db->exec_query($this->update_omitted_problems);
        $this->db->exec_query($this->update_responses);

        $this->db->exec_query($this->update_problems_table);
        $this->db->exec_query($this->remove_duplicate_records_from_problems);

        $this->db->exec_query($this->backup_12m_prob_ans_table);
        $this->db->exec_query($this->clear_12m_prob_ans_table);
        $this->db->exec_query($this->rebuild_12m_prob_ans_table);

        $this->db->exec_query($this->add_record_to_check_totals, array('name' => 'after'));
        $after3 = $this->db->fetch_assoc($this->show_check_totals);
        ob_start();
        $this->report_results($after3, $before3[0]['records_to_be_removed']);
        print_r($after3);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

