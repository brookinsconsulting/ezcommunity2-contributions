#
# Table structure for table 'eZExample_Test'
#
DROP TABLE IF EXISTS eZExample_Test;
CREATE TABLE eZExample_Test (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Text varchar(100),
  PRIMARY KEY (ID)
);
