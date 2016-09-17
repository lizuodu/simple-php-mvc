CREATE TABLE tbl_user
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	username VARCHAR(128) NOT NULL,
	password VARCHAR(128) NULL,
	email VARCHAR(128) NOT NULL,
	website VARCHAR(500) NULL,
	profile TEXT
);

CREATE TABLE tbl_post
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	title VARCHAR(128) NOT NULL,
	content TEXT NOT NULL,
	tags TEXT,
	status INTEGER NOT NULL,
	create_time VARCHAR(20),
	update_time VARCHAR(20),
	author_id INTEGER NOT NULL,
	CONSTRAINT FK_post_author FOREIGN KEY (author_id)
		REFERENCES tbl_user (id) ON DELETE CASCADE ON UPDATE RESTRICT
);

CREATE TABLE tbl_comment
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	content TEXT NOT NULL,
	status INTEGER NOT NULL,
	create_time VARCHAR(20),
	author VARCHAR(128) NOT NULL,
	email VARCHAR(128) NOT NULL,
	url VARCHAR(128),
	post_id INTEGER NOT NULL,
	CONSTRAINT FK_comment_post FOREIGN KEY (post_id)
		REFERENCES tbl_post (id) ON DELETE CASCADE ON UPDATE RESTRICT
);

CREATE TABLE tbl_tag
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	name VARCHAR(128) NOT NULL,
	frequency INTEGER DEFAULT 1
);


INSERT INTO tbl_user (username, password, email, website, profile) VALUES ('lizuodu','$2a$10$JTJf6/XqC94rrOtzuF397OHa4mbmZrVTBOQCmYD9U.obZRUut4BoC','lizuodu@163.com','http://lizuodu.com','root');



