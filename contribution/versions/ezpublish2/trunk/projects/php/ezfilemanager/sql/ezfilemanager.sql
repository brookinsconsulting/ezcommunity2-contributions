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
# Table structure for table 'eZFileManager_Folder'
#
DROP TABLE IF EXISTS eZFileManager_Folder;
CREATE TABLE eZFileManager_Folder (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11) DEFAULT '0' NOT NULL,
  UserID int(11),
  WritePermission int(11) DEFAULT '1',
  ReadPermission int(11) DEFAULT '1',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZFileManager_Folder'
#
