/*
	LOGIN PAGE
*/

#
#	Query Title
#		Authenticate User
#	
#	Values Passed:
#		$1 = The username entered 
#
#	Values Returned:
#		hash	=	The password hash for this user
#		salt	= 	The salt that was used to create this password hash
#
#	Purpose
#		This query will pull the password hash and salt from the 'user_authentication' for the
#		user with the specified username. Once these values are obtained there will be some PHP
#		logic that will determine if the plaintext password can be hashed with the salt value obtained
#		to match the hash value. If so the credentials are correct and they can be routed to the home page.
#
SELECT password_hash AS hash, 
       salt 
FROM   user_authentication 
WHERE  uid = (SELECT uid 
              FROM   USER 
              WHERE  username = $1); 


/*
	HOME PAGE
*/

#
#	Query Title
#		Get Activity Feed Items for User
#
#	Values Passed
#		$1	=	The users UID value (obtained from a session variable)
#	
#	Values Returned
#		-	A list of records each containing the following information:
#			-	Feed Item ID
#			-	Feed Poster UID
#			-	Date the feed item was posted
#			-	The type of feed item
#			-	The body of the feed item
#
#	Purpose
#		To pull all of the feed items from the organizations that the user has subscribed to
#
SELECT UF.feed_item_id AS feed_item_id, 
       UF.poster_id    AS poster_id, 
       UF.date_posted  AS date_posted, 
       UF.feed_type    AS feed_type, 
       UF.feed_body    AS feed_body 
FROM   user_feed UF 
WHERE  UF.poster_id IN (SELECT CFS.company_id 
                        FROM   company_feed_subscription CFS 
                        WHERE  CFS.user_id = $1); 

#
#	Query Title
#		Get Job Offer Suggestions for User
#
#	Values Passed
#		$1	=	The UID of the user
#
#	Values Returned
#		-	A list of job suggestions each containing the following information
#			-	The date the suggestion was calculated
#			-	The position's 'posid' value
#			-	The name of the position
#			-	A description of the position
#			-	If the offer allows the applicant to work remotely
#			-	The name of the company offering the position
#
#	Purpose
#		This query will return all the information needed to display job offer suggestions for the user
#		
SELECT   P.posid           AS position_id, 
         P.NAME            AS position_name, 
         P.description     AS position_description, 
         P.thumb_image_url AS position_image_url, 
         C.uid             AS company_id, 
         C.NAME            AS company_name, 
         JO.offer_id       AS job_offer_id, 
         JO.salalary_low   AS offer_salary_low, 
         JO.salary_high    AS offer_salary_high, 
         OS.date_posted    AS suggestion_date, 
FROM     user_job_offer_suggestion OS 
JOIN     job_offer JO 
ON       OS.offer_id = JO.offer_id 
JOIN     company C 
ON       C.uid = JO.cid 
JOIN     position P 
ON       P.posid = JO.posid 
WHERE    OS.uid = $1 
ORDER BY OS.date_posted DESC limit 5;



#
#	Query Title
#		Get Watched Job Offers for User
#
#	Values Passed
#		$1	=	The UID of the user
#
#	Values Returned
#		-	A list of job offers each containing the following information
#			-	The date the suggestion was calculated
#			-	The position's 'posid' value
#			-	The name of the position
#			-	A description of the position
#			-	If the offer allows the applicant to work remotely
#			-	The name of the company offering the position
#
#	Purpose
#		This query will return all the information needed to display the watched job offers for the user
#		
SELECT   P.posid			AS position_id, 
         P.NAME				AS position_name, 
         P.description		AS position_description, 
         P.thumb_image_url	AS position_image_url, 
         C.uid				AS company_id, 
         C.NAME				AS company_name, 
         JO.offer_id		AS job_offer_id, 
         JO.salalary_low	AS offer_salary_low, 
         JO.salary_high    	AS offer_salary_high, 
		 JOF.date_favorited	AS suggestion_date, 
FROM     user_job_offer_favorites JOF
JOIN     job_offer JO 
ON       JOF.offer_id = JO.offer_id 
JOIN     company C 
ON       C.uid = JO.cid 
JOIN     position P 
ON       P.posid = JO.posid 
WHERE    JOF.uid = $1
ORDER BY JOF.date_posted DESC limit 5;



#	
#	Query Title
#		Get Skills Required By Job Offer
#
#	Values Passed
#		$1	=	The offer_id of the job offer
#
#	Values Returned
#		-	A list of of records each containing the following information:
#			-	The job offer's offer_id
#			-	The skill's skill_id
#			-	The name of the skill
#			-	The aptitude level of the skill required by the job offer
#
SELECT rs.offer_id AS job_offer_id, 
       rs.skill_id AS skill_id, 
       s.NAME, 
       rs.aptitude_level 
FROM   job_offer_required_skills rs, 
       skill s 
WHERE  rs.offer_id = $1 
ORDER  BY rs.aptitude_level DESC; 


/*
	ORGANIZATION PAGE
*/

#
#	Query Title
#		Get Basic Organization Information
#
#	Values Passed
#		$1	= 	The organizations UID value (obtained from a parameter in the URL)
#
#	Values Returned
#		-	A single record containing all of the basic information for this particular organization
#
#	Purpose
#		Once the user lands on a page for some organization this query will pull all of the relevant information
#		for that organization including:
#			-	Name
#			-	Description
#			-	Location
#				-	GPS Coordinates
#				-	Address ( Street Address, City, State, Country, Zip )
#
SELECT L.lid            AS location_id, 
       L.coord_lat      AS latitude, 
       L.coord_long     AS longitude, 
       L.street_address AS street_address, 
       L.country        AS country, 
       L.state          AS state, 
       L.zip            AS zip_code, 
       L.city           AS city, 
       C.uid            AS company_id, 
       C.NAME           AS NAME, 
       C.description    AS description 
FROM   company C, 
       location L 
WHERE  C.lid = L.lid 
       AND C.uid = $1; 
       
       
#
#	Query Title
#		Get Job Offers For Company
#
#	Values Passed
#		$1	=	The company's UID value
#
#	Values Returned
#		-	Multiple records (0 to n) each with the following job offer and position information
#			-	offer_id		(the id of the offer)
#			-	pos_id			(the id of the position that the offer is for)
#			-	pos_name		(the name of the position that the offer is for)
#			-	pos_img_url		(the url of the image for this position)
#			-	date_posted		(the date this job offer was posted)
#			-	salary_low		(the low end value for this offer's salary)
#			-	salary_high		(the high end value for this offer's salary)
#			-	can_work_remote	(boolean value that indicates if the applicant can work remotely from home)
#
#	Purpose
#		This query will return all the job offers that the company currently has. Inactive ones will not be listed.
#
SELECT JO.offer_id        AS offer_id, 
       P.posid            AS position_id, 
       P.NAME             AS position_name, 
       P.description      AS position_description, 
       JO.description     AS job_offer_description, 
       P.thumb_image_url  AS pos_img_url, 
       JO.date_posted     AS date_offer_posted, 
       JO.salary_low      AS offer_sal_low, 
       JO.salary_high     AS offer_sal_high, 
       JO.can_work_remote AS offer_can_work_remote 
FROM   job_offer JO, 
       pos AS P 
WHERE  JO.position_id = P.posid 
       AND JO.cid = $1; 

