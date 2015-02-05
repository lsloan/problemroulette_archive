<?php

class AddUniqueIndexForUserUsername extends Migration {

    function init() {
$this->create_duplicate_users_table =<<<SQL
create table duplicate_users select u3.id id from user u3 join 
    (select u2.u_id uid, u2.u_name uname from 
        (select u1.username u_name, min(u1.id) u_id, count(*) u_count from user u1 group by u1.username) u2 
        where u2.u_count > 1) u4 
    on u3.username=u4.uname and u3.id <> u4.uid
SQL;

$this->delete_duplicate_users =<<<SQL
delete from user where id in (select id from duplicate_users)
SQL;

$this->drop_duplicate_users_table =<<<SQL
drop table duplicate_users
SQL;

$this->add_user_username_idx =<<<SQL
create unique index user_username_idx on user(username)
SQL;

$this->verify =<<<SQL
select t1.username,t1.reccount from (select username,count(*) reccount from user group by username) t1 where t1.reccount > 1
SQL;
    }

    function migrate() {
        $res1 = $this->db->fetch_assoc($this->verify);
        $this->db->exec_query($this->create_duplicate_users_table);
        $this->db->exec_query($this->delete_duplicate_users);
        $this->db->exec_query($this->drop_duplicate_users_table);
        $this->db->exec_query($this->add_user_username_idx);
        $res2 = $this->db->fetch_assoc($this->verify);
        ob_start();
        print "before:\n";
        print_r($res1);
        print "after:\n";
        print_r($res2);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>