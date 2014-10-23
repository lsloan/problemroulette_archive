-- IMPORTANT: selections.sql must be run before drop_user_id_from_selected_topics.sql

alter table `selected_topics` add column `selection_id` int(11);
create UNIQUE index `selection_topic_idx` on `selected_topics` (`selection_id`,`topic_id`);
update `selected_topics` t1, `selections` t2, `12m_class_topic` t3  set t1.selection_id=t2.id where t1.topic_id=t3.topic_id and t2.class_id=t3.class_id and t2.user_id=t1.user_id;

alter table `selected_topics` drop index `selected_topics_idx`;
alter table `selected_topics` drop column `user_id`;
