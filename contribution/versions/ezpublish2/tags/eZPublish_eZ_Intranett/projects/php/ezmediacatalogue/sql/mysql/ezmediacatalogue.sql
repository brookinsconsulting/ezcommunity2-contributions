#
# Table structure for table 'eZMediaCatalogue_Category'
#

DROP TABLE IF EXISTS eZMediaCatalogue_Category;
CREATE TABLE eZMediaCatalogue_Category (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Description text,
  ParentID int(11) default NULL,
  UserID int(11) default NULL,
  WritePermission int(11) default '1',
  ReadPermission int(11) default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZMediaCatalogue_CategoryPermission'
#

DROP TABLE IF EXISTS eZMediaCatalogue_CategoryPermission;
CREATE TABLE eZMediaCatalogue_CategoryPermission (
  ID int(11) NOT NULL auto_increment,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZMediaCatalogue_Media'
#

DROP TABLE IF EXISTS eZMediaCatalogue_Media;
CREATE TABLE eZMediaCatalogue_Media (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Caption text,
  Description text,
  FileName varchar(100) default NULL,
  OriginalFileName varchar(100) default NULL,
  ReadPermission int(11) default '1',
  WritePermission int(11) default '1',
  UserID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZMediaCatalogue_MediaPermission'
#

DROP TABLE IF EXISTS eZMediaCatalogue_MediaPermission;
CREATE TABLE eZMediaCatalogue_MediaPermission (
  ID int(11) NOT NULL auto_increment,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZMediaCatalogue_MediaCategoryLink'
#

DROP TABLE IF EXISTS eZMediaCatalogue_MediaCategoryLink;
CREATE TABLE eZMediaCatalogue_MediaCategoryLink (
  ID int(11) NOT NULL auto_increment,
  CategoryID int(11) default NULL,
  MediaID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZMediaCatalogue_MediaCategoryDefinition'
#

DROP TABLE IF EXISTS eZMediaCatalogue_MediaCategoryDefinition;
CREATE TABLE eZMediaCatalogue_MediaCategoryDefinition (
  ID int(11) NOT NULL auto_increment,
  MediaID int(11) default NULL,
  CategoryID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

