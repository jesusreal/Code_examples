CREATE TABLE users (
id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
firstName VARCHAR( 50 ) NOT NULL ,
lastName VARCHAR( 50 ) NOT NULL ,
username VARCHAR( 50 ) NOT NULL ,
email VARCHAR( 100 ) NOT NULL ,
password VARCHAR( 50 ) NOT NULL,
role VARCHAR( 6 ) NOT NULL DEFAULT 'guest' REFERENCES roles(role),
activationCode VARCHAR(50) NOT NULL,
active BOOLEAN DEFAULT 0
);

-- CREATE INDEX "id" ON "users" ("id");

CREATE TABLE roles (
  role    CHAR(1)       PRIMARY KEY NOT NULL,
  Seq     INTEGER
);
INSERT INTO roles(role, Seq) VALUES ('guest',1);
INSERT INTO roles(role, Seq) VALUES ('member',2);
INSERT INTO roles(role, Seq) VALUES ('admin',3);



CREATE TABLE books (
id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
asin CHAR( 10 ) NOT NULL,
ean CHAR(13), 
title VARCHAR( 100 ) NOT NULL ,
content TEXT NULL,
edition CHAR(1),
publisher varchar(40),
publicationDate CHAR(10),
numberOfPages varchar(4),
formattedPrice VARCHAR(10),
detailPageUrl text,
imageUrl VARCHAR( 200 )
);

CREATE TABLE loans (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(100) NOT NULL DEFAULT 'noemail@test.com',
	asin CHAR( 10 ) NOT NULL,
    overdue DATETIME NOT NULL,
    mailSent BOOLEAN DEFAULT 0
);