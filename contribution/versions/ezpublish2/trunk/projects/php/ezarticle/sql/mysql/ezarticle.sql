CREATE TABLE eZArticle_Article (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Contents text,
  ContentsWriterID int default NULL,
  LinkText varchar(50) default NULL,
  AuthorID int NOT NULL default '0',
  Modified int NOT NULL,
  Created int NOT NULL,
  Published int NOT NULL,
  PageCount int default NULL,
  IsPublished int default '0',
  Keywords text,
  Discuss int default '0',
  TopicID int NOT NULL default '0',
  StartDate int NOT NULL,
  StopDate int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleCategoryDefinition (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  PRIMARY KEY (ID)
);


CREATE TABLE eZArticle_ArticleCategoryLink (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  Placement int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleFileLink (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  FileID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleFormDict (
  ID int NOT NULL,
  ArticleID int default NULL,
  FormID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleForumLink (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  ForumID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleImageDefinition (
  ArticleID int NOT NULL default '0',
  ThumbnailImageID int default NULL,
  PRIMARY KEY (ArticleID )
);

CREATE TABLE eZArticle_ArticleImageLink (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  ImageID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleKeyword (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  Keyword varchar(50) NOT NULL default '',
  Automatic int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticlePermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleTypeLink (
  ID int NOT NULL,
  ArticleID int default NULL,
  TypeID int default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZArticle_Attribute (
  ID int NOT NULL,
  TypeID int default NULL,
  Name char(150) default NULL,
  Placement int default NULL,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_AttributeValue (
  ID int NOT NULL,
  ArticleID int default NULL,
  AttributeID int default NULL,
  Value text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_BulkMailCategoryLink (
  ArticleCategoryID int NOT NULL default '0',
  BulkMailCategoryID int NOT NULL default '0',
  PRIMARY KEY (ArticleCategoryID,BulkMailCategoryID)
);

CREATE TABLE eZArticle_Category (
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
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_CategoryReaderLink (
  ID int NOT NULL,
  CategoryID int NOT NULL default '0',
  GroupID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_Log (
  ID int NOT NULL,
  ArticleID int NOT NULL default '0',
  Created int NOT NULL,
  Message text NOT NULL,
  UserID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_Topic (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  Description text,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_Type (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  PRIMARY KEY (ID)
);

Create table eZArticle_Word
( 
  ID int not null,
   Word varchar(50) not null,
   PRIMARY KEY (ID)
);

CREATE TABLE eZArticle_ArticleWordLink
(
	ArticleID int not null,
	WordID int not null
);


DROP TABLE IF EXISTS eZArticle_ArticleMediaLink;
CREATE TABLE eZArticle_ArticleMediaLink (
  ID int(11) NOT NULL auto_increment,
  ArticleID int(11) NOT NULL default '0',
  MediaID int(11) NOT NULL default '0',
  Created int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;


CREATE INDEX ArticleWord_Word ON eZArticle_Word (Word);
CREATE INDEX ArticleWordLink_ArticleID ON eZArticle_ArticleWordLink (ArticleID);
CREATE INDEX ArticleWordLink_WordID ON eZArticle_ArticleWordLink (WordID);

CREATE INDEX ArticlePermissionObjectID ON eZArticle_ArticlePermission (ObjectID);
CREATE INDEX ArticlePermissionGroupID ON eZArticle_ArticlePermission (GroupID);
CREATE INDEX ArticlePermissionWritePermission ON eZArticle_ArticlePermission (WritePermission);
CREATE INDEX ArticlePermissionReadPermission ON eZArticle_ArticlePermission (ReadPermission);


CREATE INDEX Article_Name ON eZArticle_Article (Name);
CREATE INDEX Article_Keywords ON eZArticle_Article (Keywords);
CREATE INDEX Article_Published ON eZArticle_Article (Published);

CREATE INDEX Link_ArticleID ON eZArticle_ArticleCategoryLink (ArticleID);
CREATE INDEX Link_CategoryID ON eZArticle_ArticleCategoryLink (CategoryID);
CREATE INDEX Link_Placement ON eZArticle_ArticleCategoryLink (Placement);

CREATE INDEX Def_ArticleID ON eZArticle_ArticleCategoryDefinition (ArticleID);
CREATE INDEX Def_CategoryID ON eZArticle_ArticleCategoryDefinition (CategoryID);

