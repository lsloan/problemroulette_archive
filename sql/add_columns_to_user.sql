alter table `user` add column `current_course_id` int(11);
alter table `user` add column `last_activity` datetime;
alter table `user` add column `page_loads` int(11) default 0;
