USE linkedin_group_5;


#	Get the total number of employees (Global)

SELECT count(*) total_emp_count FROM company C, person P, company_employees CE WHERE C.uid = CE.cid AND P.uid = CE.eid;


#	Get the total number of employees (by Company)

SELECT 
	C.name, C.uid as cid, COUNT(*) as total_employees,
	TRUNCATE(MAX((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS max_emp_age,
	TRUNCATE((ABS(AVG(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS avg_emp_age,
	TRUNCATE(MIN((ABS(DATEDIFF(P.birth_date, CURDATE()))) / 365), 2) AS min_emp_age
FROM company C, person P, company_employees CE
WHERE CE.cid = C.uid AND CE.eid = P.uid
GROUP BY C.uid;


#	Get age range (Global)

SELECT 
	count(*) as emp_count
FROM 
	employees_view
WHERE
		cid = 2
	AND
		age >= 18
	AND
		age < 25