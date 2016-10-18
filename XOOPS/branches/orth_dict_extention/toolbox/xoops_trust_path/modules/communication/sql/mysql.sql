CREATE TABLE category_access (
  cat_id smallint(5) unsigned NOT NULL default 0,
  uid mediumint(8) default NULL,
  groupid smallint(5) default NULL,
  `all` tinyint(1) NOT NULL default 0,
  can_post tinyint(1) NOT NULL default 0,
  can_edit tinyint(1) NOT NULL default 0,
  can_delete tinyint(1) NOT NULL default 0,
  post_auto_approved tinyint(1) NOT NULL default 0,
  can_makeforum tinyint(1) NOT NULL default 0,
  is_moderator tinyint(1) NOT NULL default 0,
  UNIQUE KEY (cat_id,uid),
  UNIQUE KEY (cat_id,groupid),
  KEY (cat_id),
  KEY (uid),
  KEY (groupid),
  KEY (can_post)
) ENGINE=MyISAM;

CREATE TABLE forum_access (
  forum_id int(6) unsigned NOT NULL default 0,
  uid mediumint(8) default NULL,
  groupid smallint(5) default NULL,
  `all` tinyint(1) NOT NULL default 0,
  can_post tinyint(1) NOT NULL default 0,
  can_edit tinyint(1) NOT NULL default 0,
  can_delete tinyint(1) NOT NULL default 0,
  post_auto_approved tinyint(1) NOT NULL default 0,
  is_moderator tinyint(1) NOT NULL default 0,
  UNIQUE KEY (forum_id,uid),
  UNIQUE KEY (forum_id,groupid),
  KEY (forum_id),
  KEY (uid),
  KEY (groupid),
  KEY (can_post)
) ENGINE=MyISAM;

CREATE TABLE topic_access (
  topic_id int(8) unsigned NOT NULL default 0,
  groupid smallint(5) default NULL,
  `all` tinyint(1) NOT NULL default 0,
  can_post tinyint(1) NOT NULL default 0,
  can_edit tinyint(1) NOT NULL default 0,
  can_delete tinyint(1) NOT NULL default 0,
  uid mediumint(8) default NULL,
  UNIQUE KEY (topic_id,groupid),
  KEY (topic_id),
  KEY (groupid),
  KEY (can_post)
) ENGINE=MyISAM;

CREATE TABLE categories (
  cat_id smallint(5) unsigned NOT NULL auto_increment,
  cat_title_ja varchar(255) NOT NULL default '',
  cat_desc_ja text,
  cat_title_en varchar(255) NOT NULL default '',
  cat_desc_en text,
  cat_original_language VARCHAR(30),
  pid smallint(5) unsigned NOT NULL default 0,
  cat_title varchar(255) NOT NULL default '',
  cat_desc text,
  cat_topics_count int(8) NOT NULL default 0,
  cat_posts_count int(10) NOT NULL default 0,
  cat_last_post_id int(10) NOT NULL default 0,
  cat_last_post_time int(10) NOT NULL default 0,
  cat_topics_count_in_tree int(8) NOT NULL default 0,
  cat_posts_count_in_tree int(10) NOT NULL default 0,
  cat_last_post_id_in_tree int(10) NOT NULL default 0,
  cat_last_post_time_in_tree int(10) NOT NULL default 0,
  cat_depth_in_tree smallint(5) NOT NULL default 0,
  cat_order_in_tree smallint(5) NOT NULL default 0,
  cat_path_in_tree text,
  cat_unique_path text,
  cat_weight smallint(5) NOT NULL default 0,
  cat_options text,
  create_date INT(11) NOT NULL default 0,
  update_date INT(11) NOT NULL default 0,
  delete_flag CHAR(1) NOT NULL default 0,
  PRIMARY KEY (cat_id),
  KEY (cat_weight),
  KEY (pid)
) ENGINE=MyISAM;

