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

INSERT INTO eZImageCatalogue_Category VALUES (1,'Images',' ',0,27,2,3);

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
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_Image'
#

INSERT INTO eZImageCatalogue_Image VALUES (1,'','','','phpRtGOCL.jpg','DSCN1728.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (2,'','','','phpM0uJe4.jpg','DSCN1722.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (3,'','','','phpZzyrod.jpg','DSCN1760.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (4,'','','','php6o0PjV.jpg','DSCN1884.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (5,'A picture','A picture','A picture','phpXlv43s.jpg','DSCN1354.JPG',3,2,27);
INSERT INTO eZImageCatalogue_Image VALUES (6,'A picture','A picture','A picture','php7DBg1K.jpg','DSCN1728.JPG',3,2,27);
INSERT INTO eZImageCatalogue_Image VALUES (7,'Flower','Flower','A flower','phptpTEuZ.jpg','DSCN1722.JPG',3,1,27);

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

INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (1,2,5);
INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (2,2,6);
INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (3,2,7);

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

INSERT INTO eZImageCatalogue_ImageVariation VALUES (1,1,1,'ezimagecatalogue/catalogue/variations/1-150x150.jpg',150,113);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (2,2,1,'ezimagecatalogue/catalogue/variations/2-150x150.jpg',150,113);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (3,1,2,'ezimagecatalogue/catalogue/variations/1-200x200.jpg',200,150);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (4,2,2,'ezimagecatalogue/catalogue/variations/2-200x200.jpg',200,150);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (5,1,3,'ezimagecatalogue/catalogue/variations/1-100x100.jpg',100,75);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (6,2,3,'ezimagecatalogue/catalogue/variations/2-100x100.jpg',100,75);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (7,3,1,'ezimagecatalogue/catalogue/variations/3-150x150.jpg',150,113);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (8,3,3,'ezimagecatalogue/catalogue/variations/3-100x100.jpg',100,75);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (9,3,4,'ezimagecatalogue/catalogue/variations/3-300x300.jpg',300,225);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (10,3,5,'ezimagecatalogue/catalogue/variations/3-35x35.jpg',35,26);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (11,3,6,'ezimagecatalogue/catalogue/variations/3-400x500.jpg',400,300);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (12,3,7,'ezimagecatalogue/catalogue/variations/3-240x200.jpg',240,180);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (13,3,8,'ezimagecatalogue/catalogue/variations/3-250x250.jpg',250,188);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (14,4,1,'ezimagecatalogue/catalogue/variations/4-150x150.jpg',150,113);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (15,4,3,'ezimagecatalogue/catalogue/variations/4-100x100.jpg',100,75);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (16,4,4,'ezimagecatalogue/catalogue/variations/4-300x300.jpg',300,225);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (17,4,6,'ezimagecatalogue/catalogue/variations/4-400x500.jpg',400,300);

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
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (2,200,200);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (3,100,100);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (4,300,300);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (5,35,35);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (6,400,500);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (7,240,200);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (8,250,250);

