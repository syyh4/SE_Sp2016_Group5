USE linkedin_group_5;




SELECT
		C.uid as cid, C.name as company_name, 
		P.uid as eid, P.firstname, P.lastname, P.birth_date, P.age
FROM
		person P, company C, company_employees CE
WHERE
		P.uid = CE.eid 
	AND
		C.uid = CE.cid
;

/*
SELECT 
	C.name, C.uid as cid, COUNT(*) as total_employees,
	TRUNCATE(MAX((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS max_emp_age,
	TRUNCATE((ABS(AVG(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS avg_emp_age,
	TRUNCATE(MIN((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS min_emp_age
FROM company C, person P, company_employees CE
WHERE CE.cid = C.uid AND CE.eid = P.uid
GROUP BY C.uid;

SELECT count(*) total_emp_count FROM company C, person P, company_employees CE WHERE C.uid = CE.cid AND P.uid = CE.eid;
*/
-- UPDATE person P SET birth_date = "1995-06-22" WHERE (ABS(DATEDIFF(P.birth_date, CURDATE()) / 365)) < 18;


/*

SELECT 
	C.name, C.uid, COUNT(*)
	TRUNCATE(MAX((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS max_emp_age,
	TRUNCATE((ABS(AVG(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS avg_emp_age,
	TRUNCATE(MIN((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS min_emp_age
FROM company C, person P, company_employees CE
WHERE CE.cid = C.uid AND CE.eid = P.uid
GROUP BY C.uid;
*/

/*
DROP VIEW user_person_view;

CREATE VIEW user_person_view AS (
	SELECT U.uid, U.username, U.email,
			P.firstname, P.middlename, P.lastname, P.birth_date, P.age, P.gender
			
	FROM user U, person P
	WHERE U.uid = P.uid
);

SELECT * FROM user_person_view LIMIT 10;
*/


/*
SELECT 
	C.uid as company_id, C.name as company_name,
	TRUNCATE((ABS(AVG(DATEDIFF(CE.start_date, CURDATE()))) / 365), 2) as avg_year_diff
FROM company_employees CE, company C
WHERE CE.cid = C.uid
GROUP BY cid;
*/

/*
SELECT 
	C.uid as company_id, C.name as company_name,
	TRUNCATE((ABS(AVG(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS avg_emp_age
FROM company C, company_employees CE, person P
WHERE CE.cid = C.uid AND CE.eid = P.uid
GROUP BY C.uid;
*/
/*


SELECT
	C.name ,COUNT(P.gender)
FROM company C, person P, company_employees CE
WHERE CE.cid = C.uid AND CE.eid = P.uid;


#	Get Employee Count
SELECT 
	C.name, C.uid,
	Count(CE.eid)
FROM company C, person P, company_employees CE
WHERE CE.cid = C.uid AND CE.eid = P.uid
GROUP BY C.uid;

#	Get Max age for each company
SELECT 
	C.name, C.uid,
	TRUNCATE(MAX((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS max_emp_age,
	TRUNCATE((ABS(AVG(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS avg_emp_age,
	TRUNCATE(MIN((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS min_emp_age
FROM company C, person P, company_employees CE
WHERE CE.cid = C.uid AND CE.eid = P.uid
GROUP BY C.uid;
*/



/*

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


select P.uid, P.firstname, P.birth_date, P.age FROM person P WHERE P.uid=1;

update person set birth_date="1993-06-22" WHERE uid = 1;

select P.uid, P.firstname, P.birth_date, P.age FROM person P WHERE P.uid=1;
*/
