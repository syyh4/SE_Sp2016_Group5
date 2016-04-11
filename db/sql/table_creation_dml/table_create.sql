#	Drop the database so that table creation can start anew
DROP DATABASE IF EXISTS linkedin_group_5;

#	Create the database anew
CREATE DATABASE linkedin_group_5;
USE linkedin_group_5;


CREATE TABLE user (
	uid			SERIAL,
	username	varchar(256),
	email		varchar(320),
	PRIMARY KEY (uid)
);

CREATE TABLE user_authentication (
	uid				BIGINT UNSIGNED,
	password_hash 	char(40) NOT NULL,
	salt			char(40) NOT NULL,
	PRIMARY KEY (uid),
	FOREIGN KEY (uid) REFERENCES user(uid) ON DELETE CASCADE
);

CREATE TABLE person (
	uid			BIGINT UNSIGNED,
	gender		varchar(40),
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
	uid			BIGINT UNSIGNED,
	lid			BIGINT UNSIGNED,
	name		VARCHAR(300),
	description VARCHAR(500),
	PRIMARY KEY (uid),
	FOREIGN KEY (uid) REFERENCES user(uid) ON DELETE CASCADE,
	FOREIGN KEY (lid) REFERENCES location(lid)
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

CREATE TABLE skill (
	skill_id		SERIAL,
	name			VARCHAR(100),
	skill_type		VARCHAR(100),
	description		VARCHAR(300),	
	PRIMARY KEY (skill_id)
);

CREATE TABLE pos (
	posid			SERIAL,
	name			VARCHAR(100),
	description		VARCHAR(100),
	thumb_image_url	VARCHAR(100),
	PRIMARY KEY (posid)
);

CREATE TABLE job_offer (
	offer_id		SERIAL,
	position_id		BIGINT UNSIGNED,
	date_posted		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	description		VARCHAR(300),
	company_id		BIGINT UNSIGNED,
	salary_low		REAL NOT NULL DEFAULT 0.0,
	salary_high		REAL NOT NULL DEFAULT 0.0,
	salary_median	REAL NOT NULL DEFAULT 0.0,
	can_work_remote	BOOLEAN NOT NULL,
	active			BOOLEAN NOT NULL,
	PRIMARY KEY (offer_id),
	FOREIGN KEY (position_id) REFERENCES pos(posid),
	FOREIGN KEY (company_id) REFERENCES user(uid)	
);

CREATE TABLE job_offer_acceptance (
	offer_id		BIGINT UNSIGNED,
	user_id			BIGINT UNSIGNED,
	date_accepted	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (offer_id, user_id, date_accepted)
	
);

#
#	Table Name
#		company_feed_subscription
#
#	Columns
#		-	company_id		(This references a company's UID from the user table)
#		-	user_id			(This references a person's UID from the user table)
#
#	Purpose
#		This table keeps track of a user's subscription to a company
#	
CREATE TABLE company_feed_subscription (
	company_id			BIGINT UNSIGNED,
	user_id				BIGINT UNSIGNED,
	PRIMARY KEY (company_id, user_id),
	FOREIGN KEY (company_id) REFERENCES user(uid),
	FOREIGN KEY (user_id) REFERENCES user(uid)
);


#
#	Table Name
#		user_feed
#	
#	Columns
#		-	feed_item_id	(This is the autoincrementing primary key for the user_feed table)
#		-	poster_id		(This is the user that posted this feed item. It references the user's UID from the user table)
#		-	date_posted		(This is the date the feed item was posted. It defaults to the date the record was inserted)
#		-	feed_item_type	(This is the type of the feed item. Possible values are: 'n/a' , 'job_offer' , 'status_update')
#		-	feed_body		(This is the main body of the feed item)
#	
#	Purpose
#		This table contains feed items (made either by the user manually posting a status update or automatically by some activity of the user)
#
CREATE TABLE user_feed (
	feed_item_id		SERIAL,
	poster_id			BIGINT UNSIGNED,
	date_posted			TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	feed_type			VARCHAR(100) NOT NULL DEFAULT 'n/a',
	feed_body			VARCHAR(500),
	PRIMARY KEY (feed_item_id),
	FOREIGN KEY (poster_id) REFERENCES user(uid),
	
)