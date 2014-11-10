<?php

class AddIndexesForStatsExport extends Migration {

    function init() {
$this->add_semesters_time_idx =<<<SQL
create index semesters_time_idx on semesters(start_time, end_time)
SQL;

$this->add_responses_time_idx =<<<SQL
create index responses_time_idx on responses(start_time)
SQL;

$this->add_responses_user_id_idx =<<<SQL
create index responses_user_id_idx on responses(user_id)
SQL;

$this->add_responses_prob_id_idx =<<<SQL
create index responses_prob_id_idx on responses(prob_id)
SQL;

$this->add_12m_topic_prob_problem_id_idx =<<<SQL
create index 12m_topic_prob_prob_id_idx on 12m_topic_prob(problem_id)
SQL;

$this->add_12m_topic_prob_topic_id_idx =<<<SQL
create index 12m_topic_prob_topic_id_idx on 12m_topic_prob(topic_id)
SQL;

$this->add_12m_class_topic_topic_id_idx =<<<SQL
create index 12m_class_topic_topic_id_idx on 12m_class_topic(topic_id)
SQL;

$this->add_12m_class_topic_class_id_idx =<<<SQL
create index 12m_class_topic_class_id_idx on 12m_class_topic(class_id)
SQL;

$this->add_responses_answer_idx =<<<SQL
create index responses_answer_idx on responses(answer)
SQL;

$this->add_user_name_idx =<<<SQL
create index user_name_idx on user(name)
SQL;

$this->verify =<<<SQL
show indexes from responses
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_semesters_time_idx);
        $this->db->exec_query($this->add_responses_time_idx);
        $this->db->exec_query($this->add_responses_user_id_idx);
        $this->db->exec_query($this->add_responses_prob_id_idx);
        $this->db->exec_query($this->add_12m_topic_prob_problem_id_idx);
        $this->db->exec_query($this->add_12m_topic_prob_topic_id_idx);
        $this->db->exec_query($this->add_12m_class_topic_topic_id_idx);
        $this->db->exec_query($this->add_12m_class_topic_class_id_idx);
        $this->db->exec_query($this->add_responses_answer_idx);
        $this->db->exec_query($this->add_user_name_idx);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>