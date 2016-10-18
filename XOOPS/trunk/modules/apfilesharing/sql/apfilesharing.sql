#
# Table structure for table `apfilesharing_folder`
#

CREATE TABLE apfilesharing_folder (
  folder_id int(5) unsigned NOT NULL auto_increment,
  parent_id int(5) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  description text,
  create_date int(11) default 0,
  edit_date int(11) default 0,
  user_id int(11) default 0,
  read_permission_type varchar(30) default 'public',
  edit_permission_type varchar(30) default 'public',
  PRIMARY KEY (folder_id),
  KEY (parent_id)
) ENGINE=MyISAM;


INSERT INTO `apfilesharing_folder` 
(`folder_id`, `parent_id`, `title`, `description`, `create_date`, `edit_date`,`user_id`) VALUES
(1,0,'root','root',0,0,1);

#
# Table structure for table `apfilesharing_files`
#

CREATE TABLE apfilesharing_files (
  file_id int(11) unsigned NOT NULL auto_increment,
  folder_id int(5) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  ext varchar(10) NOT NULL default '',
  user_id int(11) unsigned NOT NULL default '0',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  description text,
  create_date int(11) default 0,
  edit_date int(11) default 0,
  read_permission_type varchar(30) default 'public',
  edit_permission_type varchar(30) default 'public',
  PRIMARY KEY (file_id),
  KEY (folder_id),
  KEY (date),
  KEY (status),
  KEY (title)
) ENGINE=MyISAM;

CREATE TABLE apfilesharing_files_read_permission (
  file_id int(11) ,
  group_id int(5)
) ENGINE=MyISAM;

CREATE TABLE apfilesharing_files_edit_permission (
  file_id int(11) ,
  group_id int(5)
) ENGINE=MyISAM;

CREATE TABLE apfilesharing_folder_read_permission (
  folder_id int(11) ,
  group_id int(5)
) ENGINE=MyISAM;

CREATE TABLE apfilesharing_folder_edit_permission (
  folder_id int(11) ,
  group_id int(5)
) ENGINE=MyISAM;