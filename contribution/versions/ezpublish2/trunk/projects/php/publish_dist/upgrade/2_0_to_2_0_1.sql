#
#
# SQL upgrade information for eZ publish 2.0-> 2.0.1
#
# -------------------------------------------------

# Add quantity tables
CREATE TABLE eZTrade_Quantity (
  ID int(11) NOT NULL auto_increment,
  Quantity int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZTrade_ProductQuantityDict (
  ProductID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,QuantityID)
) TYPE=MyISAM;

CREATE TABLE eZTrade_ValueQuantityDict (
  ValueID int(11) NOT NULL default '0',
  QuantityID int(11) NOT NULL default '0',
  PRIMARY KEY (ValueID,QuantityID)
) TYPE=MyISAM;

CREATE TABLE eZTrade_QuantityRange (
  ID int(11) NOT NULL auto_increment,
  MaxRange int(11) default NULL,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

INSERT INTO eZTrade_QuantityRange VALUES ('',0,'Unavailable');
INSERT INTO eZTrade_QuantityRange VALUES ('',NULL,'Available');
INSERT INTO eZTrade_QuantityRange VALUES ('',-1,'Not applicable');

ALTER TABLE eZTrade_OptionValue MODIFY Price float(10,2);

create table eZTrade_PreOrder( ID int primary key auto_increment, Created timestamp );
alter table eZTrade_PreOrder add OrderID int not null;  
alter table eZTrade_Order drop IsActive ;

INSERT INTO eZUser_Permission VALUES('','6','CompanyStats');

CREATE TABLE eZContact_CompanyView (
  ID int(11) NOT NULL auto_increment,
  CompanyID int(11) NOT NULL default '0',
  Count int(11) NOT NULL default '0',
  Date date NOT NULL default '0000-00-00',
  PRIMARY KEY (ID,CompanyID,Date)
) TYPE=MyISAM;

CREATE TABLE eZTrade_Link (
  ID int(11) NOT NULL auto_increment,
  SectionID int(11) NOT NULL default '0',
  Name varchar(60) default NULL,
  URL text,
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZTrade_LinkSection (
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZTrade_ProductSectionDict (
  ProductID int(11) NOT NULL default '0',
  SectionID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ProductID,SectionID)
) TYPE=MyISAM;

CREATE TABLE eZArticle_ArticleKeyword (
  ID int(11) NOT NULL auto_increment,
  ArticleID int(11) NOT NULL,
  Keyword varchar(50) NOT NULL,
  Automatic int(1) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

# Add SimultaneousLogins to eZUser_User
ALTER TABLE eZUser_User ADD SimultaneousLogins int(11) DEFAULT '0' NOT NULL;

# modification information on image variations. 
alter table eZImageCatalogue_ImageVariation add Modification char(20) not null default "";   

# headers for attributes
alter table eZTrade_Attribute add Placement int default 0;
alter table eZTrade_Attribute add AttributeType int default 1;    

# Unit for attribute list
alter table eZTrade_Attribute add Unit varchar(8);

# Discuss article
alter table eZArticle_Article add Discuss int default 0; 
#placement for article categories

ALTER TABLE eZArticle_Category ADD Placement int(11) DEFAULT '0';
update eZArticle_Category set Placement=ID;

DROP TABLE eZContact_ImageType;

# Add auto cookie login
alter table eZUser_User add CookieLogin int default 0; 
create table eZUser_Cookie ( ID int auto_increment primary key, UserID int default 0, Hash char(33), Time timestamp );    