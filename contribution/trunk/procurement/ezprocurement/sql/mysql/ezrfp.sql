CREATE TABLE eZRfp_Rfp (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Contents text,
  ContentsWriterID int default NULL,
  LinkText varchar(50) default NULL,
  ProjectManager int NOT NULL default '0',
  AuthorID int NOT NULL default '0',
  Modified int NOT NULL,
  Created int NOT NULL,
  Published int NOT NULL,
  AwardDate int NULL,
  PageCount int default NULL,
  IsPublished int default '0',
  Keywords text,
  Discuss int default '0',
  TopicID int NOT NULL default '0',
  StartDate int NOT NULL,
  StopDate int NOT NULL,
  ImportID varchar(255) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_RfpCategoryDefinition (
  ID int NOT NULL,
  RfpID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  PRIMARY KEY (ID)
);


CREATE TABLE eZRfp_RfpCategoryLink (
  ID int NOT NULL,
  RfpID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  Placement int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_RfpFileLink (
  ID int NOT NULL,
  RfpID int NOT NULL default '0',
  FileID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_RfpFormDict (
  ID int NOT NULL,
  RfpID int default NULL,
  FormID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_RfpForumLink (
  ID int NOT NULL,
  RfpID int NOT NULL default '0',
  ForumID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_RfpImageDefinition (
  RfpID int NOT NULL default '0',
  ThumbnailImageID int default NULL,
  PRIMARY KEY (RfpID )
);

CREATE TABLE eZRfp_RfpImageLink (
  ID int NOT NULL,
  RfpID int NOT NULL default '0',
  ImageID int NOT NULL default '0',
  Created int NOT NULL,
  Placement int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_RfpKeyword (
  ID int NOT NULL,
  RfpID int NOT NULL default '0',
  Keyword varchar(50) NOT NULL default '',
  Automatic int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_RfpPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_RfpTypeLink (
  ID int NOT NULL,
  RfpID int default NULL,
  TypeID int default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZRfp_Attribute (
  ID int NOT NULL,
  TypeID int default NULL,
  Name char(150) default NULL,
  Placement int default NULL,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_AttributeValue (
  ID int NOT NULL,
  RfpID int default NULL,
  AttributeID int default NULL,
  Value text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_BulkMailCategoryLink (
  RfpCategoryID int NOT NULL default '0',
  BulkMailCategoryID int NOT NULL default '0',
  PRIMARY KEY (RfpCategoryID,BulkMailCategoryID)
);

CREATE TABLE eZRfp_Category (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  ParentID int default '0',
  ExcludeFromSearch int default '0',
  SortMode int NOT NULL default '1',
  OwnerID int default '0',
  Placement int default '0',
  SectionID int NOT NULL default '0',
  ImageID int default NULL,
  EditorGroupID int default '0',
  ListLimit int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_CategoryReaderLink (
  ID int NOT NULL,
  CategoryID int NOT NULL default '0',
  GroupID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_Log (
  ID int NOT NULL,
  RfpID int NOT NULL default '0',
  Created int NOT NULL,
  Message text NOT NULL,
  UserID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_Topic (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  Description text,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_Type (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZRfp_RfpMediaLink (
  ID int(11) NOT NULL,
  RfpID int(11) NOT NULL default '0',
  MediaID int(11) NOT NULL default '0',
  Created int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZRfp_RfpWordLink (
  RfpID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  WordID int(11) NOT NULL default '0'
);

CREATE TABLE eZRfp_Word (
  ID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  Word varchar(50) NOT NULL default ''
);

CREATE TABLE eZRfp_RfpKeywordFirstLetter (
  ID int(11) NOT NULL default '0',
  Letter char(1) NOT NULL default ''
);


CREATE INDEX Rfp_Name ON eZRfp_Rfp (Name);
CREATE INDEX Rfp_Published ON eZRfp_Rfp (Published);
# CREATE FULLTEXT INDEX Rfp_Fulltext ON eZRfp_Rfp (Contents);
# CREATE FULLTEXT INDEX Rfp_FulltextName ON eZRfp_Rfp (Name);

CREATE INDEX Link_RfpID ON eZRfp_RfpCategoryLink (RfpID);
CREATE INDEX Link_CategoryID ON eZRfp_RfpCategoryLink (CategoryID);
CREATE INDEX Link_Placement ON eZRfp_RfpCategoryLink (Placement);

CREATE INDEX WordLink_RfpID ON eZRfp_RfpWordLink (RfpID);
CREATE INDEX WordLink_WordID ON eZRfp_RfpWordLink (WordID);
CREATE INDEX Word_Word ON eZRfp_Word (Word);
CREATE UNIQUE INDEX Word_ID ON eZRfp_Word (ID);
CREATE UNIQUE INDEX RfpID ON eZRfp_RfpImageDefinition (RfpID);

CREATE INDEX RfpPermission_ObjectID ON eZRfp_RfpPermission (ObjectID);
CREATE INDEX RfpPermission_GroupID ON eZRfp_RfpPermission (GroupID);
CREATE INDEX RfpPermission_WritePermission ON eZRfp_RfpPermission (WritePermission);
CREATE INDEX RfpPermission_ReadPermission ON eZRfp_RfpPermission (ReadPermission);

CREATE INDEX Def_RfpID ON eZRfp_RfpCategoryDefinition (RfpID);
CREATE INDEX Def_CategoryID ON eZRfp_RfpCategoryDefinition (CategoryID);

CREATE INDEX RfpKeyword_Keyword ON eZRfp_RfpKeyword (Keyword);
CREATE INDEX RfpKeyword_RfpID ON eZRfp_RfpKeyword (RfpID);

CREATE INDEX RfpAttribute_Placement ON eZRfp_Attribute (Placement);
CREATE INDEX RfpAttributeValue_RfpID ON eZRfp_AttributeValue (RfpID, AttributeID);

