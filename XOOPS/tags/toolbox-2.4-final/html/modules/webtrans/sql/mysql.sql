CREATE TABLE `{prefix}_{dirname}_template` (
  `template_id`   int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       int(8) NOT NULL default 0,
  `name`          VARCHAR(255) NOT NULL DEFAULT '',
  `pair_id`       int(11) UNSIGNED NOT NULL ,
  `source_text`   text,
  `target_text`   text,
  `create_time`   int(11) UNSIGNED NOT NULL DEFAULT 0,
  `update_time`   int(11) UNSIGNED NOT NULL DEFAULT 0,
  `delete_flag`   int(1) NOT NULL default '0',
  PRIMARY KEY (`template_id`)
);
CREATE TABLE `{prefix}_{dirname}_display_cache` (
  `display_key`   char(32) NOT NULL,
  `contents`      text,
  `user_id`       int(11) UNSIGNED NOT NULL DEFAULT 0,
  `create_time`   int(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`display_key`)
);