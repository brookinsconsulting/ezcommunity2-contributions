# This SQL file is for 3.0 features in the upcomming version ( head branch )
CREATE TABLE eZBug_Support (
  ID int(11) NOT NULL default '0',
  Name varchar(150),
  Email varchar(100) NOT NULL,
  ExpiryDate int(11),
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_SupportCategory (
  ID int(11) NOT NULL default '0',
  Name varchar(150),
  BugModuleID int(11),
  Email varchar(100) NOT NULL,
  Password varchar(100) NOT NULL,
  MailServer varchar(100) NOT NULL,
  MailServerPort int default '110',
  SupportNo int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleRate( ID int not null primary key, ArticleID int not null, Rate int not null, IP char(15) not null );

alter table eZTrade_VoucherInformation change OnlineID ToOnlineID int default '0';
alter table eZTrade_PreOrder add Verified int default '0';

CREATE TABLE eZUser_Title (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZUser_UserTitleLink (
  ID int NOT NULL,
  UserID int NOT NULL,
  TitleID int NOT NULL,
  PRIMARY KEY (ID)
);
