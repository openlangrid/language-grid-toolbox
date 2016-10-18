CREATE TABLE translation_logs (
  id int(11) NOT NULL auto_increment,
  user_id int(8) NOT NULL default 0,
  source_lang varchar(7) NOT NULL default '',
  target_lang varchar(7) NOT NULL default '',
  service_id varchar(255) NOT NULL default '',
  soapBindings TEXT,
  source TEXT,
  target TEXT,
  app_name varchar(255) default '',
  key01 varchar(128) default '',
  key02 varchar(128) default '',
  key03 varchar(128) default '',
  key04 varchar(128) default '',
  key05 varchar(128) default '',
  note1 TEXT,
  note2 TEXT,
  mt_flg char(1) default '1',
  input_print TEXT,
  output_print TEXT,
  config_print TEXT,
  create_date timestamp,
  edit_date timestamp,
  PRIMARY KEY  (id)
);

CREATE TABLE default_dictionary_setting(
    setting_id     int(11)    NOT NULL auto_increment,
    user_id        int(8)     NOT NULL,
    set_id         int(11)    NOT NULL,
    create_date    int(11),
    edit_date      int(11),
    delete_flag    char(1)    NOT NULL default '0',
    PRIMARY KEY (setting_id)
);

CREATE TABLE default_dictionary_bind(
    bind_id      int(11)         NOT NULL auto_increment,
    setting_id   int(11)         NOT NULL,
    bind_type    char(1)         NOT NULL,
    bind_value   varchar(2000)   NOT NULL,
    create_date  int(11),
    edit_date    int(11),
    delete_flag  char(1)         NOT NULL default '0',
    PRIMARY KEY (setting_id,bind_id)
);