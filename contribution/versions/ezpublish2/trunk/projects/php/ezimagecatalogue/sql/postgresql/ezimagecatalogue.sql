drop table eZImageCatalogue_Category;
drop table eZImageCatalogue_CategoryPermission;
drop table eZImageCatalogue_Image;
drop table eZImageCatalogue_ImageVariation;

CREATE TABLE eZImageCatalogue_Category (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  ParentID int default NULL,
  UserID int default NULL,
  WritePermission int default '1',
  ReadPermission int default '1',
  PRIMARY KEY (ID)
);


CREATE TABLE eZImageCatalogue_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_Image (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Caption text,
  Description text,
  PhotographerID int,
  Created int,
  FileName varchar(100) default NULL,
  OriginalFileName varchar(100) default NULL,
  ReadPermission int default '1',
  WritePermission int default '1',
  UserID int default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZImageCatalogue_ImagePermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);


CREATE TABLE eZImageCatalogue_ImageCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  ImageID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageVariation (
  ID int NOT NULL,
  ImageID int default NULL,
  VariationGroupID int default NULL,
  ImagePath varchar(100) default NULL,
  Width int default NULL,
  Height int default NULL,
  Modification char(20) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageVariationGroup (
  ID int NOT NULL,
  Width int default NULL,
  Height int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageMap (
  ID int NOT NULL,
  ImageID int default NULL,
  Link varchar(50) NOT NULL,
  AltText text,
  Shape int NOT NULL,
  StartPosX int NOT NULL,
  StartPosY int NOT NULL,
  EndPosX int NOT NULL,
  EndPosY int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZImageCatalogue_ImageCategoryDefinition (
  ID int NOT NULL,
  ImageID int default NULL,
  CategoryID int default NULL,
  PRIMARY KEY (ID)
);
