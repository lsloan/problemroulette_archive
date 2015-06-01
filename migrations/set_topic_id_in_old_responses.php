<?php

class SetTopicIdInOldResponses extends Migration {
 function init() {
$this->number_of_problems =<<<SQL
select count(*) number_of_proplems from problems
SQL;

$this->number_of_problems_in_multi_topics =<<<SQL
select count(*) number_of_problems_in_multi_topics from
 (select problem_id from 12m_topic_prob
  group by problem_id having count(topic_id) > 1) as x
SQL;

$this->problems_in_one_topic =<<<SQL
select count(*) problems_in_one_topic from (select problem_id from 12m_topic_prob group by problem_id having count(topic_id) = 1) as x
SQL;

$this->number_of_responses =<<<SQL
select count(*) total_responses from responses
SQL;

$this->responses_for_p_in_multiple_topics =<<<SQL
select count(*) responses_for_problems_in_multiple_topics
from responses r where exists (
select 1 from 12m_topic_prob tp
where r.prob_id = tp.problem_id
group by tp.problem_id having count(tp.topic_id) > 1)
SQL;

$this->responses_for_p_in_1_topic =<<<SQL
select count(*) responses_for_problems_in_1_topic
from responses r where exists (
select 1 from 12m_topic_prob tp
where r.prob_id = tp.problem_id
group by tp.problem_id having count(tp.topic_id) = 1)
SQL;

$this->responses_for_problems_w_no_assoc_12m_topic =<<<SQL
select count(*) responses_for_problems_w_no_assoc_12m_topic from responses r
where r.prob_id not in (select problem_id from 12m_topic_prob)
SQL;

$this->responses_without_topic = <<<SQL
select count(*) responses_without_topic
from responses where topic_id is null
SQL;

$this->responses_with_topic =<<<SQL
select count(*) responses_with_topic from responses where topic_id is not null
SQL;


$this->update_responses_with_topic =<<<SQL
update responses as r,
( SELECT problem_id, topic_id from 12m_topic_prob
group by problem_id having count(topic_id) = 1 ) as t2
set r.topic_id = t2.topic_id
where r.prob_id = t2.problem_id
SQL;
}
    function migrate() {
        $sql = $this->db->fetch_assoc($this->number_of_problems);
        print_r($sql);
        $sql = $this->db->fetch_assoc($this->number_of_problems_in_multi_topics);
        print_r($sql);
        $sql = $this->db->fetch_assoc($this->problems_in_one_topic);
        print_r($sql);

        $sql1 = $this->db->fetch_assoc($this->number_of_responses);
        print_r($sql1);
        $sql2 = $this->db->fetch_assoc($this->responses_for_p_in_multiple_topics);
        print_r($sql2);
        $sql3 = $this->db->fetch_assoc($this->responses_for_p_in_1_topic);
        print_r($sql3);
        $sql4 = $this->db->fetch_assoc($this->responses_for_problems_w_no_assoc_12m_topic);
        print_r($sql4);
        print 'This total of responses for problems with and without assoc topics should equal the number or responses above: ';
        print  $sql2[0]['responses_for_problems_in_multiple_topics']+$sql3[0]['responses_for_problems_in_1_topic']+$sql4[0]['responses_for_problems_w_no_assoc_12m_topic']."\n";

        $sql = $this->db->fetch_assoc($this->responses_without_topic);
        print_r($sql);
        $sql = $this->db->fetch_assoc($this->responses_with_topic);
        print_r($sql);

        $this->db->exec_query($this->update_responses_with_topic);
        print 'After topic ids added to single topic responses';
        $sql = $this->db->fetch_assoc($this->responses_without_topic);
        print_r($sql);
        $sql = $this->db->fetch_assoc($this->responses_with_topic);
        print_r($sql);
        ob_start();
        $msg = ob_get_clean();
        $this->info($msg);
    }
}