CREATE TABLE forums (
  forum_id int(6) unsigned NOT NULL auto_increment,
  cat_id smallint(5) unsigned NOT NULL default 0,
  uid int(11) not null default 0,
  forum_title_ja varchar(255) NOT NULL default '',
  forum_title_en varchar(255) NOT NULL default '',
  forum_desc_ja text,
  forum_desc_en text,
  forum_original_language VARCHAR(30),
  forum_external_link_format varchar(255) NOT NULL default '',
  forum_title varchar(255) NOT NULL default '',
  forum_desc text,
  forum_topics_count int(8) NOT NULL default 0,
  forum_posts_count int(10) NOT NULL default 0,
  forum_last_post_id int(10) unsigned NOT NULL default 0,
  forum_last_post_time int(10) NOT NULL default 0,
  forum_weight int(8) NOT NULL default 0,
  forum_options text,
  create_date INT(11) NOT NULL default 0,
  update_date INT(11) NOT NULL default 0,
  delete_flag CHAR(1) NOT NULL default 0,
  PRIMARY KEY (forum_id),
  KEY (forum_last_post_id),
  KEY (forum_last_post_time),
  KEY (forum_weight),
  KEY (cat_id)
) ENGINE=MyISAM;

CREATE TABLE topics (
  topic_id int(8) unsigned NOT NULL auto_increment,
  uid mediumint(8) unsigned NOT NULL default 0,
  forum_id int(6) unsigned NOT NULL default 0,
  topic_title_ja varchar(255) default NULL,
  topic_title_en varchar(255) default NULL,
  topic_original_language VARCHAR(30),
  topic_views int(10) NOT NULL default 0,
  topic_external_link_id varchar(255) NOT NULL default '',
  topic_title varchar(255) default NULL,
  topic_first_uid mediumint(8) NOT NULL default 0,
  topic_first_post_id int(10) unsigned NOT NULL default 0,
  topic_first_post_time int(10) NOT NULL default 0,
  topic_last_uid mediumint(8) NOT NULL default 0,
  topic_last_post_id int(10) unsigned NOT NULL default 0,
  topic_last_post_time int(10) NOT NULL default 0,
  topic_posts_count int(10) NOT NULL default 0,
  topic_locked tinyint(1) NOT NULL default 0,
  topic_sticky tinyint(1) NOT NULL default 0,
  topic_solved tinyint(1) NOT NULL default 1,
  topic_invisible tinyint(1) NOT NULL default 0,
  topic_votes_sum int(10) unsigned NOT NULL default 0,
  topic_votes_count int(10) unsigned NOT NULL default 0,
  create_date INT(11) NOT NULL default 0,
  update_date INT(11) NOT NULL default 0,
  delete_flag CHAR(1) NOT NULL default 0,
  PRIMARY KEY (topic_id),
  KEY (forum_id),
  KEY (topic_external_link_id),
  KEY (topic_last_post_time),
  KEY (topic_last_post_id),
  KEY (topic_id,forum_id),
  KEY (topic_solved),
  KEY (topic_sticky),
  KEY (topic_invisible),
  KEY (topic_votes_sum),
  KEY (topic_votes_count)
) ENGINE=MyISAM;

CREATE TABLE posts (
  post_id int(10) unsigned NOT NULL auto_increment,
  topic_id int(8) unsigned NOT NULL default 0,
  uid mediumint(8) unsigned NOT NULL default 0,
  poster_ip varchar(15) NOT NULL default '',
  post_text_ja text,
  post_text_en text,
  post_original_language VARCHAR(30),
  post_time int(10) NOT NULL default 0,
  pid int(10) unsigned NOT NULL default 0,
  modified_time int(10) NOT NULL default 0,
  uid_hidden mediumint(8) unsigned NOT NULL default 0,
  modifier_ip varchar(15) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  html tinyint(1) NOT NULL default 0,
  smiley tinyint(1) NOT NULL default 1,
  xcode tinyint(1) NOT NULL default 1,
  br tinyint(1) NOT NULL default 1,
  number_entity tinyint(1) NOT NULL default 0,
  special_entity tinyint(1) NOT NULL default 0,
  icon tinyint(3) NOT NULL default 0,
  attachsig tinyint(1) NOT NULL default 1,
  invisible tinyint(1) NOT NULL default 0,
  approval tinyint(1) NOT NULL default 1,
  votes_sum int(10) unsigned NOT NULL default 0,
  votes_count int(10) unsigned NOT NULL default 0,
  depth_in_tree smallint(5) NOT NULL default 0,
  order_in_tree smallint(5) NOT NULL default 0,
  path_in_tree text,
  unique_path text,
  guest_name varchar(25) NOT NULL default '',
  guest_email varchar(60) NOT NULL default '',
  guest_url varchar(100) NOT NULL default '',
  guest_pass_md5 varchar(40) NOT NULL default '',
  guest_trip varchar(40) NOT NULL default '',
  post_text text,
  post_text_waiting text,
  post_order INT NOT NULL default 0,
  reply_post_id int(10) default NULL,
  delete_flag CHAR(1) NOT NULL default 0,
  update_date INT(11) NOT NULL default 0,
  PRIMARY KEY (post_id),
  INDEX idx_post_time(post_time),
  INDEX idx_post_order(post_order),
  INDEX idx_reply_post_id(reply_post_id) 
) ENGINE=MyISAM;

