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
  PRIMARY KEY (ID)
);

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
# Table structure for table 'eZImageCatalogue_ImageVariationGroup'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageVariationGroup;
CREATE TABLE eZImageCatalogue_ImageVariationGroup (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Width int(11),
  Height int(11),
  PRIMARY KEY (ID)
);

