CREATE TABLE tasks (
  id int unsigned auto_increment primary key,
  name varchar(255) not null,
  source_lang varchar(10) not null,
  target_lang varchar(10) not null,
  creator mediumint(8) unsigned not null,
  create_date datetime not null,
  modifier mediumint(8) unsigned,
  update_date datetime,
  delete_flag bit not null default false
) ENGINE=MyISAM;

CREATE TABLE histories (
  id int unsigned auto_increment primary key,
  task_id int unsigned not null references tasks.id,
  forum_id int unsigned,
  file_id int unsigned not null,
  smoothing_achievement tinyint unsigned not null default 0,
  smoothing_limit_date datetime not null,
  smoothing_worker varchar(255) not null,
  check_achievement tinyint unsigned not null default 0,
  check_limit_date datetime not null,
  check_worker varchar(255) not null,
  update_summary text,
  creator mediumint(8) unsigned not null,
  create_date datetime not null,
  modifier mediumint(8) unsigned,
  update_date datetime,
  delete_flag bit not null default false
) ENGINE=MyISAM;