CREATE TABLE users2topics (
  uid mediumint(8) unsigned NOT NULL default 0,
  topic_id int(8) unsigned NOT NULL default 0,
  u2t_time int(10) NOT NULL default 0,
  u2t_marked tinyint NOT NULL default 0,
  u2t_rsv tinyint NOT NULL default 0,
  PRIMARY KEY (uid,topic_id),
  KEY (uid),
  KEY (topic_id),
  KEY (u2t_time),
  KEY (u2t_marked),
  KEY (u2t_rsv)
) ENGINE=MyISAM;

CREATE TABLE post_votes (
  vote_id int(10) unsigned NOT NULL auto_increment,
  post_id int(10) unsigned NOT NULL default 0,
  uid mediumint(8) unsigned NOT NULL default 0,
  vote_point tinyint(3) NOT NULL default 0,
  vote_time int(10) NOT NULL default 0,
  vote_ip char(16) NOT NULL default '',
  PRIMARY KEY (vote_id),
  KEY (post_id),
  KEY (vote_ip)
) ENGINE=MyISAM;

CREATE TABLE post_histories (
  history_id int(10) unsigned NOT NULL auto_increment,
  post_id int(10) unsigned NOT NULL default 0,
  history_time int(10) NOT NULL default 0,
  data text,
  PRIMARY KEY (history_id),
  KEY (post_id)
) ENGINE=MyISAM;

CREATE TABLE topic_modify_log (
  modify_id int(10) unsigned NOT NULL auto_increment,
  topic_id int(8) unsigned,
  topic_title varchar(255) default NULL,
  language varchar(255),
  user_id mediumint(8),
  ip char(16),
  modify_time int(10),
  PRIMARY KEY (modify_id)
) ENGINE=MyISAM;

CREATE TABLE post_modify_log (
  modify_id int(10) unsigned NOT NULL auto_increment,
  post_id int(10) unsigned,
  text text,
  language varchar(255),
  user_id mediumint(8),
  ip char(16),
  modify_time int(10),
  PRIMARY KEY (modify_id)
) ENGINE=MyISAM;

CREATE TABLE categories_body (
  cat_id smallint(5) NOT NULL,
  language_code varchar(16) NOT NULL,
  title varchar(255) NOT NULL default '',
  `description` text,
  PRIMARY KEY (cat_id, language_code)
) ENGINE=MyISAM;

CREATE TABLE forums_body (
  forum_id int(6) NOT NULL,
  language_code varchar(16) NOT NULL,
  title varchar(255) NOT NULL default '',
  `description` text,
  PRIMARY KEY (forum_id, language_code)
) ENGINE=MyISAM;

CREATE TABLE topics_body (
  topic_id int(8) NOT NULL,
  language_code varchar(16) NOT NULL,
  title varchar(255) NOT NULL default '',
  `description` text,
  PRIMARY KEY (topic_id, language_code)
) ENGINE=MyISAM;

CREATE TABLE posts_body (
  post_id int(10) NOT NULL,
  language_code varchar(16) NOT NULL,
  title varchar(255) NOT NULL default '',
  `description` text,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (post_id, language_code)
) ENGINE=MyISAM;

