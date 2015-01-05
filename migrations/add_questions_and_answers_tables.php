<?php

class AddQuestionsAndAnswersTables extends Migration {

    function init() {
$this->create_questions_table =<<<SQL
CREATE TABLE questions (
    id INT(11) NOT NULL auto_increment PRIMARY KEY,
    title VARCHAR(255),
    body text
)
SQL;

$this->create_answers_table =<<<SQL
CREATE TABLE answers (
    id INT(11) NOT NULL auto_increment PRIMARY KEY,
    question_id int(11),
    display_order int(4),
    is_correct int(1) default 0,
    body text
)
SQL;

$this->show_columns =<<<SQL
select table_name,column_name,data_type from information_schema.columns 
where table_schema=database() and table_name in ('questions','answers')
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->create_answers_table);
        $this->db->exec_query($this->create_questions_table);
        $res = $this->db->fetch_assoc($this->show_columns);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

