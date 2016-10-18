CREATE TABLE {prefix}_{dirname}_mailjob (
  mailjob_id int(10) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  body text NOT NULL,
  from_name varchar(255) default NULL,
  from_email varchar(255) default NULL,
  is_pm tinyint(1) NOT NULL default '0',
  is_mail tinyint(1) NOT NULL default '0',
  create_unixtime int(10) NOT NULL default '0',
  PRIMARY KEY (mailjob_id)
) TYPE=MyISAM;

CREATE TABLE {prefix}_{dirname}_mailjob_link (
  mailjob_id int(10) NOT NULL default '0',
  uid mediumint(8) NOT NULL default '0',
  retry tinyint(3) NOT NULL default '0',
  message varchar(255) default NULL,
  PRIMARY KEY (mailjob_id, uid)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS `{prefix}_{dirname}_sub_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub1_display` tinyint(1) DEFAULT NULL,
  `sub1_title` text,
  `sub1_length` int(11) DEFAULT NULL,
  `sub1_default` text,
  `sub2_display` tinyint(1) DEFAULT NULL,
  `sub2_title` text,
  `sub2_length` int(11) DEFAULT NULL,
  `sub2_default` text,
  `sub3_display` tinyint(1) DEFAULT NULL,
  `sub3_title` text,
  `sub3_length` int(11) DEFAULT NULL,
  `sub3_default` text,
  `sub4_display` tinyint(1) DEFAULT NULL,
  `sub4_title` text,
  `sub4_length` int(11) DEFAULT NULL,
  `sub4_default` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `{prefix}_{dirname}_sub_profiles` (
		 `sub1_display`, `sub1_title`, `sub1_length`, `sub1_default`,
		 `sub2_display`, `sub2_title`, `sub2_length`,`sub2_default`,
		 `sub3_display`, `sub3_title`, `sub3_length`, `sub3_default`,
		 `sub4_display`, `sub4_title`, `sub4_length`,`sub4_default`)
			 VALUES(0, '[ja]サブタイトル１[/ja][en]sub1title1[en][/en]', 0, '',
					0, '[ja]サブタイトル2[/ja][en]sub2title1[en][/en]', 0, '',
					0, '[ja]サブタイトル3[/ja][en]sub3title1[en][/en]', 0, '',
					0, '[ja]サブタイトル4[/ja][en]sub4title1[en][/en]', 0, '');

CREATE TABLE IF NOT EXISTS `{prefix}_{dirname}_sub_profile_data` (
  `uid` int(11) NOT NULL,
  `sub1_value` text,
  `sub2_value` text,
  `sub3_value` text,
  `sub4_value` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
