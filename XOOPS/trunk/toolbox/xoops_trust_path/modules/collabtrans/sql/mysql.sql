CREATE TABLE work_documents (
  id int unsigned auto_increment primary key,
  source_lang varchar(10) not null,
  target_lang varchar(10) not null,
  creator mediumint(8) not null,
  create_date timestamp not null,
  permission CHAR(1) NOT NULL DEFAULT 0,
  file_id int(11) unsigned NOT NULL,
  delete_flag bit not null default false
) ENGINE=MyISAM;


CREATE TABLE histories (
  id int unsigned auto_increment primary key,
  work_document_id int unsigned not null references work_documents.id,
  source text,
  target text,
  creator mediumint(8) not null,
  create_date timestamp not null,
  delete_flag bit not null default false
) ENGINE=MyISAM;

CREATE TABLE default_translation_path (
  id int unsigned auto_increment primary key,
  path_id INT(11) NOT NULL,
  source_lang VARCHAR(10) NOT NULL,
  target_lang VARCHAR(10) NOT NULL,
  creator mediumint(8) not null,
  create_date timestamp not null,
  delete_flag bit not null default false
) ENGINE=MyISAM;
