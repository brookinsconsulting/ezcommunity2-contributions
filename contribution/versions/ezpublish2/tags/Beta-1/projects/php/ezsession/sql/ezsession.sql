#
# Table structure for table 'eZSession_Session'
#
DROP TABLE IF EXISTS eZSession_Session;
CREATE TABLE eZSession_Session (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Hash char(33),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZSession_SessionVariable'
#
DROP TABLE IF EXISTS eZSession_SessionVariable;
CREATE TABLE eZSession_SessionVariable (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  SessionID int(11),
  Name char(25),
  Value char(50),
  PRIMARY KEY (ID)
);


