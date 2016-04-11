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

/*
	There should be queries here to pull feed information

		-	Fetch activity feed for user
		-	Fetch suggested job offers
		-	Fetch watched job offers
*/




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
#		$1	=	A single record containing all of the basic information for this particular organization
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
