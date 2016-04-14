#	Create the tables
source ./table_create.sql;

#	Update the user permissions
#	--
#		Currently Broken
#	--
#	source ./permissions_set.sql;

#	Load the input data
source ./input_data_v2.sql;