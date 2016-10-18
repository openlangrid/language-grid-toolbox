CREATE TABLE `{prefix}_autocomplete_setting` (
  user_id int(8) NOT NULL default 0,
  row_id int(8) NOT NULL default 0,
  search_target text,
  create_time int(11),
  PRIMARY KEY  (user_id, row_id)
);
