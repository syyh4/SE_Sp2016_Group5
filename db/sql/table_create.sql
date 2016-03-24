#	Drop the database so that table creation can start anew
DROP DATABASE IF EXISTS linkedin_group_5;

#	Create the database anew
CREATE DATABASE linkedin_group_5;
USE DATABASE linkedin_group_5;


CREATE TABLE user (
	uid			SERIAL,
	username	varchar(256),
	email		varchar(320),
	PRIMARY KEY (uid)
);

CREATE TABLE user_authentication (
	uid			BIGINT UNSIGNED,
	password_hast char(40) NOT NULL,
	salt			char(40) NOT NULL,
	PRIMARY KEY (uid),
	FOREIGN KEY (uid) REFERENCES user(uid) ON DELETE CASCADE
);

CREATE TABLE person (
	uid			BIGINT UNSIGNED,
	firstname	varchar(200),
	middlename	varchar(200),
	lastname	varchar(200),
	birth_date	DATE,
	PRIMARY KEY (uid),
	FOREIGN KEY (uid) REFERENCES user(uid) ON DELETE CASCADE
);



CREATE TABLE location (
	lid				SERIAL,
	coord_long		DECIMAL(9,6),
	coord_lat		DECIMAL(9,6),
	street_address	varchar(300),
	country			varchar(300),
	state			varchar(300),
	zip				char(5),
	city			varchar(300),
	PRIMARY KEY (lid)
);

CREATE TABLE company (
	uid		BIGINT UNSIGNED,
	lid		BIGINT UNSIGNED,
	name	varchar(300),
	PRIMARY KEY (uid),
	FOREIGN KEY (uid) REFERENCES user(uid) ON DELETE CASCADE,
	FOREIGN KEY (lid) REFERENCES location(lid),
);

CREATE TABLE person_location (
	lid				BIGINT UNSIGNED,
	pid				BIGINT UNSIGNED,
	from_date		DATE NOT NULL,
	to_date			DATE,
	curr_res		BOOLEAN NOT NULL DEFAULT FALSE,
	PRIMARY KEY (lid, pid, from_date),
	FOREIGN KEY (lid) REFERENCES location(lid),
	FOREIGN KEY (pid) REFERENCES user(uid)
);

CREATE TABLE company_employees (
	eid				BIGINT UNSIGNED,	# The ID of the employee
	cid				BIGINT UNSIGNED,	# The ID of the company
	start_date		DATE,
	end_date		DATE,
	PRIMARY KEY (eid, cid),
	FOREIGN KEY (eid) REFERENCES user(uid),
	FOREIGN KEY (cid) REFERENCES user(uid)
);

