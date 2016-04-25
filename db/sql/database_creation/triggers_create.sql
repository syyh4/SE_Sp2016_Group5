USE linkedin_group_5;

DROP TRIGGER job_offer_posted_trigger;

delimiter #

CREATE TRIGGER job_offer_posted_trigger AFTER INSERT ON job_offer
FOR EACH ROW
BEGIN 
	INSERT INTO company_feed_items (cid, feed_type, feed_body, offer_id) VALUES (new.company_id, "offer_posting", "There's a new job offer!", new.offer_id);
END#

delimiter ;