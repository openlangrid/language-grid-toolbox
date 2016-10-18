CREATE TABLE `{prefix}_{dirname}_post_language` (
	`question_id` int(11) NOT NULL default 0,
	`answer_id` int(11) NOT NULL default 0,
	`language` varchar(30) NOT NULL,
	PRIMARY KEY (`question_id`, `answer_id`)
);

CREATE TABLE `{prefix}_{dirname}_post_author` (
	`question_id` int(11) NOT NULL default 0,
	`answer_id` int(11) NOT NULL default 0,
	`author_uname` varchar(25),
	PRIMARY KEY (`question_id`, `answer_id`)
);