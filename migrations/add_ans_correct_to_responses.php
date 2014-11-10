<?php

class AddAnsCorrectToResponses extends Migration {

    function init() {
$this->add_column =<<<SQL
alter table responses add column `ans_correct` INT(1) NOT NULL default 0
SQL;

$this->fill_column =<<<SQL
update responses t1 join problems t2 on t1.prob_id=t2.id set t1.ans_correct=1 where t1.answer=t2.correct
SQL;

$this->verify =<<<SQL
SELECT ans_correct, count(*) FROM responses group by ans_correct
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_column);
        $this->db->exec_query($this->fill_column);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>