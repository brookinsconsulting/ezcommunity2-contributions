# MySQL dump 7.1
#
# Host: localhost    Database: publish.ezsession
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'eZSession_Session'
#
CREATE TABLE eZSession_Session (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Hash char(33),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZSession_Session'
#

#
# Table structure for table 'eZSession_SessionVariable'
#
CREATE TABLE eZSession_SessionVariable (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  SessionID int(11),
  Name char(25),
  Value char(50),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZSession_SessionVariable'
#
