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
