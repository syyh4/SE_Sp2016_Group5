USE linkedin_group_5;

LOAD DATA LOCAL INFILE '../input_data/user_input_v1.csv' INTO TABLE user FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' (username, email);


LOAD DATA LOCAL INFILE '../input_data/people_input_v1.csv' INTO TABLE person IGNORE 1 LINES FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' (uid,firstname, middlename,lastname);