CREATE TABLE {prefix}_glossary_terms (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	resource_id INT(11) UNSIGNED NOT NULL,
	creation_time INT(11) UNSIGNED NOT NULL,
	update_time INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	KEY (resource_id),
	KEY (creation_time),
	KEY (update_time)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_glossary_term_expressions (
	glossary_term_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression TEXT,
	PRIMARY KEY (glossary_term_id, language_code)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_glossary_definitions (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	glossary_term_id INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	KEY (glossary_term_id)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_glossary_definition_expressions (
	glossary_definition_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression TEXT,
	PRIMARY KEY (glossary_definition_id, language_code)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_glossary_categories (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	resource_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	PRIMARY KEY (id),
	KEY (resource_id)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_glossary_category_expressions (
	glossary_category_id INT(11) UNSIGNED NOT NULL,
	language_code VARCHAR(30) NOT NULL,
	expression	VARCHAR(255),
	PRIMARY KEY (glossary_category_id, language_code)
) ENGINE=InnoDB;

CREATE TABLE {prefix}_glossary_category_term_relations (
	glossary_category_id INT(11) UNSIGNED NOT NULL,
	glossary_term_id INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (glossary_category_id, glossary_term_id)
) ENGINE=InnoDB;