CREATE TABLE bbs_correct_edit_history (
  bbs_id INT(10) NOT NULL,
  bbs_item_type_cd CHAR(2) NOT NULL,
  language_code CHAR(16) NOT NULL,
  history_count INT(11) NOT NULL,
  proc_type_cd CHAR(1) NOT NULL,
  bbs_text text,
  user_id MEDIUMINT(8) NOT NULL,
  create_date INT(11) NOT NULL,
  delete_flag CHAR(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (bbs_id, bbs_item_type_cd, language_code, history_count)
) ENGINE=MyISAM;

CREATE TABLE topic_access_log (
  topic_id int(8) NOT NULL DEFAULT 0,
  user_id INT(11) NOT NULL DEFAULT 0,
  last_access_time INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (topic_id, user_id)
) ENGINE=InnoDB;

CREATE TABLE forum_post_file (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  post_id INT(11) NOT NULL DEFAULT 0,
  file_name VARCHAR(255) NOT NULL DEFAULT '',
  file_data LONGBLOB NOT NULL DEFAULT '',
  file_size INT(11) NOT NULL DEFAULT 0
)ENGINE=InnoDB;


-- communication -------------------------------------
CREATE TABLE user_access (
  uid MEDIUMINT UNSIGNED NOT NULL,
  access_time DATETIME NOT NULL,
  function_distinction TINYINT,
  topic_id INT UNSIGNED,
  PRIMARY KEY (uid,function_distinction,topic_id)
) ENGINE=MyISAM;

CREATE TABLE content (
    content_id         INT UNSIGNED  AUTO_INCREMENT NOT NULL,
    topic_id           INT UNSIGNED NOT NULL,
    uid                INT NOT NULL,
    content_title      VARCHAR(256),
    content_url        VARCHAR(2083),
    content_type       VARCHAR(12),
    content_created    DATETIME,
    delete_flag        CHAR(1) NOT NULL DEFAULT 0,
    
    PRIMARY KEY (content_id)

) ENGINE=MyISAM;


CREATE TABLE attachment_content (
    attachment_content_id   INT UNSIGNED  AUTO_INCREMENT NOT NULL,
    post_id                 INT UNSIGNED NOT NULL,
    content_id              MEDIUMINT UNSIGNED NOT NULL,
    image_id                INT UNSIGNED,
    content_marker_created  DATETIME,
    
    PRIMARY KEY (attachment_content_id)

) ENGINE=MyISAM;


CREATE TABLE content_marker (
    marker_id               INT UNSIGNED  AUTO_INCREMENT NOT NULL,
    attachment_content_id   INT UNSIGNED NOT NULL,
    x_coordinate            FLOAT UNSIGNED NOT NULL,
    y_coordinate            FLOAT UNSIGNED NOT NULL,    
    content_marker_created  DATETIME,
    
    PRIMARY KEY (marker_id)

) ENGINE=MyISAM;


CREATE TABLE content_google_map (
    google_map_id        INT UNSIGNED  AUTO_INCREMENT NOT NULL,
    latitude             FLOAT NOT NULL,
    longitude            FLOAT NOT NULL,
    zoom                 MEDIUMINT UNSIGNED  NOT NULL,
    map_type             CHAR(1),

    start_addr_latitude  FLOAT NOT NULL,
    start_addr_longitude FLOAT NOT NULL,
    start_location       VARCHAR(256),
    end_addr_latitude    FLOAT NOT NULL,
    end_addr_longitude   FLOAT NOT NULL,
    end_location         VARCHAR(256),
    travel_mode          CHAR(1),    
    route_select         MEDIUMINT UNSIGNED,
    
    content_type        VARCHAR(20),
    content_id          INT UNSIGNED NOT NULL,
    google_map_created  DATETIME,
    
    PRIMARY KEY (google_map_id)

) ENGINE=MyISAM;


CREATE TABLE content_image (
    image_id          INT UNSIGNED  AUTO_INCREMENT NOT NULL,
    content_id        INT UNSIGNED NOT NULL,
    file_id int(11) unsigned NOT NULL default '0',
    image_file_name   VARCHAR(256),
    image_mimetype    VARCHAR(30),
    image_data        LONGBLOB,
    image_width       MEDIUMINT UNSIGNED,
    image_height      MEDIUMINT UNSIGNED,
    image_created     DATETIME,
    
    PRIMARY KEY (image_id)

) ENGINE=MyISAM;
