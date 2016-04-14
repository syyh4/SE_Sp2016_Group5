/*
	Filename:		views_create.sql
	
	Purpose:		To create the main views to be used in queries
	
	Last Modified:	4/14/2016 by Anthony Forsythe	
*/
USE linkedin_group_5;

DROP VIEW IF EXISTS company_location_view;

CREATE VIEW company_location_view 
AS 
  (SELECT C.uid        AS company_id, 
          C.name       AS company_name, 
          L.lid        AS location_id, 
          L.coord_lat  AS latitude, 
          L.coord_long AS longitude, 
          L.street_address, 
          L.city, 
          L.state, 
          L.country, 
          L.zip 
   FROM   company C, 
          location L 
   WHERE  C.lid = L.lid); 