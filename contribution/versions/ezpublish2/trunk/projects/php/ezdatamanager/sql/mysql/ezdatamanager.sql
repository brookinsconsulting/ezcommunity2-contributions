
CREATE TABLE eZDataManager_DataType (
  ID int(11) NOT NULL default '0',
  Name varchar(100) default '',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;

INSERT INTO eZDataManager_DataType VALUES (1,'Klubb');
INSERT INTO eZDataManager_DataType VALUES (2,'Medlem');


CREATE TABLE eZDataManager_DataTypeItem (
  ID int(11) NOT NULL default '0',
  DataTypeID int(11) NOT NULL default '0',
  Name varchar(100) default '',
  ItemType int(11) NOT NULL default '0',
  Created int(11) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;


INSERT INTO eZDataManager_DataTypeItem VALUES (1,1,'Klubb nr.',1,1013341280);
INSERT INTO eZDataManager_DataTypeItem VALUES (2,1,'Charterdato',1,1013341282);
INSERT INTO eZDataManager_DataTypeItem VALUES (3,1,'Møtested og tid',1,1013341307);
INSERT INTO eZDataManager_DataTypeItem VALUES (4,1,'President',1,1013341316);
INSERT INTO eZDataManager_DataTypeItem VALUES (5,2,'Medlemsnummer',1,1013341356);
INSERT INTO eZDataManager_DataTypeItem VALUES (6,2,'Klubb',2,1013341368);
INSERT INTO eZDataManager_DataTypeItem VALUES (7,2,'Adresse',1,1013341379);
INSERT INTO eZDataManager_DataTypeItem VALUES (8,2,'Postnummer',1,1013341393);
INSERT INTO eZDataManager_DataTypeItem VALUES (9,2,'Poststed',1,1013341400);
INSERT INTO eZDataManager_DataTypeItem VALUES (10,2,'Telefon priv',1,1013341409);
INSERT INTO eZDataManager_DataTypeItem VALUES (11,2,'Telefon arbeid',1,1013341416);
INSERT INTO eZDataManager_DataTypeItem VALUES (12,2,'e-post',1,1013341426);
INSERT INTO eZDataManager_DataTypeItem VALUES (13,2,'Kjønn',1,1013341431);
INSERT INTO eZDataManager_DataTypeItem VALUES (14,2,'Fødselsdato',1,1013341439);
INSERT INTO eZDataManager_DataTypeItem VALUES (15,2,'Verv',1,1013341446);


CREATE TABLE eZDataManager_Item (
  ID int(11) NOT NULL default '0',
  DataTypeID int(11) NOT NULL default '0',
  Name varchar(100) default '',
  OwnerGroupID int(11) default '0',
  PRIMARY KEY  (ID),
  KEY DataManager_Item_Name (Name)
) TYPE=MyISAM;


INSERT INTO eZDataManager_Item VALUES (1,1,'Skien',0);
INSERT INTO eZDataManager_Item VALUES (2,2,'Bård Farstad',0);

CREATE TABLE eZDataManager_ItemValue (
  ID int(11) NOT NULL default '0',
  ItemID int(11) NOT NULL default '0',
  DataTypeItemID int(11) NOT NULL default '0',
  Value text,
  ItemType int(11) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;


INSERT INTO eZDataManager_ItemValue VALUES (1,1,1,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>1002</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (2,1,2,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>2002</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (3,1,3,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>Klubbhuset kl 18:30 hver onsdag.</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (4,1,4,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>Bård</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (5,2,5,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>007</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (6,2,6,'1',0);
INSERT INTO eZDataManager_ItemValue VALUES (7,2,7,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>Aaasalund</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (8,2,8,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>3944</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (9,2,9,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>Porsgrunn</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (10,2,10,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>9999999</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (11,2,11,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>35 58 70 42</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (12,2,12,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro><mail to=\"bf@ez.no\" subject=\"\" text=\"bf@ez.no\" /></intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (13,2,13,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>Hannkjønn</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (14,2,14,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>77</intro><body><page></page></body></article>',0);
INSERT INTO eZDataManager_ItemValue VALUES (15,2,15,'<?xml version=\"1.0\"?><article><generator>qdom</generator>\n<intro>President</intro><body><page></page></body></article>',0);

CREATE TABLE eZDataManager_RelationDefinition (
  ID int(11) NOT NULL default '0',
  DataTypeItemID int(11) NOT NULL default '0',
  DataTypeRelationID int(11) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZDataManager_RelationDefinition'
#

INSERT INTO eZDataManager_RelationDefinition VALUES (1,6,1);
