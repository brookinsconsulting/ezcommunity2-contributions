CREATE TABLE eZStats_BrowserType (
  ID int NOT NULL,
  BrowserType varchar(250) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_PageView (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  BrowserTypeID int NOT NULL default '0',
  RemoteHostID int NOT NULL default '0',
  RefererURLID int NOT NULL default '0',
  Date int NOT NULL,
  RequestPageID int NOT NULL default '0',
  DateValue int NOT NULL,
  TimeValue int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_RefererURL (
  ID int NOT NULL,
  Domain varchar(100) default NULL,
  URI varchar(200) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_RemoteHost (
  ID int NOT NULL,
  IP varchar(15) default NULL,
  HostName varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZStats_RequestPage (
  ID int NOT NULL,
  URI varchar(250) default NULL,
  PRIMARY KEY (ID)
);
