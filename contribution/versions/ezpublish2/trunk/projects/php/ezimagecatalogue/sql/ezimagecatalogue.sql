# MySQL dump 7.1
#
# Host: localhost    Database: publish.ezimagecatalouge
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'eZImageCatalogue_Image'
#
CREATE TABLE eZImageCatalogue_Image (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Caption text,
  Description text,
  FileName varchar(100),
  OriginalFileName varchar(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_Image'
#

INSERT INTO eZImageCatalogue_Image VALUES (1,'','caption text','','php0meQsj.jpg','360_rose.jpg');

#
# Table structure for table 'eZImageCatalogue_ImageVariation'
#
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
