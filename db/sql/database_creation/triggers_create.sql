USE linkedin_group_5;

DROP TRIGGER job_offer_posted_trigger;

delimiter #

CREATE TRIGGER job_offer_posted_trigger AFTER INSERT ON job_offer
FOR EACH ROW
BEGIN 
	INSERT INTO company_feed_items (cid, feed_type, feed_body, offer_id) VALUES (new.company_id, "offer_posting", "There's a new job offer!", new.offer_id);
END#

delimiter ;




DELIMITER //
DROP TRIGGER IF EXISTS upd_person_age //
CREATE TRIGGER upd_person_age BEFORE INSERT ON person
FOR EACH ROW
BEGIN
	SET @age = TRUNCATE((ABS(DATEDIFF(new.birth_date, CURDATE())) / 365), 2);
	
	SET new.age = @age;
END;//

DELIMITER ;

DELIMITER //
DROP TRIGGER IF EXISTS upd_person_age //
CREATE TRIGGER upd_person_age BEFORE UPDATE ON person
FOR EACH ROW
BEGIN
	SET @age = TRUNCATE((ABS(DATEDIFF(NEW.birth_date, CURDATE())) / 365), 2);
	
	SET NEW.age = @age;
END;//

DELIMITER ;
