DROP TABLE IF EXISTS {prefix}langrid_services;
CREATE TABLE {prefix}langrid_services (
	service_id varchar(255) NOT NULL default '',
	service_type varchar(50) NOT NULL default '',
	service_name varchar(255) NOT NULL default '',
	endpoint_url varchar(255) NOT NULL default '',
	supported_languages_paths TEXT,
	now_active char(3) NOT NULL default 'off',
	organization varchar(255) default '',
	copyright varchar(255) default '',
	license varchar(500)  default '',
	description text default '',
	registered_date varchar(30) default '',
	updated_date varchar(30) default '',
	create_date timestamp default now(),
	edit_date timestamp,
	delete_flag char(1) NOT NULL default '0',
	PRIMARY KEY (service_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS {prefix}translation_set;
CREATE TABLE {prefix}translation_set (
  set_id INT(11) NOT NULL auto_increment,
  set_name VARCHAR(255) NOT NULL default '',
  user_id INT(8) NOT NULL default 0,
  shared_flag CHAR(1) NOT NULL default '0',
  create_user_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  update_user_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  create_time INT(11) UNSIGNED NOT NULL DEFAULT 0,
  update_time INT(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (set_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS {prefix}translation_path;
CREATE TABLE {prefix}translation_path (
  path_id INT(11) NOT NULL auto_increment,
  path_name VARCHAR(60),
  user_id INT(8) NOT NULL default 0,
  set_id INT(11) NOT NULL default 0,
  source_lang VARCHAR(10) NOT NULL default '',
  target_lang VARCHAR(10) NOT NULL default '',
  revs_path_id INT(11),
  create_user_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  update_user_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  create_time INT(11) UNSIGNED NOT NULL DEFAULT 0,
  update_time INT(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (path_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS {prefix}translation_exec;
CREATE TABLE {prefix}translation_exec (
  path_id INT(11) NOT NULL default 0,
  exec_id INT(11) NOT NULL default 0,
  exec_order INT(11) NOT NULL default 0,
  source_lang VARCHAR(10) NOT NULL default '',
  target_lang VARCHAR(10) NOT NULL default '',
  service_type VARCHAR(1) NOT NULL default '',
  service_id VARCHAR(255) NOT NULL default '',
  dictionary_flag  INT(1) NOT NULL default 0,
  create_user_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  update_user_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  create_time INT(11) UNSIGNED NOT NULL DEFAULT 0,
  update_time INT(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (path_id,exec_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS {prefix}translation_bind;
CREATE TABLE {prefix}translation_bind (
  path_id INT(11) NOT NULL default 0,
  exec_id INT(11) NOT NULL default 0,
  bind_id INT(11) NOT NULL default 0,
  bind_type char(1) NOT NULL default '0',
  bind_value TEXT NOT NULL,
  create_user_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  update_user_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  create_time INT(11) UNSIGNED NOT NULL DEFAULT 0,
  update_time INT(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (path_id,exec_id,bind_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS {prefix}default_dictionary_setting;
CREATE TABLE {prefix}default_dictionary_setting(
    setting_id     int(11)    NOT NULL auto_increment,
    user_id        int(8)     NOT NULL,
    set_id         int(11)    NOT NULL,
    create_date    int(11),
    edit_date      int(11),
    delete_flag    char(1)    NOT NULL default '0',
    PRIMARY KEY (setting_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS {prefix}default_dictionary_bind;
CREATE TABLE {prefix}default_dictionary_bind(
    bind_id      int(11)         NOT NULL auto_increment,
    setting_id   int(11)         NOT NULL,
    bind_type    char(1)         NOT NULL,
    bind_value   varchar(2000)   NOT NULL,
    create_date  int(11),
    edit_date    int(11),
    delete_flag  char(1)         NOT NULL default '0',
    PRIMARY KEY (setting_id,bind_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS {prefix}user_dictionary;
CREATE TABLE {prefix}user_dictionary (
	user_dictionary_id int(11) auto_increment ,
	user_id mediumint(8) NOT NULL default 0,
	type_id tinyint(3) NOT NULL default 0,
	dictionary_name VARCHAR(255) NOT NULL default 0,
	now_active char(3) NOT NULL default 'off',
	deploy_flag char(1) NOT NULL default 0,
	create_date int(11) NOT NULL,
	update_date int(11) NOT NULL,
	last_update_user varchar(255) default '',
	delete_flag char(1) NOT NULL default 0,
	PRIMARY KEY (user_dictionary_id),
	INDEX (user_id),
	INDEX (dictionary_name),
	INDEX (create_date),
	INDEX (update_date)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS {prefix}user_dictionary_contents;
CREATE TABLE {prefix}user_dictionary_contents (
	user_dictionary_id int(11),
	language VARCHAR(30) NOT NULL default '',
	row int (11) NOT NULL,
	contents text,
	delete_flag char(1) NOT NULL default 0,
	INDEX (user_dictionary_id),
	INDEX (language)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS {prefix}langrid_services_config;
CREATE TABLE {prefix}langrid_services_config (
	config_id int(11) NOT NULL,
	config_name varchar(255),
	config_value text,
	PRIMARY KEY (config_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS {prefix}service_grid_log;
CREATE TABLE {prefix}service_grid_log (
	log_id int(11) auto_increment,
	source_lang VARCHAR(10) NOT NULL default '',
	target_lang VARCHAR(10) NOT NULL default '',
	source TEXT,
	result TEXT,
	executed_time varbinary(14) NOT NULL,
	service_name TEXT,
	url varchar(255) default '',
	executed_user varchar(255) default '',
	PRIMARY KEY (log_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
DROP TABLE IF EXISTS {prefix}import_dictionary;
CREATE TABLE {prefix}import_dictionary (
	id INT(11) NOT NULL auto_increment,
	user_dictionary_id int(11) NOT NULL default 0,
	bind_type char(1) NOT NULL default '0',
	bind_value TEXT NOT NULL,
	create_date varbinary(14) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

INSERT INTO {prefix}langrid_services (service_id, service_type, service_name, endpoint_url, supported_languages_paths, now_active, organization, copyright, license, description, registered_date, updated_date, create_date, delete_flag) VALUES
('ChasenService', 'ANALYZER', 'ChaSen', 'http://langrid.org/service_manager/invoker/ChasenService', 'ja', 'off', 'Language Grid Operation Center', 'Nara Institute of Science and Technology', 'http://langrid.org/operation/document/ChaSen-License.pdf', 'This service provides morphological analysis. This is realized by wrapping ChaSen as a Web service.', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('ICTCLAS', 'ANALYZER', 'ICTCLAS', 'http://langrid.org/service_manager/invoker/ICTCLAS', 'zh', 'off', 'NLP Group, Institute of Computing Technology, Chinese Academy of Sciences', 'Institute of Computing Technology, Chinese Academy of Sciences', 'http://www.nlp.org.cn/docs/download.php?proj_id=6&prog_id=1', '', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('Juman_service', 'ANALYZER', 'Juman', 'http://langrid.org/service_manager/invoker/Juman_service', 'ja', 'off', 'Kurohashi Laboratory, Department of Intelligence Science and Technology, Graduate School of Informatics, Kyoto University', 'Kyoto University', 'Language Grid Users can copy or modify all or part of this language\nresource solely for the purpose of non-profit use. Language Grid Users\nshall not transfer or lend all or part of the language resource to any\nthird party without permission of the copyrig', 'This service provides morphological analysis. This is realized by wrapping Juman as Web services.', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('Klt', 'ANALYZER', 'KLT version 2.1', 'http://langrid.org/service_manager/invoker/Klt', 'ko', 'off', 'Seung-Shik Kang Laboratory, Kookmin University', 'Seung-Shik Kang', 'http://nlp.kookmin.ac.kr/', '', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('Mecab', 'ANALYZER', 'MeCab', 'http://langrid.org/service_manager/invoker/Mecab', 'ja', 'off', 'NTT Communication Science Laboratories', 'Taku Kudo, and Nippon Telegraph and Telephone Corporation', 'http://mecab.sourceforge.net/', '', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('TreeTagger', 'ANALYZER', 'TreeTagger', 'http://langrid.org/service_manager/invoker/TreeTagger', 'en,de,fr,it,es,nl,ru,bg,pt', 'off', 'Institut fuer Maschinelle Sprachverarbeitung (IMS), Universitaet Stuttgart', 'IMS Stuttgart', 'http://www.ims.uni-stuttgart.de/~schmid/Tagger-Licence', '', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('KyotoTourismDictionaryDb', 'DICTIONARY', 'Kyoto Tourism Dictionary with Longest Match Search', 'http://langrid.org/service_manager/invoker/KyotoTourismDictionaryDb', 'en2zh,zh2en,en2ja,ja2en,en2ko,ko2en,zh2ja,ja2zh,zh2ko,ko2zh,ja2ko,ko2ja', 'off', 'Language Infrastructure Group, National Institute of Information and Communications Technology', 'National Institute of Information and Communications Technology', 'Users can copy or modify all or part of this resource solely for non-profit use. Users shall not distribute, transfer or lend all or part of the resource to anyone without our permission.', 'Bilingual Dictionary with Longest Match Search', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('NaturalDisasterDb', 'DICTIONARY', 'Multi-language Glossary on Natural Disasters Service with Longest Match Search', 'http://langrid.org/service_manager/invoker/NaturalDisasterDb', 'ja2en,en2ja,ja2zh,zh2ja,ja2ko,ko2ja,ja2fr,fr2ja,ja2es,es2ja,en2zh,zh2en,en2ko,ko2en,en2fr,fr2en,en2es,es2en,zh2ko,ko2zh,zh2fr,fr2zh,zh2es,es2zh,ko2fr,fr2ko,ko2es,es2ko,fr2es,es2fr', 'off', 'Asian Disaster Reduction Center', 'Kenzo Toki', '', 'Bilingual Dictionary with Longest Match Search', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('GoogleTranslate', 'TRANSLATION', 'Google Translate', 'http://langrid.org/service_manager/invoker/GoogleTranslate', 'sq2ar,ar2sq,sq2bg,bg2sq,sq2zh-CN,zh-CN2sq,sq2ca,ca2sq,sq2hr,hr2sq,sq2ru,ru2sq,sq2el,el2sq,sq2nl,nl2sq,sq2en,en2sq,sq2da,da2sq,sq2cs,cs2sq,sq2zh-TW,zh-TW2sq,sq2de,de2sq,sq2iw,iw2sq,sq2hi,hi2sq,sq2hu,hu2sq,sq2id,id2sq,sq2it,it2sq,sq2pl,pl2sq,sq2pt-PT,pt-PT2sq,sq2et,et2sq,sq2sk,sk2sq,sq2ja,ja2sq,sq2ko,ko2sq,sq2lv,lv2sq,sq2lt,lt2sq,sq2mt,mt2sq,sq2sv,sv2sq,sq2th,th2sq,sq2tr,tr2sq,sq2uk,uk2sq,sq2no,no2sq,sq2ro,ro2sq,sq2vi,vi2sq,sq2tl,tl2sq,sq2fi,fi2sq,sq2fr,fr2sq,sq2gl,gl2sq,sq2es,es2sq,sq2sr,sr2sq,sq2sl,sl2sq,sq2af,af2sq,sq2be,be2sq,sq2is,is2sq,sq2ga,ga2sq,sq2mk,mk2sq,sq2ms,ms2sq,sq2fa,fa2sq,sq2sw,sw2sq,sq2cy,cy2sq,sq2yi,yi2sq,ar2bg,bg2ar,ar2zh-CN,zh-CN2ar,ar2ca,ca2ar,ar2hr,hr2ar,ar2ru,ru2ar,ar2el,el2ar,ar2nl,nl2ar,ar2en,en2ar,ar2da,da2ar,ar2cs,cs2ar,ar2zh-TW,zh-TW2ar,ar2de,de2ar,ar2iw,iw2ar,ar2hi,hi2ar,ar2hu,hu2ar,ar2id,id2ar,ar2it,it2ar,ar2pl,pl2ar,ar2pt-PT,pt-PT2ar,ar2et,et2ar,ar2sk,sk2ar,ar2ja,ja2ar,ar2ko,ko2ar,ar2lv,lv2ar,ar2lt,lt2ar,ar2mt,mt2ar,ar2sv,sv2ar,ar2th,th2ar,ar2tr,tr2ar,ar2uk,uk2ar,ar2no,no2ar,ar2ro,ro2ar,ar2vi,vi2ar,ar2tl,tl2ar,ar2fi,fi2ar,ar2fr,fr2ar,ar2gl,gl2ar,ar2es,es2ar,ar2sr,sr2ar,ar2sl,sl2ar,ar2af,af2ar,ar2be,be2ar,ar2is,is2ar,ar2ga,ga2ar,ar2mk,mk2ar,ar2ms,ms2ar,ar2fa,fa2ar,ar2sw,sw2ar,ar2cy,cy2ar,ar2yi,yi2ar,bg2zh-CN,zh-CN2bg,bg2ca,ca2bg,bg2hr,hr2bg,bg2ru,ru2bg,bg2el,el2bg,bg2nl,nl2bg,bg2en,en2bg,bg2da,da2bg,bg2cs,cs2bg,bg2zh-TW,zh-TW2bg,bg2de,de2bg,bg2iw,iw2bg,bg2hi,hi2bg,bg2hu,hu2bg,bg2id,id2bg,bg2it,it2bg,bg2pl,pl2bg,bg2pt-PT,pt-PT2bg,bg2et,et2bg,bg2sk,sk2bg,bg2ja,ja2bg,bg2ko,ko2bg,bg2lv,lv2bg,bg2lt,lt2bg,bg2mt,mt2bg,bg2sv,sv2bg,bg2th,th2bg,bg2tr,tr2bg,bg2uk,uk2bg,bg2no,no2bg,bg2ro,ro2bg,bg2vi,vi2bg,bg2tl,tl2bg,bg2fi,fi2bg,bg2fr,fr2bg,bg2gl,gl2bg,bg2es,es2bg,bg2sr,sr2bg,bg2sl,sl2bg,bg2af,af2bg,bg2be,be2bg,bg2is,is2bg,bg2ga,ga2bg,bg2mk,mk2bg,bg2ms,ms2bg,bg2fa,fa2bg,bg2sw,sw2bg,bg2cy,cy2bg,bg2yi,yi2bg,zh-CN2ca,ca2zh-CN,zh-CN2hr,hr2zh-CN,zh-CN2ru,ru2zh-CN,zh-CN2el,el2zh-CN,zh-CN2nl,nl2zh-CN,zh-CN2en,en2zh-CN,zh-CN2da,da2zh-CN,zh-CN2cs,cs2zh-CN,zh-CN2zh-TW,zh-TW2zh-CN,zh-CN2de,de2zh-CN,zh-CN2iw,iw2zh-CN,zh-CN2hi,hi2zh-CN,zh-CN2hu,hu2zh-CN,zh-CN2id,id2zh-CN,zh-CN2it,it2zh-CN,zh-CN2pl,pl2zh-CN,zh-CN2pt-PT,pt-PT2zh-CN,zh-CN2et,et2zh-CN,zh-CN2sk,sk2zh-CN,zh-CN2ja,ja2zh-CN,zh-CN2ko,ko2zh-CN,zh-CN2lv,lv2zh-CN,zh-CN2lt,lt2zh-CN,zh-CN2mt,mt2zh-CN,zh-CN2sv,sv2zh-CN,zh-CN2th,th2zh-CN,zh-CN2tr,tr2zh-CN,zh-CN2uk,uk2zh-CN,zh-CN2no,no2zh-CN,zh-CN2ro,ro2zh-CN,zh-CN2vi,vi2zh-CN,zh-CN2tl,tl2zh-CN,zh-CN2fi,fi2zh-CN,zh-CN2fr,fr2zh-CN,zh-CN2gl,gl2zh-CN,zh-CN2es,es2zh-CN,zh-CN2sr,sr2zh-CN,zh-CN2sl,sl2zh-CN,zh-CN2af,af2zh-CN,zh-CN2be,be2zh-CN,zh-CN2is,is2zh-CN,zh-CN2ga,ga2zh-CN,zh-CN2mk,mk2zh-CN,zh-CN2ms,ms2zh-CN,zh-CN2fa,fa2zh-CN,zh-CN2sw,sw2zh-CN,zh-CN2cy,cy2zh-CN,zh-CN2yi,yi2zh-CN,ca2hr,hr2ca,ca2ru,ru2ca,ca2el,el2ca,ca2nl,nl2ca,ca2en,en2ca,ca2da,da2ca,ca2cs,cs2ca,ca2zh-TW,zh-TW2ca,ca2de,de2ca,ca2iw,iw2ca,ca2hi,hi2ca,ca2hu,hu2ca,ca2id,id2ca,ca2it,it2ca,ca2pl,pl2ca,ca2pt-PT,pt-PT2ca,ca2et,et2ca,ca2sk,sk2ca,ca2ja,ja2ca,ca2ko,ko2ca,ca2lv,lv2ca,ca2lt,lt2ca,ca2mt,mt2ca,ca2sv,sv2ca,ca2th,th2ca,ca2tr,tr2ca,ca2uk,uk2ca,ca2no,no2ca,ca2ro,ro2ca,ca2vi,vi2ca,ca2tl,tl2ca,ca2fi,fi2ca,ca2fr,fr2ca,ca2gl,gl2ca,ca2es,es2ca,ca2sr,sr2ca,ca2sl,sl2ca,ca2af,af2ca,ca2be,be2ca,ca2is,is2ca,ca2ga,ga2ca,ca2mk,mk2ca,ca2ms,ms2ca,ca2fa,fa2ca,ca2sw,sw2ca,ca2cy,cy2ca,ca2yi,yi2ca,hr2ru,ru2hr,hr2el,el2hr,hr2nl,nl2hr,hr2en,en2hr,hr2da,da2hr,hr2cs,cs2hr,hr2zh-TW,zh-TW2hr,hr2de,de2hr,hr2iw,iw2hr,hr2hi,hi2hr,hr2hu,hu2hr,hr2id,id2hr,hr2it,it2hr,hr2pl,pl2hr,hr2pt-PT,pt-PT2hr,hr2et,et2hr,hr2sk,sk2hr,hr2ja,ja2hr,hr2ko,ko2hr,hr2lv,lv2hr,hr2lt,lt2hr,hr2mt,mt2hr,hr2sv,sv2hr,hr2th,th2hr,hr2tr,tr2hr,hr2uk,uk2hr,hr2no,no2hr,hr2ro,ro2hr,hr2vi,vi2hr,hr2tl,tl2hr,hr2fi,fi2hr,hr2fr,fr2hr,hr2gl,gl2hr,hr2es,es2hr,hr2sr,sr2hr,hr2sl,sl2hr,hr2af,af2hr,hr2be,be2hr,hr2is,is2hr,hr2ga,ga2hr,hr2mk,mk2hr,hr2ms,ms2hr,hr2fa,fa2hr,hr2sw,sw2hr,hr2cy,cy2hr,hr2yi,yi2hr,ru2el,el2ru,ru2nl,nl2ru,ru2en,en2ru,ru2da,da2ru,ru2cs,cs2ru,ru2zh-TW,zh-TW2ru,ru2de,de2ru,ru2iw,iw2ru,ru2hi,hi2ru,ru2hu,hu2ru,ru2id,id2ru,ru2it,it2ru,ru2pl,pl2ru,ru2pt-PT,pt-PT2ru,ru2et,et2ru,ru2sk,sk2ru,ru2ja,ja2ru,ru2ko,ko2ru,ru2lv,lv2ru,ru2lt,lt2ru,ru2mt,mt2ru,ru2sv,sv2ru,ru2th,th2ru,ru2tr,tr2ru,ru2uk,uk2ru,ru2no,no2ru,ru2ro,ro2ru,ru2vi,vi2ru,ru2tl,tl2ru,ru2fi,fi2ru,ru2fr,fr2ru,ru2gl,gl2ru,ru2es,es2ru,ru2sr,sr2ru,ru2sl,sl2ru,ru2af,af2ru,ru2be,be2ru,ru2is,is2ru,ru2ga,ga2ru,ru2mk,mk2ru,ru2ms,ms2ru,ru2fa,fa2ru,ru2sw,sw2ru,ru2cy,cy2ru,ru2yi,yi2ru,el2nl,nl2el,el2en,en2el,el2da,da2el,el2cs,cs2el,el2zh-TW,zh-TW2el,el2de,de2el,el2iw,iw2el,el2hi,hi2el,el2hu,hu2el,el2id,id2el,el2it,it2el,el2pl,pl2el,el2pt-PT,pt-PT2el,el2et,et2el,el2sk,sk2el,el2ja,ja2el,el2ko,ko2el,el2lv,lv2el,el2lt,lt2el,el2mt,mt2el,el2sv,sv2el,el2th,th2el,el2tr,tr2el,el2uk,uk2el,el2no,no2el,el2ro,ro2el,el2vi,vi2el,el2tl,tl2el,el2fi,fi2el,el2fr,fr2el,el2gl,gl2el,el2es,es2el,el2sr,sr2el,el2sl,sl2el,el2af,af2el,el2be,be2el,el2is,is2el,el2ga,ga2el,el2mk,mk2el,el2ms,ms2el,el2fa,fa2el,el2sw,sw2el,el2cy,cy2el,el2yi,yi2el,nl2en,en2nl,nl2da,da2nl,nl2cs,cs2nl,nl2zh-TW,zh-TW2nl,nl2de,de2nl,nl2iw,iw2nl,nl2hi,hi2nl,nl2hu,hu2nl,nl2id,id2nl,nl2it,it2nl,nl2pl,pl2nl,nl2pt-PT,pt-PT2nl,nl2et,et2nl,nl2sk,sk2nl,nl2ja,ja2nl,nl2ko,ko2nl,nl2lv,lv2nl,nl2lt,lt2nl,nl2mt,mt2nl,nl2sv,sv2nl,nl2th,th2nl,nl2tr,tr2nl,nl2uk,uk2nl,nl2no,no2nl,nl2ro,ro2nl,nl2vi,vi2nl,nl2tl,tl2nl,nl2fi,fi2nl,nl2fr,fr2nl,nl2gl,gl2nl,nl2es,es2nl,nl2sr,sr2nl,nl2sl,sl2nl,nl2af,af2nl,nl2be,be2nl,nl2is,is2nl,nl2ga,ga2nl,nl2mk,mk2nl,nl2ms,ms2nl,nl2fa,fa2nl,nl2sw,sw2nl,nl2cy,cy2nl,nl2yi,yi2nl,en2da,da2en,en2cs,cs2en,en2zh-TW,zh-TW2en,en2de,de2en,en2iw,iw2en,en2hi,hi2en,en2hu,hu2en,en2id,id2en,en2it,it2en,en2pl,pl2en,en2pt-PT,pt-PT2en,en2et,et2en,en2sk,sk2en,en2ja,ja2en,en2ko,ko2en,en2lv,lv2en,en2lt,lt2en,en2mt,mt2en,en2sv,sv2en,en2th,th2en,en2tr,tr2en,en2uk,uk2en,en2no,no2en,en2ro,ro2en,en2vi,vi2en,en2tl,tl2en,en2fi,fi2en,en2fr,fr2en,en2gl,gl2en,en2es,es2en,en2sr,sr2en,en2sl,sl2en,en2af,af2en,en2be,be2en,en2is,is2en,en2ga,ga2en,en2mk,mk2en,en2ms,ms2en,en2fa,fa2en,en2sw,sw2en,en2cy,cy2en,en2yi,yi2en,da2cs,cs2da,da2zh-TW,zh-TW2da,da2de,de2da,da2iw,iw2da,da2hi,hi2da,da2hu,hu2da,da2id,id2da,da2it,it2da,da2pl,pl2da,da2pt-PT,pt-PT2da,da2et,et2da,da2sk,sk2da,da2ja,ja2da,da2ko,ko2da,da2lv,lv2da,da2lt,lt2da,da2mt,mt2da,da2sv,sv2da,da2th,th2da,da2tr,tr2da,da2uk,uk2da,da2no,no2da,da2ro,ro2da,da2vi,vi2da,da2tl,tl2da,da2fi,fi2da,da2fr,fr2da,da2gl,gl2da,da2es,es2da,da2sr,sr2da,da2sl,sl2da,da2af,af2da,da2be,be2da,da2is,is2da,da2ga,ga2da,da2mk,mk2da,da2ms,ms2da,da2fa,fa2da,da2sw,sw2da,da2cy,cy2da,da2yi,yi2da,cs2zh-TW,zh-TW2cs,cs2de,de2cs,cs2iw,iw2cs,cs2hi,hi2cs,cs2hu,hu2cs,cs2id,id2cs,cs2it,it2cs,cs2pl,pl2cs,cs2pt-PT,pt-PT2cs,cs2et,et2cs,cs2sk,sk2cs,cs2ja,ja2cs,cs2ko,ko2cs,cs2lv,lv2cs,cs2lt,lt2cs,cs2mt,mt2cs,cs2sv,sv2cs,cs2th,th2cs,cs2tr,tr2cs,cs2uk,uk2cs,cs2no,no2cs,cs2ro,ro2cs,cs2vi,vi2cs,cs2tl,tl2cs,cs2fi,fi2cs,cs2fr,fr2cs,cs2gl,gl2cs,cs2es,es2cs,cs2sr,sr2cs,cs2sl,sl2cs,cs2af,af2cs,cs2be,be2cs,cs2is,is2cs,cs2ga,ga2cs,cs2mk,mk2cs,cs2ms,ms2cs,cs2fa,fa2cs,cs2sw,sw2cs,cs2cy,cy2cs,cs2yi,yi2cs,zh-TW2de,de2zh-TW,zh-TW2iw,iw2zh-TW,zh-TW2hi,hi2zh-TW,zh-TW2hu,hu2zh-TW,zh-TW2id,id2zh-TW,zh-TW2it,it2zh-TW,zh-TW2pl,pl2zh-TW,zh-TW2pt-PT,pt-PT2zh-TW,zh-TW2et,et2zh-TW,zh-TW2sk,sk2zh-TW,zh-TW2ja,ja2zh-TW,zh-TW2ko,ko2zh-TW,zh-TW2lv,lv2zh-TW,zh-TW2lt,lt2zh-TW,zh-TW2mt,mt2zh-TW,zh-TW2sv,sv2zh-TW,zh-TW2th,th2zh-TW,zh-TW2tr,tr2zh-TW,zh-TW2uk,uk2zh-TW,zh-TW2no,no2zh-TW,zh-TW2ro,ro2zh-TW,zh-TW2vi,vi2zh-TW,zh-TW2tl,tl2zh-TW,zh-TW2fi,fi2zh-TW,zh-TW2fr,fr2zh-TW,zh-TW2gl,gl2zh-TW,zh-TW2es,es2zh-TW,zh-TW2sr,sr2zh-TW,zh-TW2sl,sl2zh-TW,zh-TW2af,af2zh-TW,zh-TW2be,be2zh-TW,zh-TW2is,is2zh-TW,zh-TW2ga,ga2zh-TW,zh-TW2mk,mk2zh-TW,zh-TW2ms,ms2zh-TW,zh-TW2fa,fa2zh-TW,zh-TW2sw,sw2zh-TW,zh-TW2cy,cy2zh-TW,zh-TW2yi,yi2zh-TW,de2iw,iw2de,de2hi,hi2de,de2hu,hu2de,de2id,id2de,de2it,it2de,de2pl,pl2de,de2pt-PT,pt-PT2de,de2et,et2de,de2sk,sk2de,de2ja,ja2de,de2ko,ko2de,de2lv,lv2de,de2lt,lt2de,de2mt,mt2de,de2sv,sv2de,de2th,th2de,de2tr,tr2de,de2uk,uk2de,de2no,no2de,de2ro,ro2de,de2vi,vi2de,de2tl,tl2de,de2fi,fi2de,de2fr,fr2de,de2gl,gl2de,de2es,es2de,de2sr,sr2de,de2sl,sl2de,de2af,af2de,de2be,be2de,de2is,is2de,de2ga,ga2de,de2mk,mk2de,de2ms,ms2de,de2fa,fa2de,de2sw,sw2de,de2cy,cy2de,de2yi,yi2de,iw2hi,hi2iw,iw2hu,hu2iw,iw2id,id2iw,iw2it,it2iw,iw2pl,pl2iw,iw2pt-PT,pt-PT2iw,iw2et,et2iw,iw2sk,sk2iw,iw2ja,ja2iw,iw2ko,ko2iw,iw2lv,lv2iw,iw2lt,lt2iw,iw2mt,mt2iw,iw2sv,sv2iw,iw2th,th2iw,iw2tr,tr2iw,iw2uk,uk2iw,iw2no,no2iw,iw2ro,ro2iw,iw2vi,vi2iw,iw2tl,tl2iw,iw2fi,fi2iw,iw2fr,fr2iw,iw2gl,gl2iw,iw2es,es2iw,iw2sr,sr2iw,iw2sl,sl2iw,iw2af,af2iw,iw2be,be2iw,iw2is,is2iw,iw2ga,ga2iw,iw2mk,mk2iw,iw2ms,ms2iw,iw2fa,fa2iw,iw2sw,sw2iw,iw2cy,cy2iw,iw2yi,yi2iw,hi2hu,hu2hi,hi2id,id2hi,hi2it,it2hi,hi2pl,pl2hi,hi2pt-PT,pt-PT2hi,hi2et,et2hi,hi2sk,sk2hi,hi2ja,ja2hi,hi2ko,ko2hi,hi2lv,lv2hi,hi2lt,lt2hi,hi2mt,mt2hi,hi2sv,sv2hi,hi2th,th2hi,hi2tr,tr2hi,hi2uk,uk2hi,hi2no,no2hi,hi2ro,ro2hi,hi2vi,vi2hi,hi2tl,tl2hi,hi2fi,fi2hi,hi2fr,fr2hi,hi2gl,gl2hi,hi2es,es2hi,hi2sr,sr2hi,hi2sl,sl2hi,hi2af,af2hi,hi2be,be2hi,hi2is,is2hi,hi2ga,ga2hi,hi2mk,mk2hi,hi2ms,ms2hi,hi2fa,fa2hi,hi2sw,sw2hi,hi2cy,cy2hi,hi2yi,yi2hi,hu2id,id2hu,hu2it,it2hu,hu2pl,pl2hu,hu2pt-PT,pt-PT2hu,hu2et,et2hu,hu2sk,sk2hu,hu2ja,ja2hu,hu2ko,ko2hu,hu2lv,lv2hu,hu2lt,lt2hu,hu2mt,mt2hu,hu2sv,sv2hu,hu2th,th2hu,hu2tr,tr2hu,hu2uk,uk2hu,hu2no,no2hu,hu2ro,ro2hu,hu2vi,vi2hu,hu2tl,tl2hu,hu2fi,fi2hu,hu2fr,fr2hu,hu2gl,gl2hu,hu2es,es2hu,hu2sr,sr2hu,hu2sl,sl2hu,hu2af,af2hu,hu2be,be2hu,hu2is,is2hu,hu2ga,ga2hu,hu2mk,mk2hu,hu2ms,ms2hu,hu2fa,fa2hu,hu2sw,sw2hu,hu2cy,cy2hu,hu2yi,yi2hu,id2it,it2id,id2pl,pl2id,id2pt-PT,pt-PT2id,id2et,et2id,id2sk,sk2id,id2ja,ja2id,id2ko,ko2id,id2lv,lv2id,id2lt,lt2id,id2mt,mt2id,id2sv,sv2id,id2th,th2id,id2tr,tr2id,id2uk,uk2id,id2no,no2id,id2ro,ro2id,id2vi,vi2id,id2tl,tl2id,id2fi,fi2id,id2fr,fr2id,id2gl,gl2id,id2es,es2id,id2sr,sr2id,id2sl,sl2id,id2af,af2id,id2be,be2id,id2is,is2id,id2ga,ga2id,id2mk,mk2id,id2ms,ms2id,id2fa,fa2id,id2sw,sw2id,id2cy,cy2id,id2yi,yi2id,it2pl,pl2it,it2pt-PT,pt-PT2it,it2et,et2it,it2sk,sk2it,it2ja,ja2it,it2ko,ko2it,it2lv,lv2it,it2lt,lt2it,it2mt,mt2it,it2sv,sv2it,it2th,th2it,it2tr,tr2it,it2uk,uk2it,it2no,no2it,it2ro,ro2it,it2vi,vi2it,it2tl,tl2it,it2fi,fi2it,it2fr,fr2it,it2gl,gl2it,it2es,es2it,it2sr,sr2it,it2sl,sl2it,it2af,af2it,it2be,be2it,it2is,is2it,it2ga,ga2it,it2mk,mk2it,it2ms,ms2it,it2fa,fa2it,it2sw,sw2it,it2cy,cy2it,it2yi,yi2it,pl2pt-PT,pt-PT2pl,pl2et,et2pl,pl2sk,sk2pl,pl2ja,ja2pl,pl2ko,ko2pl,pl2lv,lv2pl,pl2lt,lt2pl,pl2mt,mt2pl,pl2sv,sv2pl,pl2th,th2pl,pl2tr,tr2pl,pl2uk,uk2pl,pl2no,no2pl,pl2ro,ro2pl,pl2vi,vi2pl,pl2tl,tl2pl,pl2fi,fi2pl,pl2fr,fr2pl,pl2gl,gl2pl,pl2es,es2pl,pl2sr,sr2pl,pl2sl,sl2pl,pl2af,af2pl,pl2be,be2pl,pl2is,is2pl,pl2ga,ga2pl,pl2mk,mk2pl,pl2ms,ms2pl,pl2fa,fa2pl,pl2sw,sw2pl,pl2cy,cy2pl,pl2yi,yi2pl,pt-PT2et,et2pt-PT,pt-PT2sk,sk2pt-PT,pt-PT2ja,ja2pt-PT,pt-PT2ko,ko2pt-PT,pt-PT2lv,lv2pt-PT,pt-PT2lt,lt2pt-PT,pt-PT2mt,mt2pt-PT,pt-PT2sv,sv2pt-PT,pt-PT2th,th2pt-PT,pt-PT2tr,tr2pt-PT,pt-PT2uk,uk2pt-PT,pt-PT2no,no2pt-PT,pt-PT2ro,ro2pt-PT,pt-PT2vi,vi2pt-PT,pt-PT2tl,tl2pt-PT,pt-PT2fi,fi2pt-PT,pt-PT2fr,fr2pt-PT,pt-PT2gl,gl2pt-PT,pt-PT2es,es2pt-PT,pt-PT2sr,sr2pt-PT,pt-PT2sl,sl2pt-PT,pt-PT2af,af2pt-PT,pt-PT2be,be2pt-PT,pt-PT2is,is2pt-PT,pt-PT2ga,ga2pt-PT,pt-PT2mk,mk2pt-PT,pt-PT2ms,ms2pt-PT,pt-PT2fa,fa2pt-PT,pt-PT2sw,sw2pt-PT,pt-PT2cy,cy2pt-PT,pt-PT2yi,yi2pt-PT,et2sk,sk2et,et2ja,ja2et,et2ko,ko2et,et2lv,lv2et,et2lt,lt2et,et2mt,mt2et,et2sv,sv2et,et2th,th2et,et2tr,tr2et,et2uk,uk2et,et2no,no2et,et2ro,ro2et,et2vi,vi2et,et2tl,tl2et,et2fi,fi2et,et2fr,fr2et,et2gl,gl2et,et2es,es2et,et2sr,sr2et,et2sl,sl2et,et2af,af2et,et2be,be2et,et2is,is2et,et2ga,ga2et,et2mk,mk2et,et2ms,ms2et,et2fa,fa2et,et2sw,sw2et,et2cy,cy2et,et2yi,yi2et,sk2ja,ja2sk,sk2ko,ko2sk,sk2lv,lv2sk,sk2lt,lt2sk,sk2mt,mt2sk,sk2sv,sv2sk,sk2th,th2sk,sk2tr,tr2sk,sk2uk,uk2sk,sk2no,no2sk,sk2ro,ro2sk,sk2vi,vi2sk,sk2tl,tl2sk,sk2fi,fi2sk,sk2fr,fr2sk,sk2gl,gl2sk,sk2es,es2sk,sk2sr,sr2sk,sk2sl,sl2sk,sk2af,af2sk,sk2be,be2sk,sk2is,is2sk,sk2ga,ga2sk,sk2mk,mk2sk,sk2ms,ms2sk,sk2fa,fa2sk,sk2sw,sw2sk,sk2cy,cy2sk,sk2yi,yi2sk,ja2ko,ko2ja,ja2lv,lv2ja,ja2lt,lt2ja,ja2mt,mt2ja,ja2sv,sv2ja,ja2th,th2ja,ja2tr,tr2ja,ja2uk,uk2ja,ja2no,no2ja,ja2ro,ro2ja,ja2vi,vi2ja,ja2tl,tl2ja,ja2fi,fi2ja,ja2fr,fr2ja,ja2gl,gl2ja,ja2es,es2ja,ja2sr,sr2ja,ja2sl,sl2ja,ja2af,af2ja,ja2be,be2ja,ja2is,is2ja,ja2ga,ga2ja,ja2mk,mk2ja,ja2ms,ms2ja,ja2fa,fa2ja,ja2sw,sw2ja,ja2cy,cy2ja,ja2yi,yi2ja,ko2lv,lv2ko,ko2lt,lt2ko,ko2mt,mt2ko,ko2sv,sv2ko,ko2th,th2ko,ko2tr,tr2ko,ko2uk,uk2ko,ko2no,no2ko,ko2ro,ro2ko,ko2vi,vi2ko,ko2tl,tl2ko,ko2fi,fi2ko,ko2fr,fr2ko,ko2gl,gl2ko,ko2es,es2ko,ko2sr,sr2ko,ko2sl,sl2ko,ko2af,af2ko,ko2be,be2ko,ko2is,is2ko,ko2ga,ga2ko,ko2mk,mk2ko,ko2ms,ms2ko,ko2fa,fa2ko,ko2sw,sw2ko,ko2cy,cy2ko,ko2yi,yi2ko,lv2lt,lt2lv,lv2mt,mt2lv,lv2sv,sv2lv,lv2th,th2lv,lv2tr,tr2lv,lv2uk,uk2lv,lv2no,no2lv,lv2ro,ro2lv,lv2vi,vi2lv,lv2tl,tl2lv,lv2fi,fi2lv,lv2fr,fr2lv,lv2gl,gl2lv,lv2es,es2lv,lv2sr,sr2lv,lv2sl,sl2lv,lv2af,af2lv,lv2be,be2lv,lv2is,is2lv,lv2ga,ga2lv,lv2mk,mk2lv,lv2ms,ms2lv,lv2fa,fa2lv,lv2sw,sw2lv,lv2cy,cy2lv,lv2yi,yi2lv,lt2mt,mt2lt,lt2sv,sv2lt,lt2th,th2lt,lt2tr,tr2lt,lt2uk,uk2lt,lt2no,no2lt,lt2ro,ro2lt,lt2vi,vi2lt,lt2tl,tl2lt,lt2fi,fi2lt,lt2fr,fr2lt,lt2gl,gl2lt,lt2es,es2lt,lt2sr,sr2lt,lt2sl,sl2lt,lt2af,af2lt,lt2be,be2lt,lt2is,is2lt,lt2ga,ga2lt,lt2mk,mk2lt,lt2ms,ms2lt,lt2fa,fa2lt,lt2sw,sw2lt,lt2cy,cy2lt,lt2yi,yi2lt,mt2sv,sv2mt,mt2th,th2mt,mt2tr,tr2mt,mt2uk,uk2mt,mt2no,no2mt,mt2ro,ro2mt,mt2vi,vi2mt,mt2tl,tl2mt,mt2fi,fi2mt,mt2fr,fr2mt,mt2gl,gl2mt,mt2es,es2mt,mt2sr,sr2mt,mt2sl,sl2mt,mt2af,af2mt,mt2be,be2mt,mt2is,is2mt,mt2ga,ga2mt,mt2mk,mk2mt,mt2ms,ms2mt,mt2fa,fa2mt,mt2sw,sw2mt,mt2cy,cy2mt,mt2yi,yi2mt,sv2th,th2sv,sv2tr,tr2sv,sv2uk,uk2sv,sv2no,no2sv,sv2ro,ro2sv,sv2vi,vi2sv,sv2tl,tl2sv,sv2fi,fi2sv,sv2fr,fr2sv,sv2gl,gl2sv,sv2es,es2sv,sv2sr,sr2sv,sv2sl,sl2sv,sv2af,af2sv,sv2be,be2sv,sv2is,is2sv,sv2ga,ga2sv,sv2mk,mk2sv,sv2ms,ms2sv,sv2fa,fa2sv,sv2sw,sw2sv,sv2cy,cy2sv,sv2yi,yi2sv,th2tr,tr2th,th2uk,uk2th,th2no,no2th,th2ro,ro2th,th2vi,vi2th,th2tl,tl2th,th2fi,fi2th,th2fr,fr2th,th2gl,gl2th,th2es,es2th,th2sr,sr2th,th2sl,sl2th,th2af,af2th,th2be,be2th,th2is,is2th,th2ga,ga2th,th2mk,mk2th,th2ms,ms2th,th2fa,fa2th,th2sw,sw2th,th2cy,cy2th,th2yi,yi2th,tr2uk,uk2tr,tr2no,no2tr,tr2ro,ro2tr,tr2vi,vi2tr,tr2tl,tl2tr,tr2fi,fi2tr,tr2fr,fr2tr,tr2gl,gl2tr,tr2es,es2tr,tr2sr,sr2tr,tr2sl,sl2tr,tr2af,af2tr,tr2be,be2tr,tr2is,is2tr,tr2ga,ga2tr,tr2mk,mk2tr,tr2ms,ms2tr,tr2fa,fa2tr,tr2sw,sw2tr,tr2cy,cy2tr,tr2yi,yi2tr,uk2no,no2uk,uk2ro,ro2uk,uk2vi,vi2uk,uk2tl,tl2uk,uk2fi,fi2uk,uk2fr,fr2uk,uk2gl,gl2uk,uk2es,es2uk,uk2sr,sr2uk,uk2sl,sl2uk,uk2af,af2uk,uk2be,be2uk,uk2is,is2uk,uk2ga,ga2uk,uk2mk,mk2uk,uk2ms,ms2uk,uk2fa,fa2uk,uk2sw,sw2uk,uk2cy,cy2uk,uk2yi,yi2uk,no2ro,ro2no,no2vi,vi2no,no2tl,tl2no,no2fi,fi2no,no2fr,fr2no,no2gl,gl2no,no2es,es2no,no2sr,sr2no,no2sl,sl2no,no2af,af2no,no2be,be2no,no2is,is2no,no2ga,ga2no,no2mk,mk2no,no2ms,ms2no,no2fa,fa2no,no2sw,sw2no,no2cy,cy2no,no2yi,yi2no,ro2vi,vi2ro,ro2tl,tl2ro,ro2fi,fi2ro,ro2fr,fr2ro,ro2gl,gl2ro,ro2es,es2ro,ro2sr,sr2ro,ro2sl,sl2ro,ro2af,af2ro,ro2be,be2ro,ro2is,is2ro,ro2ga,ga2ro,ro2mk,mk2ro,ro2ms,ms2ro,ro2fa,fa2ro,ro2sw,sw2ro,ro2cy,cy2ro,ro2yi,yi2ro,vi2tl,tl2vi,vi2fi,fi2vi,vi2fr,fr2vi,vi2gl,gl2vi,vi2es,es2vi,vi2sr,sr2vi,vi2sl,sl2vi,vi2af,af2vi,vi2be,be2vi,vi2is,is2vi,vi2ga,ga2vi,vi2mk,mk2vi,vi2ms,ms2vi,vi2fa,fa2vi,vi2sw,sw2vi,vi2cy,cy2vi,vi2yi,yi2vi,tl2fi,fi2tl,tl2fr,fr2tl,tl2gl,gl2tl,tl2es,es2tl,tl2sr,sr2tl,tl2sl,sl2tl,tl2af,af2tl,tl2be,be2tl,tl2is,is2tl,tl2ga,ga2tl,tl2mk,mk2tl,tl2ms,ms2tl,tl2fa,fa2tl,tl2sw,sw2tl,tl2cy,cy2tl,tl2yi,yi2tl,fi2fr,fr2fi,fi2gl,gl2fi,fi2es,es2fi,fi2sr,sr2fi,fi2sl,sl2fi,fi2af,af2fi,fi2be,be2fi,fi2is,is2fi,fi2ga,ga2fi,fi2mk,mk2fi,fi2ms,ms2fi,fi2fa,fa2fi,fi2sw,sw2fi,fi2cy,cy2fi,fi2yi,yi2fi,fr2gl,gl2fr,fr2es,es2fr,fr2sr,sr2fr,fr2sl,sl2fr,fr2af,af2fr,fr2be,be2fr,fr2is,is2fr,fr2ga,ga2fr,fr2mk,mk2fr,fr2ms,ms2fr,fr2fa,fa2fr,fr2sw,sw2fr,fr2cy,cy2fr,fr2yi,yi2fr,gl2es,es2gl,gl2sr,sr2gl,gl2sl,sl2gl,gl2af,af2gl,gl2be,be2gl,gl2is,is2gl,gl2ga,ga2gl,gl2mk,mk2gl,gl2ms,ms2gl,gl2fa,fa2gl,gl2sw,sw2gl,gl2cy,cy2gl,gl2yi,yi2gl,es2sr,sr2es,es2sl,sl2es,es2af,af2es,es2be,be2es,es2is,is2es,es2ga,ga2es,es2mk,mk2es,es2ms,ms2es,es2fa,fa2es,es2sw,sw2es,es2cy,cy2es,es2yi,yi2es,sr2sl,sl2sr,sr2af,af2sr,sr2be,be2sr,sr2is,is2sr,sr2ga,ga2sr,sr2mk,mk2sr,sr2ms,ms2sr,sr2fa,fa2sr,sr2sw,sw2sr,sr2cy,cy2sr,sr2yi,yi2sr,sl2af,af2sl,sl2be,be2sl,sl2is,is2sl,sl2ga,ga2sl,sl2mk,mk2sl,sl2ms,ms2sl,sl2fa,fa2sl,sl2sw,sw2sl,sl2cy,cy2sl,sl2yi,yi2sl,af2be,be2af,af2is,is2af,af2ga,ga2af,af2mk,mk2af,af2ms,ms2af,af2fa,fa2af,af2sw,sw2af,af2cy,cy2af,af2yi,yi2af,be2is,is2be,be2ga,ga2be,be2mk,mk2be,be2ms,ms2be,be2fa,fa2be,be2sw,sw2be,be2cy,cy2be,be2yi,yi2be,is2ga,ga2is,is2mk,mk2is,is2ms,ms2is,is2fa,fa2is,is2sw,sw2is,is2cy,cy2is,is2yi,yi2is,ga2mk,mk2ga,ga2ms,ms2ga,ga2fa,fa2ga,ga2sw,sw2ga,ga2cy,cy2ga,ga2yi,yi2ga,mk2ms,ms2mk,mk2fa,fa2mk,mk2sw,sw2mk,mk2cy,cy2mk,mk2yi,yi2mk,ms2fa,fa2ms,ms2sw,sw2ms,ms2cy,cy2ms,ms2yi,yi2ms,fa2sw,sw2fa,fa2cy,cy2fa,fa2yi,yi2fa,sw2cy,cy2sw,sw2yi,yi2sw,cy2yi,yi2cy', 'off', 'Google, Inc.', 'Google', 'http://code.google.com/intl/ja/apis/ajaxlanguage/terms.html', 'This service provides multilingual translation service.\nThis is realized by wrapping Google Translate as a Web service.\nTo use this service,learn Google AJAX API Terms of Use.\nhttp://code.google.com/intl/ja/apis/ajaxlanguage/terms.html', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('KyotoUJServer', 'TRANSLATION', 'J-Server (Kyoto-U)', 'http://langrid.org/service_manager/invoker/KyotoUJServer', 'ja2en,en2ja,ja2ko,ko2ja,ja2zh,zh2ja', 'off', 'Ishida and Matsubara Laboratory, Department of Social Informatics, Graduate School of Informatics, Kyoto University', 'Kodensha Co., Ltd.', '', 'J-Server running on Kyoto-U server.', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('NICTJServer', 'TRANSLATION', 'J-Server (NICT)', 'http://langrid.org/service_manager/invoker/NICTJServer', 'ja2en,en2ja,ja2ko,ko2ja,ja2zh,zh2ja', 'off', 'Language Infrastructure Group, National Institute of Information and Communications Technology', 'Kodensha Co., Ltd.', '', 'JServer running on NICT''s server', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('parsit', 'TRANSLATION', 'parsit', 'http://langrid.org/service_manager/invoker/parsit', 'en2th', 'off', 'National Electronics and Computer Technology Center', 'NECTEC, Thailand', 'NECTEC, Thailand', 'Parsit: English to Thai Machine Transltion', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('ToshibaMT', 'TRANSLATION', 'Toshiba English-Chinese Machine Translation', 'http://langrid.org/service_manager/invoker/ToshibaMT', 'en2zh,zh2en', 'off', 'Corporate R&amp;D Center, Toshiba Corp.', 'TOSHIBA CORPORATION', '', 'This atomic service is provided by wrapping English-Chinese translator.', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('Translution', 'TRANSLATION', 'Translution', 'http://langrid.org/service_manager/invoker/Translution', 'en-GB2fr,fr2en-GB,en-GB2de,de2en-GB,en-GB2es,es2en-GB,en-GB2it,it2en-GB,en-GB2pt,pt2en-GB,en-GB2nl,nl2en-GB,en-GB2sv,sv2en-GB,en-GB2el,el2en-GB,en-GB2ru,ru2en-GB,en-GB2ar,ar2en-GB,en-GB2ko,ko2en-GB,en-GB2ja,ja2en-GB,en-GB2zh-CN,zh-CN2en-GB,en-GB2zh-TW,zh-TW2en-GB,en2fr,fr2en,en2de,de2en,en2es,es2en,en2it,it2en,en2pt,pt2en,en2nl,nl2en,en2sv,sv2en,en2el,el2en,en2ru,ru2en,en2ar,ar2en,en2ko,ko2en,en2ja,ja2en,en2zh-CN,zh-CN2en,en2zh-TW,zh-TW2en,fr2de,de2fr,fr2es,es2fr,fr2it,it2fr,fr2pt,pt2fr,fr2nl,nl2fr,fr2el,el2fr', 'off', 'Ishida and Matsubara Laboratory, Department of Social Informatics, Graduate School of Informatics, Kyoto University', 'Translution', 'http://www.translution.com/about/licensing.aspx', 'This service provides multilingual translation service.\nThis is realized by wrapping the Translution translator as a Web service. \nTo use this service, learn the following Terms and Conditions.\nhttp://www.translution.com/about/licensing.aspx', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('KyotoUCLWT', 'TRANSLATION', 'WEB-Transer (Kyoto-U)', 'http://langrid.org/service_manager/invoker/KyotoUCLWT', 'ja2en,en2ja,ja2ko,ko2ja,ja2zh,zh2ja,en2de,de2en,en2fr,fr2en,en2it,it2en,en2es,es2en,en2pt,pt2en', 'off', 'Ishida and Matsubara Laboratory, Department of Social Informatics, Graduate School of Informatics, Kyoto University', 'Cross Language Inc.', '', 'WEB-Transer running on Kyoto-U server.', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('NICTCLWT', 'TRANSLATION', 'WEB-Transer (NICT)', 'http://langrid.org/service_manager/invoker/NICTCLWT', 'ja2en,en2ja,ja2ko,ko2ja,ja2zh,zh2ja,en2de,de2en,en2fr,fr2en,en2it,it2en,en2es,es2en,en2pt,pt2en', 'off', 'Language Infrastructure Group, National Institute of Information and Communications Technology', 'Cross Language Inc.', '', 'WEB-Transer running on NICT''s server', '', '', UNIX_TIMESTAMP(NOW()), '0'),
('YakushiteNet', 'TRANSLATION', 'YakushiteNet', 'http://langrid.org/service_manager/invoker/YakushiteNet', 'zh2ja,en2ja,ja2en', 'off', 'System Platform Center, Oki Electric Industry Co., Ltd.', '', '', 'This service provides multilingual translation service.\nThis is realized by wrapping YakushiteNet as a Web service.', '', '', UNIX_TIMESTAMP(NOW()), '0');


