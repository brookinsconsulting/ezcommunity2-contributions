alter table eZBulkMail_UserCategoryLink drop ID;

CREATE TABLE eZSiteManager_Menu (
  ID int(11) NOT NULL default '0',
  Name varchar(40) default NULL,
  Link varchar(40) default NULL,
  Type int(11) default '1',
  ParentID int(11) default '0'
) TYPE=MyISAM;

CREATE TABLE eZSiteManager_MenuType (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_SubscriptionCategorySettings (
  ID int(11) NOT NULL,
  CategoryID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  Delay int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZBulkMail_SentLog (
  ID int(11) NOT NULL,
  MailID int(11) NOT NULL default '0',
  Mail varchar(255) default NULL,
  SentDate int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

alter table eZAd_View change ViewPrice ViewPrice float(10,2) default 0;
alter table eZLink_Link change KeyWords KeyWords text;
