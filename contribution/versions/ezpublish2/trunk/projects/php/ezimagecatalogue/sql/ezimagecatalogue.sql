#
# Table structure for table 'eZImageCatalogue_Category'
#
DROP TABLE IF EXISTS eZImageCatalogue_Category;
CREATE TABLE eZImageCatalogue_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11),
  UserID int(11),
  WritePermission int(11) DEFAULT '1',
  ReadPermission int(11) DEFAULT '1',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_Category'
#

#
# Table structure for table 'eZImageCatalogue_Image'
#
DROP TABLE IF EXISTS eZImageCatalogue_Image;
CREATE TABLE eZImageCatalogue_Image (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Caption text,
  Description text,
  FileName varchar(100),
  OriginalFileName varchar(100),
  UserID int(11),
  WritePermission int(11) DEFAULT '1',
  ReadPermission int(11) DEFAULT '1',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_Image'
#

INSERT INTO eZImageCatalogue_Image VALUES (1,'','caption text','','php0meQsj.jpg','360_rose.jpg');

#
# Table structure for table 'eZImageCatalogue_ImageCategoryLink'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageCategoryLink;
CREATE TABLE eZImageCatalogue_ImageCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  ImageID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_ImageCategoryLink'
#

#
# Table structure for table 'eZImageCatalogue_ImageVariation'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageVariation;
CREATE TABLE eZImageCatalogue_ImageVariation (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ImageID int(11),
  VariationGroupID int(11),
  ImagePath char(100),
  Width int(11),
  Height int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_ImageVariation'
#

INSERT INTO eZImageCatalogue_ImageVariation VALUES (1,1,1,'ezimagecatalogue/catalogue/variations/1-150x150.jpg',150,83);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (2,1,2,'ezimagecatalogue/catalogue/variations/1-100x100.jpg',100,56);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (3,1,3,'ezimagecatalogue/catalogue/variations/1-300x300.jpg',270,150);

#
# Table structure for table 'eZImageCatalogue_ImageVariationGroup'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageVariationGroup;
CREATE TABLE eZImageCatalogue_ImageVariationGroup (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Width int(11),
  Height int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_ImageVariationGroup'
#

INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (1,150,150);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (2,100,100);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (3,300,300);

#
# Table structure for table 'eZLink_Hit'
#
DROP TABLE IF EXISTS eZLink_Hit;
CREATE TABLE eZLink_Hit (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Link int(11),
  Time timestamp(14),
  RemoteIP char(15),
  PRIMARY KEY (ID)
);
