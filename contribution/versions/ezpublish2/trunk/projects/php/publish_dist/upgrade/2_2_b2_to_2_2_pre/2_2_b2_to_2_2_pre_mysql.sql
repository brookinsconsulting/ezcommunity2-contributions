alter table eZTrade_VoucherInformation add FromAddressID int default 0; 
alter table eZTrade_VoucherInformation change AddressID ToAddressID int default 0;        

CREATE TABLE eZBug_Log (
  ID int(11) NOT NULL auto_increment,
  BugID int(11) NOT NULL default '0',
  UserID int(11) NOT NULL default '0',
  Description text,
  Created int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

alter table eZFileManager_Folder add SectionID int(11);

alter table eZTrade_OrderOptionValue change OptionName OptionName text;
alter table eZTrade_OrderOptionValue change ValueName ValueName text;

CREATE TABLE eZSiteManager_SectionFrontPageRow (
  ID int(11) NOT NULL default '0',
  SettingID int(11) default '0',
  CategoryID int(11) default '0',
  Placement int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;
 
CREATE TABLE eZSiteManager_SectionFrontPageRowLink (
  ID int(11) NOT NULL default '0',
  FrontPageID int(11) default '0',
  SectionID int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;
 
CREATE TABLE eZSiteManager_SectionFrontPageSetting (
  ID int(11) NOT NULL default '0',
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;
 
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (1,'1column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (2,'2column');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (3,'1short');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (4,'1columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (5,'2columnProduct');
INSERT INTO eZSiteManager_SectionFrontPageSetting VALUES (6,'ad');

CREATE TABLE eZForm_FormElementFixedValues (
  ID int(11) NOT NULL default '0',
  Value varchar(80) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;
 
CREATE TABLE eZForm_FormElementFixedValueLink (
  ID int(11) NOT NULL default '0',
  ElementID int(11) default '0',
  FixedValueID int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;