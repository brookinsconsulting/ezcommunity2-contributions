CREATE TABLE eZBulkMail_Category (
  ID int NOT NULL,
  Name varchar(200) default NULL,
  Description text,
  IsPublic int NOT NULL,
  IsSingleCategory int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_Mail (
  ID int NOT NULL,
  UserID int default '0',
  FromField varchar(100) default NULL,
  FromName varchar(100) default NULL,
  ReplyTo varchar(100) default NULL,
  Subject varchar(255) default NULL,
  BodyText text,
  SentDate int default 0,
  IsDraft int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_MailCategoryLink (
  MailID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  PRIMARY KEY (MailID,CategoryID)
);

CREATE TABLE eZBulkMail_MailTemplateLink (
  MailID int NOT NULL default '0',
  TemplateID int NOT NULL default '0',
  PRIMARY KEY (MailID)
);

CREATE TABLE eZBulkMail_SentLog (
  ID int NOT NULL,
  MailID int NOT NULL default '0',
  AddressID int NOT NULL default '0',
  SentDate int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionAddress (
  ID int NOT NULL,
  Password varchar(50) NOT NULL,
  EMail varchar(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_SubscriptionLink (
  CategoryID int NOT NULL default '0',
  AddressID int NOT NULL default '0',
  PRIMARY KEY (AddressID,CategoryID)
);

CREATE TABLE eZBulkMail_Template (
  ID int NOT NULL,
  Name varchar(200) default NULL,
  Description lvarchar default NULL,
  Header text,
  Footer text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_GroupCategoryLink (
  CategoryID int NOT NULL,
  GroupID int NOT NULL,
  PRIMARY KEY (CategoryID, GroupID)
);

CREATE TABLE eZBulkMail_Forgot (
  ID int NOT NULL,
  Mail varchar(255) NOT NULL,
  Password varchar(50) NOT NULL,
  Hash varchar(33),
  Time int,
  PRIMARY KEY (ID)
);