<?php

class AddSemestersTable extends Migration {

    function init() {
$this->add_table =<<<SQL
create table if not exists semesters (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30),
  `start_time` datetime,
  `end_time` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `semesters_name_idx` (`name`)
)
SQL;

$this->add_values =<<<SQL
insert into semesters (name, start_time, end_time) values 
    ('Fall 2012',   '2012-09-01 00:00:00', '2012-12-25 23:59:59'),
    ('Winter 2013', '2012-12-26 00:00:00', '2013-05-02 23:59:59'),
    ('Spring 2013', '2013-05-03 00:00:00', '2013-06-25 23:59:59'),
    ('Summer 2013', '2013-06-26 00:00:00', '2013-08-16 23:59:59'),
    ('Fall 2013',   '2013-08-17 00:00:00', '2013-12-20 23:59:59'),
    ('Winter 2014', '2013-12-21 00:00:00', '2014-05-01 23:59:59'),
    ('Spring 2014', '2014-05-02 00:00:00', '2014-06-24 23:59:59'),
    ('Summer 2014', '2014-06-25 00:00:00', '2014-08-15 23:59:59'),
    ('Fall 2014',   '2014-08-16 00:00:00', '2014-12-19 23:59:59'),
    ('Winter 2015', '2014-12-20 00:00:00', '2015-04-30 23:59:59'),
    ('Spring 2015', '2015-05-01 00:00:00', '2015-06-26 23:59:59'),
    ('Summer 2015', '2015-06-27 00:00:00', '2015-08-21 23:59:59'),
    ('Fall 2015',   '2015-08-22 00:00:00', '2015-12-23 23:59:59');
SQL;

$this->verify =<<<SQL
SELECT count(*) FROM semesters
SQL;
    }

    function migrate() {
        $this->db->exec_query($this->add_table);
        $this->db->exec_query($this->add_values);
        $res = $this->db->fetch_assoc($this->verify);
        ob_start();
        print_r($res);
        $msg = ob_get_clean();
        $this->info($msg);
    }
}

?>