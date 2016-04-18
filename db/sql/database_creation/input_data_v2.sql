# SELECT THE PROPER DATABASE TO USE
USE linkedin_group_5;



#	LOAD DATA INTO THE USERS TABLE
LOAD DATA LOCAL INFILE '../../input_data/v3/users.csv' INTO TABLE user
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(username, email);


#	Load DATA INTO THE PERSONS TABLE
LOAD DATA LOCAL INFILE '../../input_data/v3/person.csv' INTO TABLE person
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(uid, firstname, middlename, lastname, @birth_date_variable, gender)
SET birth_date = STR_TO_DATE(@birth_date_variable, '%c/%e/%Y');

show warnings\G;

#	LOAD LOCATION DATA
LOAD DATA LOCAL INFILE '../../input_data/v3/locations.csv' INTO TABLE location
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(coord_lat, coord_long, street_address, country, state, zip, city);

#	LOAD COMPANY DATA
LOAD DATA LOCAL INFILE '../../input_data/v3/company.csv' INTO TABLE company 
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(uid, lid, name, description);

show warnings\G;