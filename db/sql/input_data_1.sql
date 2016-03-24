USE linkedin_group_5;

LOAD DATA INFILE '../input_data/user_input_v1.csv' INTO TABLE user FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' (username, email);
