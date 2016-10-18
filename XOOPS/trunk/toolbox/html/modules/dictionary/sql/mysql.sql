CREATE TABLE {prefix}_user_dictionary (
	user_dictionary_id int(11) NOT NULL auto_increment,
	user_id mediumint(8) NOT NULL default 0,
	type_id tinyint(3) NOT NULL default 0,
	dictionary_name VARCHAR(255) NOT NULL default 0,
	now_active char(3) NOT NULL default 'off',
	deploy_flag char(1) NOT NULL default 0,
	create_date int(11) NOT NULL,
	update_date int(11) NOT NULL,
	delete_flag char(1) NOT NULL default 0,
	PRIMARY KEY (user_dictionary_id),
	INDEX (user_id),
	INDEX (dictionary_name),
	INDEX (create_date),
	INDEX (update_date)
)
CREATE TABLE {prefix}_user_dictionary_contents (
	user_dictionary_id int(11),
	language VARCHAR(30) NOT NULL default '',
	row int (11) NOT NULL,
	contents text COLLATE utf8_unicode_ci,
	delete_flag char(1) NOT NULL default 0,
	INDEX (user_dictionary_id),
	INDEX (language)
)
CREATE TABLE {prefix}_user_dictionary_permission (
	user_dictionary_id int(11) NOT NULL,
	permission_type VARCHAR(30) default 'all',
	permission_type_id int(11) NOT NULL default 0,
	`view` tinyint(1) NOT NULL default 0,
	edit tinyint(1) NOT NULL default 0,
	`use` tinyint(1) NOT NULL default 0,
	delete_flag char(1) NOT NULL default 0,
	INDEX (user_dictionary_id)
)

CREATE INDEX index_user_dictionary_contents ON {prefix}_user_dictionary_contents (user_dictionary_id, row, language);
