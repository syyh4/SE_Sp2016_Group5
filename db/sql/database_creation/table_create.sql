/*
	File Name:			table_create.sql
	
	Last Major Update:	4/11/2016 by Anthony Forsythe
	
	Todo List:
		-	Add remaining table descriptions	
*/


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



#	
#	Table Name
#		person_location
#
#	Columns 
#		-	lid		(This references a location's lid from the location table)
#		-	pid		(This references a person's uid from the user table)
#		-	from_date	(This is the date the person began living at this location)
#		-	to_date		(This is the date the person stopped living at this location)
#		-	curr_res	(This is a boolean value indicating whether this is the persons current location or not)
#	
#	Primary Key
#		-	lid & pid & from_date (This primary key is a combination of the lid, pid, and from_date)
#
#	Purpose
#		This table lists all the locations (including the current location) that a person resides at
#
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



#
#	Table Name
#		company_employees
#
#	Columns
#		-	posid			(This is the autoincrementing primary key for the table)
#		-	name			(This is the name of the position)
#		-	description		(This is the description of the position)
#		-	thumb_image_url	(This is the url of the thumbnail image for this position)
#		
#	Primary Key
#		-	posid
#
#	Purpose
#		This table lists all of the empoyees that work at a specific company
#
CREATE TABLE company_employees (
	eid				BIGINT UNSIGNED,
	cid				BIGINT UNSIGNED,
	start_date		DATE,
	end_date		DATE,
	PRIMARY KEY (eid, cid),
	FOREIGN KEY (eid) REFERENCES user(uid),
	FOREIGN KEY (cid) REFERENCES user(uid)
);



#
#	Table Name
#		skill
#
#	Columns
#		-	skill_id		(This is the autoincrementing primary key for the table)
#		-	name			(This is the name of the skill)
#		-	skill_type		(This is the type of skill... e.g 'Software Development' , 'Data Analysis', etc.)
#		-	description		(This is the description of the skill)
#		
#	Primary Key
#		-	skill_id
#
#	Purpose
#		This table contains all of the possible workplace skills 
#	
CREATE TABLE skill (
	skill_id		SERIAL,
	name			VARCHAR(100),
	skill_type		VARCHAR(100),
	description		VARCHAR(300),	
	PRIMARY KEY (skill_id)
);



#
#	Table Name
#		pos		(This table was named 'pos' instead of 'position' because 'position' is some function or whatever and kept throwing errors)
#
#	Columns
#		-	posid			(This is the autoincrementing primary key for the table)
#		-	name			(This is the name of the position)
#		-	description		(This is the description of the position)
#		-	thumb_image_url	(This is the url of the thumbnail image for this position)
#		
#	Primary Key
#		-	posid
#
#	Purpose
#		
CREATE TABLE pos (
	posid			SERIAL,
	name			VARCHAR(100),
	description		VARCHAR(100),
	thumb_image_url	VARCHAR(100),
	PRIMARY KEY (posid)
);



#
#	Table Name
#		job_offer
#
#	Columns
#		-	offer_id		(The autoincrementing primary key for this table)
#		-	position_id		(This references a position's posid from the pos [position] table)
#		-	date_posted		(This is the date the job offer was posted. It defaults to the date the record was inserted into this table)
#		-	description		(This is a description of the job offer)
#		-	company_id		(This references a company's uid from the user table)
#		-	salary_low		(This is the low end value for the offer's salary)
#		-	salary_high		(This is the high end value for the offer's salary)
#		-	salary_median	(This is the median value for the salary)
#		-	can_work_remote	(This is a boolean value that indicates whether the applicant can work from home or not)
#		-	active			(This is a boolean value that indicates whether the job offer is still active or not)
#
#	Primary Key
#		-	offer_id
#	
#	Purpose
#		This table keeps track of the job offers that companies post
#
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



#
#	Table Name
#		job_offer_acceptance
#
#	Columns
#		-	offer_id		(This references the job offer's offer_id from the job_offer table)
#		-	user_id			(This references the user's uid from the user table)
#		-	date_accepted	(This is the date the job offer was accepted by the user)
#
#	Primary Key
#		-	offer_id & user_id	(The primary key is a combination of the offer_id and the user_id)
#
#	Purpose
#		This table keeps track of job offers that have been accepted by users
#
CREATE TABLE job_offer_acceptance (
	offer_id		BIGINT UNSIGNED,
	user_id			BIGINT UNSIGNED,
	date_accepted	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (offer_id, user_id, date_accepted),
	FOREIGN KEY (offer_id) REFERENCES job_offer(offer_id),
	FOREIGN KEY (user_id) REFERENCES user(uid)
);



