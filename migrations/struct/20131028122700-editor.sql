
CREATE TABLE translation (
	id int NOT NULL AUTO_INCREMENT,
	language_id varchar(5) NOT NULL,
	idf text NOT NULL COLLATE utf8_bin,
	translation text NOT NULL,
	UNIQUE (language_id, idf(100)),
	PRIMARY KEY (id)
);
