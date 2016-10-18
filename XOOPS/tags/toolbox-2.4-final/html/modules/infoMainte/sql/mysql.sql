CREATE TABLE screen_info_mainte (
  user_id     mediumint(8) NOT NULL,
  module_id   varchar(20) NOT NULL,
  screen_id   varchar(20) NOT NULL,
  item_id     varchar(50) NOT NULL,
  parameter   text default '',
  create_date int(11) NOT NULL,
  delete_flag char(1) NOT NULL default 0,
  PRIMARY KEY (user_id, module_id,screen_id,item_id),
  KEY(create_date),
  KEY(delete_flag)
) TYPE=MyISAM;
