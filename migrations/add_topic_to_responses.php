<?php

class AddTopicToResponses extends Migration {

    function init() {
$this->add_topic =<<<SQL
alter table responses add column `topic_id` int(11) default NULL
SQL;

$this->add_prob_ans_index_idx =<<<SQL
create unique index prob_ans_num_idx on 12m_prob_ans(prob_id, ans_num)
SQL;

$this->verify =<<<SQL
show columns from responses
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_topic);
        $this->db->exec_query($this->add_prob_ans_index_idx);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>
