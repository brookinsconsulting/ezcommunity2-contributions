CREATE TABLE eZSession_Session(
  ID int NOT NULL,
  Hash varchar(33) default NULL,
  Created int NOT NULL,
  LastAccessed int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSession_SessionVariable(
  ID int NOT NULL,
  SessionID int not NULL,
  Name varchar(25) not NULL,
  Value varchar(50) not NULL,
  GroupName varchar(50) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZSession_Preferences (
  ID int NOT NULL,
  UserID int NOT NULL,
  Name varchar(50) default NULL,
  Value varchar(255) default NULL,
  GroupName varchar(50) default NULL,
  PRIMARY KEY (ID)
);
CREATE TABLE eZUser_User (
  ID int NOT NULL,
  Login varchar(50) NOT NULL default '',
  Password varchar(50) NOT NULL default '',
  Email varchar(50) default NULL,
  FirstName varchar(50) default NULL,
  LastName varchar(50) default NULL,
  InfoSubscription int default '0',
  Signature text NOT NULL,
  SimultaneousLogins int NOT NULL default '0',
  CookieLogin int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_UserGroupLink (
  ID int NOT NULL,
  UserID int default NULL,
  GroupID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_UserAddressLink (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  AddressID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Author (
  ID int NOT NULL,
  Name varchar(255) default NULL,
  EMail varchar(255) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZUser_Cookie (
  ID int NOT NULL,
  UserID int default '0',
  Hash varchar(33) default NULL,
  Time int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZUser_Forgot (
  ID int NOT NULL,
  UserID int NOT NULL default '0',
  Hash varchar(33) default NULL,
  Time int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Group (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  SessionTimeout int default '60',
  IsRoot int default '0',
  PRIMARY KEY (ID)
);


CREATE TABLE eZUser_GroupPermissionLink (
  ID int NOT NULL,
  GroupID int default NULL,
  PermissionID int default NULL,
  IsEnabled int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Module (
  ID int NOT NULL,
  Name varchar(100) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZUser_Permission (
  ID int NOT NULL,
  ModuleID int default NULL,
  Name varchar(100) default NULL,
  PRIMARY KEY (ID)
);


INSERT INTO eZUser_Module (ID, Name ) VALUES (1,'eZTrade');
INSERT INTO eZUser_Module (ID, Name ) VALUES (2,'eZPoll');
INSERT INTO eZUser_Module (ID, Name ) VALUES (3,'eZUser');
INSERT INTO eZUser_Module (ID, Name ) VALUES (4,'eZTodo');
INSERT INTO eZUser_Module (ID, Name ) VALUES (5,'eZNews');
INSERT INTO eZUser_Module (ID, Name ) VALUES (6,'eZContact');
INSERT INTO eZUser_Module (ID, Name ) VALUES (7,'eZForum');
INSERT INTO eZUser_Module (ID, Name ) VALUES (8,'eZLink');
INSERT INTO eZUser_Module (ID, Name ) VALUES (9,'eZFileManager');
INSERT INTO eZUser_Module (ID, Name ) VALUES (10,'eZImageCatalogue');
INSERT INTO eZUser_Module (ID, Name ) VALUES (11,'eZBug');
INSERT INTO eZUser_Module (ID, Name ) VALUES (12,'eZArticle');
INSERT INTO eZUser_Module (ID, Name ) VALUES (13,'eZBulkMail');
INSERT INTO eZUser_Module (ID, Name ) VALUES (14,'eZStats');
INSERT INTO eZUser_Module (ID, Name ) VALUES (15,'eZSysInfo');
INSERT INTO eZUser_Module (ID, Name ) VALUES (16,'eZSiteManager');


INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (1,3,'UserAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (2,3,'UserDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (3,3,'UserModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (4,3,'GroupDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (5,3,'GroupAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (6,3,'GroupModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (11,8,'LinkGroupModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (8,3,'AdminLogin');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (10,8,'LinkGroupAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (9,8,'LinkGroupDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (12,8,'LinkModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (13,8,'LinkAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (14,8,'LinkDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (15,7,'CategoryAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (16,7,'CategoryModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (17,7,'CategoryDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (18,7,'ForumDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (19,7,'ForumAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (20,7,'ForumModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (21,7,'MessageModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (22,7,'MessageAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (23,7,'MessageDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (24,6,'PersonAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (25,6,'CompanyAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (26,6,'CategoryAdd');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (27,6,'PersonDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (28,6,'CompanyDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (29,6,'CategoryDelete');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (30,6,'PersonModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (31,6,'CompanyModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (32,6,'CategoryModify');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (33,6,'PersonView');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (34,6,'PersonList');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (35,3,'UserLogin');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (36,9,'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (39,10,'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (41,6,'CompanyView');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (42,6,'CompanyList');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (43,6,'TypeAdmin');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (44,6,'Consultation');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (45,4,'ViewOtherUsers');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (46,4,'AddOthers');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (47,4,'EditOthers');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (48,6,'CompanyStats');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (49,1,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (50,2,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (51,3,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (52,4,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (53,5,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (54,6,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (55,7,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (56,8,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (57,9,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (58,10,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (59,11,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (60,12,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (61,13,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (62,14,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (63,15,'ModuleEdit');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (64,12,'WriteToRoot');
INSERT INTO eZUser_Permission (ID, ModuleID, Name ) VALUES (65,16,'ModuleEdit');


INSERT INTO eZUser_User ( ID,  Login,  Password,  Email,  FirstName,  LastName,  InfoSubscription,  Signature,  SimultaneousLogins,  CookieLogin )  
VALUES (1,'admin','9b6d0bb3102b87fae57bc4a39149518e','postmaster@yourdomain','admin','user','0','',0,0);

INSERT INTO eZUser_Group ( ID,  Name,  Description,  SessionTimeout,  IsRoot ) VALUES (1,'Administrators','All rights',7200,1);
INSERT INTO eZUser_Group ( ID,  Name,  Description,  SessionTimeout,  IsRoot ) VALUES (2,'Anonymous','Anonymous users',7200,0);

INSERT INTO eZUser_UserGroupLink ( ID,  UserID,  GroupID ) VALUES (1,1,1);



CREATE TABLE eZSiteManager_Section (
  ID int NOT NULL,
  Name varchar(200) default NULL,
  Created int NOT NULL,
  Description varchar(255),
  SiteDesign varchar(30) default NULL,
  TemplateStyle varchar(30) default NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZSiteManager_Section   ( ID,  Name, Created, Description,  SiteDesign ) VALUES ( 1, 'Standard Section', 1, NULL, 'standard' );
CREATE TABLE eZURLTranslator_URL (
  ID int NOT NULL,
  Source varchar(200) default NULL,
  Dest varchar(200) default NULL,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_Hit (
  ID int NOT NULL,
  Link int default NULL,
  Time int NOT NULL,
  RemoteIP varchar(15) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_Link (
  ID int NOT NULL ,
  Name varchar(100) default NULL,
  Description text,
  LinkGroup int default NULL,
  KeyWords varchar(100) default NULL,
  Modified int NOT NULL,
  Accepted int,
  Created int default NULL,
  Url varchar(100) default NULL,
  ImageID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_LinkCategoryDefinition (
  ID int NOT NULL ,
  LinkID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_LinkCategoryLink (
  ID int NOT NULL ,
  LinkID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_Category (
  ID int NOT NULL,
  Parent int NOT NULL,
  Name varchar(100) default NULL,
  ImageID int NOT NULL,
  Description text,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_Attribute (
  ID int NOT NULL,
  TypeID int default NULL,
  Name varchar(150) default NULL,
  Created int default NULL,
  Placement int default 0,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_AttributeValue (
  ID int NOT NULL,
  LinkID int default NULL,
  AttributeID int default NULL,
  Value char(200) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_Type (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_TypeLink (
  ID int NOT NULL,
  LinkID int default NULL,
  TypeID int default NULL,
  PRIMARY KEY (ID)
);
CREATE TABLE eZAd_Ad(
  ID int NOT NULL,
  Name varchar(150) default NULL,
  ImageID int default NULL,
  ViewStartDate int default null,
  ViewStopDate int default null,
  ViewRule int,
  URL varchar(200) default NULL,
  Description text,
  IsActive int not null,
  ViewPrice float default 0.0,
  ClickPrice float default 0.0,
  HTMLBanner text default null,
  UseHTML int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_AdCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  AdID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_Category (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  Description text,
  ParentID int not NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_Click (
  ID int NOT NULL,
  AdID int default NULL,
  ClickCount int default NULL,
  ClickOffsetCount int default NULL,
  ClickPrice float,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_View (
  ID int NOT NULL,
  AdID int default NULL,
  ViewCount int NOT NULL,
  ViewOffsetCount int NOT NULL,
  ViewPrice float NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZImageCatalogue_Category (
  ID int NOT NULL,
  Name varchar(100) default NULL,
  Description text,
  ParentID int default NULL,
  UserID int default NULL,
  WritePermission int default '1',
  ReadPermission int default '1',
  PRIMARY KEY (ID)
);


CREATE TABLE eZImageCatalogue_CategoryPermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_Image (
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
  Keywords varchar(255) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZImageCatalogue_ImagePermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);


CREATE TABLE eZImageCatalogue_ImageCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  ImageID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageVariation (
  ID int NOT NULL,
  ImageID int default NULL,
  VariationGroupID int default NULL,
  ImagePath varchar(100) default NULL,
  Width int default NULL,
  Height int default NULL,
  Modification char(20) NOT NULL default '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageVariationGroup (
  ID int NOT NULL,
  Width int default NULL,
  Height int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZImageCatalogue_ImageMap (
  ID int NOT NULL,
  ImageID int default NULL,
  Link varchar(50) NOT NULL,
  AltText text,
  Shape int NOT NULL,
  StartPosX int NOT NULL,
  StartPosY int NOT NULL,
  EndPosX int NOT NULL,
  EndPosY int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZImageCatalogue_ImageCategoryDefinition (
  ID int NOT NULL,
  ImageID int default NULL,
  CategoryID int default NULL,
  PRIMARY KEY (ID)
);
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



CREATE INDEX Article_Name ON eZArticle_Article (Name);
CREATE INDEX Article_Keywords ON eZArticle_Article (Keywords);
CREATE INDEX Article_Published ON eZArticle_Article (Published);

CREATE INDEX Link_ArticleID ON eZArticle_ArticleCategoryLink (ArticleID);
CREATE INDEX Link_CategoryID ON eZArticle_ArticleCategoryLink (CategoryID);
CREATE INDEX Link_Placement ON eZArticle_ArticleCategoryLink (Placement);

CREATE INDEX Def_ArticleID ON eZArticle_ArticleCategoryDefinition (ArticleID);
CREATE INDEX Def_CategoryID ON eZArticle_ArticleCategoryDefinition (CategoryID);

