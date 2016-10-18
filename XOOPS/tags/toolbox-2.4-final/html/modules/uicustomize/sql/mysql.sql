CREATE TABLE {prefix}_uicustomize_support_ui_languages (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	language_code varchar(10) NOT NULL,
	language_name varchar(30) NOT NULL,
	language_dir varchar(30) NOT NULL,
	creation_time INT(11) UNSIGNED,
	creation_uid INT(8) UNSIGNED,
	PRIMARY KEY (id)
) TYPE = InnoDB;

INSERT INTO `{prefix}_uicustomize_support_ui_languages` (`language_code`, `language_name`, `language_dir`, `creation_time`, `creation_uid`) VALUES
('en', 'English', 'english', '0', '1'),
('ja', 'Japanese', 'ja_utf8','0', '1');


CREATE TABLE {prefix}_uicustomize_text_resource_files (
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	module_id INT(5) NOT NULL,
	language varchar(10) NOT NULL,
	shared_file_id INT(11) UNSIGNED NOT NULL,
	file_name varchar(255) NOT NULL,
	creation_time INT(11) UNSIGNED,
	creation_uid INT(8) UNSIGNED,
	PRIMARY KEY (id)
) TYPE = InnoDB;
