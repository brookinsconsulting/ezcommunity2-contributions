## NOTE this file should not be used in head branch..
alter table eZTrade_VoucherInformation change OnlineID ToOnlineID int default '0';
alter table eZTrade_PreOrder add Verified Verified int default '0';
alter table eZArticle_Article add LinkURL varchar(100) default '';
alter table eZTrade_PreOrder add Verified int default '0';
alter table eZTrade_VoucherInformation add UserID int default 0; 

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

alter table eZTrade_ProductSectionDict add ID int NOT NULL default 0; 
