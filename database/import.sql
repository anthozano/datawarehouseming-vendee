LOAD DATA LOCAL INFILE "F:/bdd/tp7/datamining-vendee/resources/raw/deces_L3.csv"
INTO TABLE `vendee`.`raw_deces`
FIELDS TERMINATED BY ',' ENCLOSED BY '' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE "F:/bdd/tp7/datamining-vendee/resources/raw/mariages_L3.csv"
INTO TABLE `vendee`.`raw_mariage`
FIELDS TERMINATED BY ',' ENCLOSED BY '' LINES TERMINATED BY '\n';
