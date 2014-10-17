alter table `user` add column `selection_id` int(11);

CREATE TABLE `selections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selections_user_class_idx` (`user_id`,`class_id`)
);

insert into selections (user_id, class_id) select id user_id, selected_course_id class_id from user where selected_course_id is not null;
update user,selections set user.selection_id=selections.id where user.id=selections.user_id and user.selected_course_id=selections.class_id;
