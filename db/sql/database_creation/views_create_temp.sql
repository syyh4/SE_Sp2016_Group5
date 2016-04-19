USE linkedin_group_5;



CREATE VIEW user_person_view AS (
	SELECT U.uid, U.username, U.email, P.firstname, P.middlename, P.lastname, P.birth_date, P.gender
	FROM user U, person P
	WHERE U.uid = P.uid
);