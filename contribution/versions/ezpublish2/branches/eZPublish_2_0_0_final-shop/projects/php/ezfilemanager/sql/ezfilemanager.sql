#
# Table structure for table 'eZFileManager_File'
#
DROP TABLE IF EXISTS eZFileManager_File;
CREATE TABLE eZFileManager_File (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(200),
  Description char(200),
  FileName char(200),
  OriginalFileName char(200),
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZFileManager_File'
#

INSERT INTO eZFileManager_File VALUES (1,'CHANGELOG','The complete change log.','phpUuO7Ms','CHANGELOG',0,0,0);

#
# Table structure for table 'eZFileManager_FileFolderLink'
#
DROP TABLE IF EXISTS eZFileManager_FileFolderLink;
CREATE TABLE eZFileManager_FileFolderLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  FolderID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZFileManager_FileFolderLink'
#


#
# Table structure for table 'eZFileManager_FilePageViewLink'
#
DROP TABLE IF EXISTS eZFileManager_FilePageViewLink;
CREATE TABLE eZFileManager_FilePageViewLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PageViewID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZFileManager_FilePageViewLink'
#

INSERT INTO eZFileManager_FilePageViewLink VALUES (1,121,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (2,123,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (3,216,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (4,217,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (5,219,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (6,221,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (7,223,1);

#
# Table structure for table 'eZFileManager_Folder'
#
DROP TABLE IF EXISTS eZFileManager_Folder;
CREATE TABLE eZFileManager_Folder (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11) DEFAULT '0' NOT NULL,
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZFileManager_Folder'
#




