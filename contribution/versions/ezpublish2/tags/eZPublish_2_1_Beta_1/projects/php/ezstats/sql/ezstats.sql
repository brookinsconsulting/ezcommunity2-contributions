#
# Table structure for table 'eZStats_BrowserType'
#
DROP TABLE IF EXISTS eZStats_BrowserType;
CREATE TABLE eZStats_BrowserType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  BrowserType char(250) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_BrowserType'
#



#
# Table structure for table 'eZStats_PageView'
#
DROP TABLE IF EXISTS eZStats_PageView;
CREATE TABLE eZStats_PageView (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  BrowserTypeID int(11) DEFAULT '0' NOT NULL,
  RemoteHostID int(11) DEFAULT '0' NOT NULL,
  RefererURLID int(11) DEFAULT '0' NOT NULL,
  Date timestamp(14),
  RequestPageID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_PageView'
#



#
# Table structure for table 'eZStats_RefererURL'
#
DROP TABLE IF EXISTS eZStats_RefererURL;
CREATE TABLE eZStats_RefererURL (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Domain char(100),
  URI char(200),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_RefererURL'
#




#
# Table structure for table 'eZStats_RemoteHost'
#
DROP TABLE IF EXISTS eZStats_RemoteHost;
CREATE TABLE eZStats_RemoteHost (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  IP char(15),
  HostName char(150),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_RemoteHost'
#




#
# Table structure for table 'eZStats_RequestPage'
#
DROP TABLE IF EXISTS eZStats_RequestPage;
CREATE TABLE eZStats_RequestPage (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  URI char(250),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_RequestPage'
#



