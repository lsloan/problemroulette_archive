CREATE TABLE `omitted_problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  PRIMARY KEY (id)
);
create unique index omitted_problems_idx on omitted_problems(`user_id`, `topic_id`, `problem_id`);
