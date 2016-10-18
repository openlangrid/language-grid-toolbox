CREATE TABLE {prefix}_qa_questions (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	resource_id INT(11) UNSIGNED NOT NULL,
	creation_time INT(11) UNSIGNED NOT NULL,
	update_time INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	KEY (resource_id),
	KEY (creation_time),
	KEY (update_time)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_qa_question_expressions (
	qa_question_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression TEXT,
	PRIMARY KEY (qa_question_id, language_code)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_qa_answers (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	qa_question_id INT(11) UNSIGNED NOT NULL,
	creation_date INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	KEY (qa_question_id)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_qa_answer_expressions (
	qa_answer_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression TEXT,
	PRIMARY KEY (qa_answer_id, language_code)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_qa_categories (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	resource_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	PRIMARY KEY (id),
	KEY (resource_id)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_qa_category_expressions (
	qa_category_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression	VARCHAR(255),
	PRIMARY KEY (qa_category_id, language_code)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_qa_category_question_relations (
	qa_category_id INT(11) UNSIGNED NOT NULL,
	qa_question_id INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (qa_category_id, qa_question_id)
) ENGINE=InnoDB;