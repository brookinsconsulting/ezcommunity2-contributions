#
# Table structure for table 'eZSession_Preferences'
#
DROP TABLE IF EXISTS eZSession_Preferences;
CREATE TABLE eZSession_Preferences (
  ID int(11) NOT NULL,
  UserID int(11) DEFAULT '0' NOT NULL,
  Name char(50),
  Value char(255),
  GroupName char(50) default NULL,
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZSession_Session'
#
DROP TABLE IF EXISTS eZSession_Session;
CREATE TABLE eZSession_Session (
  ID int(11) NOT NULL,
  Hash char(33),
  Created int,
  LastAccessed int,

  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZSession_SessionVariable'
#
DROP TABLE IF EXISTS eZSession_SessionVariable;
CREATE TABLE eZSession_SessionVariable (
  ID int(11) NOT NULL,
  SessionID int(11),
  Name char(25),
  Value char(50),
  GroupName char(50) default NULL,
  PRIMARY KEY (ID)
);




