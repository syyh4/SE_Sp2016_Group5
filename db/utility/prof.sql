USE linkedin_group_5;

DROP TABLE IF EXISTS user_profile_images;
CREATE TABLE user_profile_images (
	img_id			SERIAL,
	img_url			VARCHAR(2083),
	img_gender		VARCHAR(50),
	PRIMARY KEY (img_id)
);

LOAD DATA LOCAL INFILE '../../input_data/v3/person_profile_images.csv' INTO TABLE user_profile_images
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(img_url, img_gender);