CREATE TABLE eZBulkMail_Category (
  ID int(11) NOT NULL auto_increment,
  Name varchar(200) default NULL,
  Description text,
  IsPublic int NOT NULL,
  IsSingleCategory int default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_Mail (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default '0',
  FromField varchar(100) default NULL,
  FromName varchar(100) default NULL,
  ReplyTo varchar(100) default NULL,
  Subject varchar(255) default NULL,
  BodyText text,
  SentDate int(14) default 0,
  IsDraft int(1) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_MailCategoryLink (
  MailID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID,CategoryID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_MailTemplateLink (
  MailID int(11) NOT NULL default '0',
  TemplateID int(11) NOT NULL default '0',
  PRIMARY KEY (MailID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_SentLog (
  ID int(11) NOT NULL auto_increment,
  MailID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  SentDate int(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_SubscriptionAddress (
  ID int(11) NOT NULL auto_increment,
  Password varchar(50) NOT NULL,
  EMail varchar(255) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_SubscriptionLink (
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  PRIMARY KEY (AddressID,CategoryID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_Template (
  ID int(11) NOT NULL auto_increment,
  Name varchar(200) default NULL,
  Description lvarchar default NULL,
  Header text,
  Footer text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_GroupCategoryLink (
  CategoryID int NOT NULL,
  GroupID int NOT NULL,
  PRIMARY KEY (CategoryID, GroupID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_Forgot (
  ID int NOT NULL,
  Mail varchar(255) NOT NULL,
  Password varchar(50) NOT NULL,
  Hash varchar(33),
  Time int,
  PRIMARY KEY (ID)
) TYPE=MyISAM;