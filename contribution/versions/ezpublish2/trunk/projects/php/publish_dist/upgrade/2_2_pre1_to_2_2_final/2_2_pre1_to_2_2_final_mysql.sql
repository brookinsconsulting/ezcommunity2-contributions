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

