/*
	Filename:		views_create.sql
	
	Purpose:		To create the main views to be used in queries
	
	Last Modified:	4/14/2016 by Anthony Forsythe	
*/
USE linkedin_group_5;

DROP VIEW IF EXISTS company_location_view;
CREATE VIEW company_full_view 
AS 
  (SELECT C.uid        AS company_id, 
			U.prof_image AS company_image,
          C.name       AS company_name, 
          C.description AS company_description,
          L.lid        AS location_id, 
          L.coord_lat  AS latitude, 
          L.coord_long AS longitude, 
          L.street_address, 
          L.city, 
          L.state, 
          L.country, 
          L.zip 
   FROM   company C, 
          location L,
          user U
   WHERE  C.lid = L.lid AND C.uid = U.uid
   ); 
   
   
DROP VIEW IF EXISTS company_employees_view;
CREATE VIEW company_employees_view AS
(
	SELECT 
		C.name, C.uid as cid, COUNT(*) as total_employees,
		TRUNCATE(MAX((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS max_emp_age,
		TRUNCATE((ABS(AVG(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS avg_emp_age,
		TRUNCATE(MIN((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS min_emp_age
	FROM company C, person P, company_employees CE
	WHERE CE.cid = C.uid AND CE.eid = P.uid
	GROUP BY C.uid
);


DROP VIEW IF EXISTS employees_view;
CREATE VIEW employees_view AS 
(
	SELECT
		C.uid as cid, C.name as company_name, 
		P.uid as eid, P.firstname, P.lastname, P.birth_date, P.age, U.prof_image as emp_image, P.gender
	FROM
		person P, company C, company_employees CE, user U
	WHERE
		P.uid = CE.eid 
	AND
		C.uid = CE.cid
	AND 
		U.uid = P.uid
);


DROP VIEW IF EXISTS user_person_view;
CREATE VIEW user_person_view AS (
	SELECT U.uid, U.username, U.email, U.prof_image,
			P.firstname, P.middlename, P.lastname, P.birth_date, P.age, P.gender
			
	FROM user U, person P
	WHERE U.uid = P.uid
);


DROP VIEW IF EXISTS individual_employees_view;
CREATE VIEW individual_employees_view AS (
	SELECT 	C.uid as company_id, C.name as company_name,
			P.uid as employee_id, P.firstname, P.middlename, P.lastname, P.birth_date, P.age, P.gender
	FROM	company C, person P, company_employees CE
	WHERE	CE.cid = C.uid 
		AND
			CE.eid = P.uid
);
