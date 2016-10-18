CREATE TABLE langrid_services (
  id int(11) NOT NULL auto_increment,
  service_id varchar(255) NOT NULL default '',
  service_type varchar(128) NOT NULL default '',
  allowed_app_provision varchar(50) NOT NULL default '',
  service_name varchar(255) NOT NULL default '',
  endpoint_url varchar(255) NOT NULL default '',
  supported_languages_paths TEXT,
  organization varchar(255) default '',
  copyright varchar(255) default '',
  license varchar(500)  default '',
  description text,
  registered_date varchar(30) default '',
  updated_date varchar(30) default '',
  misc_basic_userid varchar(128),
  misc_basic_passwd varchar(128),
  PRIMARY KEY (id)
) TYPE=MyISAM;

CREATE TABLE langrid_config_voice_setting (
  id int(11) NOT NULL auto_increment,
  user_id int(8) NOT NULL default 0,
  set_id INT(11) NOT NULL default 0,
  language varchar(35) NOT NULL default '',
  service_id varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE langrid_config_ebmt_learning (
  id  int(11) NOT NULL auto_increment,
  token  varchar(128),
  ebmt_service  varchar(255) NOT NULL,
  user_dictionary_id  int(11) NOT NULL,
  user_dictionary_name varchar(255) NOT NULL,
  source_lang  varchar(10) NOT NULL,
  target_lang  varchar(10) NOT NULL,
  status  varchar(50),
  create_time int(11),
  PRIMARY KEY (id)
) TYPE=MyISAM;
