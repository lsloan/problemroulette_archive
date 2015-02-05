<?php

class AddCreatedAtToUser extends Migration {

    function init() {
$this->add_created_at_column =<<<SQL
alter table user add column created_at datetime
SQL;

$this->use_first_response_if_possible =<<<SQL
update user user1 join (
    select user2.id u_id,min(responses.start_time) first_response from 
    user user2 join responses 
    on user2.id=responses.user_id group by responses.user_id) join_table 
on user1.id=join_table.u_id 
set user1.created_at=join_table.first_response
SQL;

$this->use_now_otherwise =<<<SQL
update user set created_at=now() where created_at is null
SQL;

$this->count_nulls =<<<SQL
SELECT count(*) FROM user where created_at is null
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_created_at_column);
        $this->db->exec_query($this->use_first_response_if_possible);
        $this->db->exec_query($this->use_now_otherwise);
        $res = $this->db->fetch_assoc($this->count_nulls);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>
