create table `user_backup` as select * from user;

alter table `user` add column `selected_course_id` int(11), add column `last_activity` datetime, add column `page_loads` int(11) default 0;

create table `selected_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (id)
);
create unique index selected_topics_idx on selected_topics(`user_id`, `topic_id`);
