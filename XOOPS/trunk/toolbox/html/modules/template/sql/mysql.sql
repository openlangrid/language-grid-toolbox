CREATE TABLE {prefix}_template_translation_templates (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	resource_id INT(11) UNSIGNED NOT NULL,
	creation_time INT(11) UNSIGNED NOT NULL,
	update_time INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	KEY (resource_id),
	KEY (creation_time),
	KEY (update_time)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_translation_template_expressions (
	translation_template_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression TEXT,
	ngram TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci,
	PRIMARY KEY (translation_template_id, language_code),
	FULLTEXT KEY `ngram` (`ngram`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE {prefix}_template_bound_word_set_ids (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_default_bound_word_sets (
	id INT(11) UNSIGNED NOT NULL,
	`type` VARCHAR(255) NOT NULL,
	PRIMARY KEY (id),
	KEY (`type`)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_bound_word_sets (
	id INT(11) UNSIGNED NOT NULL,
	resource_id INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	KEY (resource_id)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_bound_word_set_expressions (
	bound_word_set_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression TEXT,
	PRIMARY KEY (bound_word_set_id, language_code)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_bound_words (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	bound_word_set_id INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	KEY (bound_word_set_id)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_bound_word_expressions (
	bound_word_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression	VARCHAR(255),
	PRIMARY KEY (bound_word_id, language_code)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_categories (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	resource_id INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	KEY (resource_id)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_category_expressions (
	category_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression	VARCHAR(255),
	PRIMARY KEY (category_id, language_code)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_bound_word_set_translation_template_relations (
	bound_word_set_id INT(11) UNSIGNED NOT NULL,
	translation_template_id INT(11) UNSIGNED NOT NULL,
	`index` INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (bound_word_set_id, translation_template_id, `index`)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_template_category_translation_template_relations (
	category_id INT(11) UNSIGNED NOT NULL,
	translation_template_id INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (category_id, translation_template_id)
) ENGINE=InnoDB;