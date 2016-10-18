#
# Table structure for table `filesharing_folder`
#

CREATE TABLE filesharing_folder (
  cid int(5) unsigned NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  description text,
  create_date int(11) default 0,
  edit_date int(11) default 0,
  user_id int(11) default 0,
  read_permission_type varchar(30) default 'public',
  read_permission_user int(11) default 0,
  edit_permission_type varchar(30) default 'public',
  edit_permission_user int(11) default 0,
  PRIMARY KEY (cid),
  KEY (pid)
) TYPE=MyISAM;


INSERT INTO `filesharing_folder` 
(`cid`, `pid`, `title`, `description`, `create_date`, `edit_date`,`user_id`) VALUES
(1,0,'root','root',0,0,1);

#
# Table structure for table `filesharing_files`
#

CREATE TABLE filesharing_files (
  lid int(11) unsigned NOT NULL auto_increment,
  cid int(5) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  ext varchar(10) NOT NULL default '',
  submitter int(11) unsigned NOT NULL default '0',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  description text,
  create_date int(11) default 0,
  edit_date int(11) default 0,
  read_permission_type varchar(30) default 'public',
  read_permission_user int(11) default 0,
  edit_permission_type varchar(30) default 'public',
  edit_permission_user int(11) default 0,
  PRIMARY KEY (lid),
  KEY (cid),
  KEY (date),
  KEY (status),
  KEY (title)
) TYPE=MyISAM;