#
#	Table Name
#		company_feed_subscription
#
#	Columns
#		-	company_id		(This references a company's UID from the user table)
#		-	user_id			(This references a person's UID from the user table)
#
#	Primary Key
#		-	company_id & user_id	(The primary key is a combination of the uid of the company and the uid of the user)
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
#	Primary Key
#		-	feed_item_id
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
);



#
#	Table Name
#		user_job_offer_favorites
#	
#	Columns
#		-	user_id		(References a person's uid from the user table)
#		-	offer_id	(References a job offer's offer_id from the job_offer table)
#	
#	Primary Key
#		-	user_id & offer_id	(The primary key is a combination of the user_id and offer_id)
#
#	Purpose
#		This table keeps track of what job offer a user has favorited
#
CREATE TABLE user_job_offer_favorites (
	user_id			BIGINT UNSIGNED,
	offer_id		BIGINT UNSIGNED,
	date_favorited	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (user_id, offer_id),
	FOREIGN KEY (user_id) REFERENCES user(uid),
	FOREIGN KEY (offer_id) REFERENCES job_offer(offer_id)
);



#
#	Table Name
#		user_job_offer_suggestion
#
#	Columns
#		-	user_id		(References a person's uid from the user table)
#		-	offer_id	(References a job offer's offer_id from the job_offer table)
#		-	date_posted	(This is the date this suggestion was added to the table)
#	
#	Primary Key
#		-	user_id & offer_id & date_posted (The primary key is a combination of these three attributes)
#
#	Purpose
#		This table will hold all of the job offer suggestions that are calculated by a program that will run
#		every night or so
#
CREATE TABLE user_job_offer_suggestion (
	user_id			BIGINT UNSIGNED,
	offer_id		BIGINT UNSIGNED,
	date_posted		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (user_id, offer_id, date_posted),
	FOREIGN KEY (user_id) REFERENCES user(uid),
	FOREIGN KEY (offer_id) REFERENCES job_offer(offer_id)
);



#
#	Table Name
#		job_offer_required_skills
#
#	Columns
#		-	offer_id		(This references a job offer's offer_id from the job_offer table)
#		-	skill_id		(This references the skill's skill_id from the skill table. It is the skill that is required for this job offer)
#		-	aptitude_level	(This is the aptitude level of the skill required for this job. It can be a value between 1 and 5)
#	
#	Primary Key
#		-	offer_id & skill_id	(The primary key is a combination of the offer_id and the skill_id)
#
#	Purpose
#		This table contains the skills that are required for a particular job offer
#
CREATE TABLE job_offer_required_skills (
	offer_id		BIGINT UNSIGNED,
	skill_id		BIGINT UNSIGNED,
	aptitude_level	SMALLINT UNSIGNED NOT NULL DEFAULT 1,
	PRIMARY KEY (offer_id, skill_id),
	FOREIGN KEY (offer_id) REFERENCES job_offer(offer_id),
	FOREIGN KEY (skill_id) REFERENCES skill(skill_id),
	CONSTRAINT chk_aptitude_level CHECK (aptitude_level > 0 AND aptitude_level <= 5)
);


#
#	Table Name
#		user_auth_tokens
#
#	Columns
#		-	token_id		(This is the primary key of the token)
#		-	issued_to_id	(This is the id of the user that the token was issued to)
#		-	issue_time		(This is the date the token was issued)
#		-	expire_time		(This is the time the token expires)
#
#	Primary Key
#		-	token_id
#
#	Purpose
#		This table will hold the tokens for the REST API
CREATE TABLE user_auth_tokens (
	token_id			SERIAL,
	token				CHAR(64),
	issued_to			BIGINT UNSIGNED,
	issue_time			TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	expire_time			TIMESTAMP,
	PRIMARY KEY (token_id),
	FOREIGN KEY (issued_to) REFERENCES user(uid),
	UNIQUE (token)
);

CREATE TABLE company_feed_items (
	feed_item_id		SERIAL,
	cid					BIGINT UNSIGNED,
	feed_type			VARCHAR(100),
	date_posted			TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	feed_body			VARCHAR(500),
	offer_id			BIGINT UNSIGNED,
	PRIMARY KEY (feed_item_id),
	FOREIGN KEY (cid) REFERENCES company(uid),
	FOREIGN KEY (offer_id) REFERENCES job_offer(offer_id)
);