CREATE TABLE eZBulkMail_Category (
  ID int NOT NULL,
  Name varchar(200) default NULL,
  Description lvarchar,
  IsPublic int NOT NULL,
  IsSingleCategory int default 0,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_Mail (
  ID int NOT NULL,
  UserID int NOT NULL,
  FromField varchar(100) default NULL,
  FromName varchar(100) default NULL,
  ReplyTo varchar(100) default NULL,
  Subject varchar(255) default NULL,
  BodyText lvarchar,
  SentDate int default 0,
  IsDraft int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBulkMail_MailCategoryLink (
  MailID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (MailID,CategoryID)
);

CREATE TABLE eZBulkMail_MailTemplateLink (
  MailID int NOT NULL,
  TemplateID int NOT NULL,
  PRIMARY KEY (MailID)
);

CREATE TABLE eZBulkMail_SentLog (
  ID int NOT NULL,
  MailID int NOT NULL,
  AddressID int NOT NULL,
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
  CategoryID int NOT NULL,
  AddressID int NOT NULL,
  PRIMARY KEY (AddressID,CategoryID)
);

CREATE TABLE eZBulkMail_Template (
  ID int NOT NULL, 
  Name varchar(200) default NULL,
  Description lvarchar default NULL,
  Header lvarchar default NULL,
  Footer lvarchar default NULL,
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