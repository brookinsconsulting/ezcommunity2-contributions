#
# Table structure for table 'eZSession_Preferences'
#
DROP TABLE IF EXISTS eZSession_Preferences;
CREATE TABLE eZSession_Preferences (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  Name char(50),
  Value char(255),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZSession_Preferences'
#




#
# Table structure for table 'eZSession_Session'
#
DROP TABLE IF EXISTS eZSession_Session;
CREATE TABLE eZSession_Session (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Hash char(33),
  Created timestamp(14),
  LastAccessed timestamp(14),
  SecondLastAccessed timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZSession_Session'
#



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

#
# Dumping data for table 'eZSession_SessionVariable'
#




