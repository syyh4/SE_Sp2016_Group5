USE linkedin_group_5;


DROP VIEW user_person_view;

CREATE VIEW user_person_view AS (
	SELECT U.uid, U.username, U.email, P.firstname, P.middlename, P.lastname, P.birth_date, P.gender
	FROM user U, person P
	WHERE U.uid = P.uid
);

DROP TABLE company_feed_items;
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