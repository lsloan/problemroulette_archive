<?php

class EnsureTalliesForAllPossibleAnswers extends Migration {

    function init() {
$this->list_errors =<<<SQL
select 12mpa.prob_id, count(12mpa.prob_id) as c, p.ans_count 
    from problems p, 12m_prob_ans 12mpa 
    where p.id = 12mpa.prob_id 
    group by 12mpa.prob_id having c < p.ans_count
SQL;

$this->get_total_responses =<<<SQL
select count(*) as total_responses from responses where answer > 0
SQL;

$this->get_sum_of_response_counts =<<<SQL
select sum(count) as sum_of_response_counts from 12m_prob_ans where ans_num > 0
SQL;

$this->add_row =<<<SQL
insert into 12m_prob_ans (prob_id, ans_num, count) values (:prob_id, :ans_num, :count)
SQL;

$this->update_row =<<<SQL
update 12m_prob_ans set count = :count where prob_id = :prob_id and ans_num = :ans_num
SQL;

$this->count_responses =<<<SQL
select answer, count(*) as ans_count from responses where prob_id=:prob_id and answer>0 group by answer
SQL;

$this->get_current_entries =<<<SQL
select ans_num, count from 12m_prob_ans where prob_id=:prob_id
SQL;
    }

    function migrate() {
        $before = $this->db->fetch_assoc($this->list_errors);
        if(sizeof($before) > 0) {
            foreach ($before as $index => $item) {
                $response_counts = array();
                $res = $this->db->fetch_assoc($this->count_responses, array(':prob_id' => $item['prob_id']));
                foreach ($res as $key => $value) {
                    $response_counts[$value['answer']] = $value['ans_count'];
                }
                $current_entries = array();
                $res = $this->db->fetch_assoc($this->get_current_entries, array(':prob_id' => $item['prob_id']));
                foreach ($res as $key => $value) {
                    $current_entries[$value['ans_num']] = $value['count'];
                }

                for ($i=1; $i <= $item['ans_count']; $i++) {
                    $bindings = array();
                    $bindings[':prob_id'] = $item['prob_id'];
                    $bindings[':ans_num'] = $i;
                    if(isset($current_entries[$i]) && isset($response_counts[$i])) {
                        if($current_entries[$i] == $response_counts[$i]) {
                            # everything is hunky-dory
                        } else {
                            # need to update current record with actual response count
                            $bindings[':count']   = $response_counts[$i];
                            $this->db->exec_query($this->update_row, $bindings);
                        }
                    } elseif (isset($response_counts[$i])) {
                        # need to insert new record with actual response count
                        $bindings[':count']   = $response_counts[$i];
                        $this->db->exec_query($this->add_row, $bindings);
                    } else {
                        # need to insert new record with 0 as response count
                        $bindings[':count']   = 0;
                        $this->db->exec_query($this->add_row, $bindings);
                    }
                }
            }
        }
        $after = $this->db->fetch_assoc($this->list_errors);
        $total_responses = $this->db->fetch_assoc($this->get_total_responses);
        $sum_of_response_counts = $this->db->fetch_assoc($this->get_sum_of_response_counts);
        ob_start();
        print("\n    Error count before: ".sizeof($before));
        print("\n     Error count after: ".sizeof($after));
        print("\nSum of response counts: ".$sum_of_response_counts[0]['sum_of_response_counts']);
        print("\n       Total responses: ".$total_responses[0]['total_responses']);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

