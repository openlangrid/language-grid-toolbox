CREATE TABLE shop_questions (
	shop_question_id	INT UNSIGNED  AUTO_INCREMENT NOT NULL,
	qa_question_id		INT UNSIGNED NOT NULL,
	available			CHAR(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (shop_question_id)
) ENGINE=MyISAM;


CREATE TABLE shop_answers (
	shop_answer_id	INT UNSIGNED  AUTO_INCREMENT NOT NULL,
	qa_question_id	INT UNSIGNED NOT NULL,
	qa_answer_id	VARCHAR(100) NOT NULL,
	available		CHAR(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (shop_answer_id)
) ENGINE=MyISAM;


CREATE TABLE shop_answer_contents (
	shop_answer_content_id	INT UNSIGNED  AUTO_INCREMENT NOT NULL,
	shop_answer_id			INT UNSIGNED NOT NULL,
	content_title      		VARCHAR(256) NOT NULL,
	permission				CHAR(1) NOT NULL DEFAULT 0,
	created    				DATETIME,
	content_type       		VARCHAR(20),
	file_id					INT UNSIGNED NOT NULL,
    image_file_name   VARCHAR(256),
    image_mimetype    VARCHAR(30),
    image_data        LONGBLOB,
    image_width       MEDIUMINT UNSIGNED,
    image_height      MEDIUMINT UNSIGNED,
	original_url			TEXT,
    latitude             	FLOAT NOT NULL,
    longitude            	FLOAT NOT NULL,
    zoom                 	MEDIUMINT UNSIGNED  NOT NULL,
    map_type             	CHAR(1),
    start_addr_latitude  	FLOAT NOT NULL,
    start_addr_longitude 	FLOAT NOT NULL,
    start_location       	VARCHAR(256),
    end_addr_latitude    	FLOAT NOT NULL,
    end_addr_longitude   	FLOAT NOT NULL,
    end_location         	VARCHAR(256),
    travel_mode          	CHAR(1),    
    route_select         	MEDIUMINT UNSIGNED,
	
	PRIMARY KEY (shop_answer_content_id)
) ENGINE=MyISAM;


CREATE TABLE associate_glossaries (
    associate_glossary_id   INT NOT NULL AUTO_INCREMENT,
    resource_name            VARCHAR(255) NOT NULL,
    dictionary_name         VARCHAR(255) NOT NULL,
    create_date             INT NOT NULL,

    CONSTRAINT PRIMARY KEY (associate_glossary_id)
) ENGINE=MyISAM;
