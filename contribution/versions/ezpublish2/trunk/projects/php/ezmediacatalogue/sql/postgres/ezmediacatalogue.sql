drop table eZMediaCatalouge_Category;
drop table eZMediaCatalouge_CategoryPermission;
drop table eZMediaCatalogue_Image;
drop table eZMediaCatalogue_ImagePermission;
drop table eZMediaCatalogue_ImageCategoryLink;
drop table eZMediaCatalogue_ImageCategoryDefinition;

CREATE TABLE eZMediaCatalouge_Category (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  ParentID int default NULL,
  UserID int default NULL,
  WritePermission int default '1',
  ReadPermission int default '1',
  PRIMARY KEY (ID)
);


CREATE TABLE eZMediaCatalouge_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZMediaCatalogue_Image (
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


CREATE TABLE eZMediaCatalogue_ImagePermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);


CREATE TABLE eZMediaCatalogue_ImageCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  ImageID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZMediaCatalogue_ImageCategoryDefinition (
  ID int NOT NULL,
  ImageID int default NULL,
  CategoryID int default NULL,
  PRIMARY KEY (ID)
);
