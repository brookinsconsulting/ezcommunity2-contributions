#
# Table structure for table 'eZExample_Test'
#

DROP TABLE IF EXISTS eZExample_Test;
CREATE TABLE eZExample_Test (
  ID int(11) NOT NULL auto_increment,
  Text char(100) default NULL,
  Created timestamp,
  PRIMARY KEY (ID)
);


#
# Dumping data for table 'eZExample_Test'
#


#
# Table structure for table 'eZQuiz_Alternative'
#

DROP TABLE IF EXISTS eZQuiz_Alternative;
CREATE TABLE eZQuiz_Alternative (
  ID int(11) NOT NULL auto_increment,
  QuestionID int(11) default '0',
  Name char(100) default NULL,
  IsCorrect int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZQuiz_Alternative'
#

INSERT INTO eZQuiz_Alternative VALUES (1,1,'',0);
INSERT INTO eZQuiz_Alternative VALUES (2,2,'test 1',1);
INSERT INTO eZQuiz_Alternative VALUES (3,2,'test 2',0);

#
# Table structure for table 'eZQuiz_Answer'
#

DROP TABLE IF EXISTS eZQuiz_Answer;
CREATE TABLE eZQuiz_Answer (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default '0',
  AlternativeID int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZQuiz_Answer'
#


#
# Table structure for table 'eZQuiz_Game'
#

DROP TABLE IF EXISTS eZQuiz_Game;
CREATE TABLE eZQuiz_Game (
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  Description text,
  StartDate date default NULL,
  StopDate date default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZQuiz_Game'
#

INSERT INTO eZQuiz_Game VALUES (1,'test','wegwegweg','2001-12-12','0000-00-00');

#
# Table structure for table 'eZQuiz_Question'
#

DROP TABLE IF EXISTS eZQuiz_Question;
CREATE TABLE eZQuiz_Question (
  ID int(11) NOT NULL auto_increment,
  Name char(100) default NULL,
  GameID int(11) default '0',
  Placement int(11) default '0',
  Score int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZQuiz_Question'
#

INSERT INTO eZQuiz_Question VALUES (1,'hei å hå',1,0,0);
INSERT INTO eZQuiz_Question VALUES (2,'',1,1,0);

#
# Table structure for table 'eZQuiz_Score'
#

DROP TABLE IF EXISTS eZQuiz_Score;
CREATE TABLE eZQuiz_Score (
  ID int(11) NOT NULL auto_increment,
  GameID int(11) default '0',
  UserID int(11) default '0',
  TotalScore int(11) default '0',
  LastQuestion int(11) default '0',
  FinishedGame int(1) default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZQuiz_Score'
#

#
# Table structure for table 'eZQuiz_AllTimeScore'
#

DROP TABLE IF EXISTS eZQuiz_AllTimeScore;
CREATE TABLE eZQuiz_AllTimeScore (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default '0',
  TotalScore int(11) default '0',
  GamesPlayed int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZQuiz_AllTimeScore'
#


#
# Table structure for table 'eZSession_Preferences'
#

DROP TABLE IF EXISTS eZSession_Preferences;
CREATE TABLE eZSession_Preferences (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  Name char(50) default NULL,
  Value char(255) default NULL,
  GroupName char(50) default NULL,
  PRIMARY KEY (ID),
  KEY GroupName(GroupName,Name)
) TYPE=MyISAM;


alter table eZTrade_OrderOptionValue add RemoteID varchar(100) default ''; 



# Author list
create table eZUser_Author( ID int primary key auto_increment, Name char(255), EMail char(255) );

# Photographer list
create table eZUser_Photographer( ID int primary key auto_increment, Name char(255), EMail char(255) );

#
# convert old author fields to new
#

# create author list
insert into eZUser_Author( Name ) select AuthorText from eZArticle_Article Group By AuthorText;

# Create a temp table
CREATE TABLE eZArticle_ArticleTmp (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Contents text,
  ContentsWriterID int default NULL,
  LinkText varchar(50) default NULL,
  AuthorID int(11) NOT NULL default '0',
  Modified timestamp(14) NOT NULL,
  Created timestamp(14) NOT NULL,
  PageCount int(11) default NULL,
  IsPublished enum('true','false') default 'false',
  Published timestamp(14) NOT NULL,
  Keywords text,
  Discuss int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;


insert into eZArticle_ArticleTmp( ID, Name, Contents, ContentsWriterID, LinkText, AuthorID, Modified, 
Created, PageCount, IsPublished, Published, Keywords, Discuss ) 
select  
Article.ID,
Article.Name,
Article.Contents,
Author.ID,
Article.LinkText,
Article.AuthorID,
Article.Modified,
Article.Created,
Article.PageCount,
Article.IsPublished,
Article.Published,
Article.Keywords,
Article.Discuss
from eZArticle_Article as Article, eZUser_Author as Author where Article.AuthorText=Author.Name;

# rename tables
alter table eZArticle_Article rename eZArticle_Article_backup;
alter table eZArticle_ArticleTmp rename eZArticle_Article;


# IMPORTANT !!
# If you need to restore the article table restore 
# drop table eZArticle_Article;
# alter table eZArticle_Article_backup rename eZArticle_Article;
# if not you can delete the backup table 
# drop table eZArticle_Article_backup;


# Article topic
create table eZArticle_Topic( ID int primary key auto_increment, Name char(255), Description text );
alter table eZArticle_Article add TopicID int not null default 0;   


#
# Table structure for table 'eZArticle_Attribute'
#
DROP TABLE IF EXISTS eZArticle_Attribute;
CREATE TABLE eZArticle_Attribute (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  TypeID int(11),
  Name char(150),
  Placement int(11),
  Created timestamp(14),
  PRIMARY KEY (ID),
  INDEX( Placement )
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_Attribute'
#

#
# Table structure for table 'eZArticle_AttributeValue'
#
DROP TABLE IF EXISTS eZArticle_AttributeValue;
CREATE TABLE eZArticle_AttributeValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11),
  AttributeID int(11),
  Value text,
  PRIMARY KEY (ID),
  INDEX( ArticleID, AttributeID )
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_AttributeValue'
#

#
# Table structure for table 'eZArticle_Type'
#
DROP TABLE IF EXISTS eZArticle_Type;
CREATE TABLE eZArticle_Type (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_Type'
#

#
# Table structure for table 'eZArticle_ArticleTypeLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleTypeLink;
CREATE TABLE eZArticle_ArticleTypeLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11),
  TypeID int(11),
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_ArticleTypeLink'
#

alter table eZArticle_Topic add Created timestamp;   

create table eZMessage_Message( ID int primary key auto_increment, UserID int not null, Created timestamp, IsRead int not null default '0', Subject char(255) not null, Description text );

create table eZArticle_Log( ID int primary key auto_increment, ArticleID int not null, Created timestamp not null, Message text not null );     
alter table eZArticle_Log add UserID int not null;

alter table eZArticle_Article add StartDate timestamp default 0;  
alter table eZArticle_Article add StopDate timestamp default 0;

#alter table eZArticle_Category add SectionID int(11) default 0;

#
# Table structure for table 'eZArticle_ArticleFormDict'
#
DROP TABLE IF EXISTS eZArticle_ArticleFormDict;
CREATE TABLE eZArticle_ArticleFormDict (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11),
  FormID int(11),
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZImageCatalogue_ImageCategoryDefinition'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageCategoryDefinition;
CREATE TABLE eZImageCatalogue_ImageCategoryDefinition (
  ID int(11) NOT NULL auto_increment,
  ImageID int,
  CategoryID int,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Table structure for table 'eZImageCatalogue_ImageMap'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageMap;
CREATE TABLE eZImageCatalogue_ImageMap (
  ID int(11) NOT NULL auto_increment,
  ImageID int(11) default NULL,
  Link varchar(50) NOT NULL default '',
  AltText text default '',
  Shape int(11) NOT NULL default '0',
  StartPosX int(11) NOT NULL default '0',
  StartPosY int(11) NOT NULL default '0',
  EndPosX int(11) NOT NULL default '0',
  EndPosY int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# convert to new database types for database independence
#

# eZ session conversion
alter table eZSession_Session drop SecondLastAccessed;

alter table eZSession_Session add LastAccessedTmp int;
update eZSession_Session set LastAccessedTmp= UNIX_TIMESTAMP( LastAccessed );
alter table eZSession_Session drop LastAccessed; 
alter table eZSession_Session change LastAccessedTmp LastAccessed int; 

alter table eZSession_Session add CreatedTmp int;
update eZSession_Session set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZSession_Session drop Created; 
alter table eZSession_Session change CreatedTmp Created int;

# eZ user conversion
alter table eZUser_User add InfoSubscriptionTmp int default '0';
update eZUser_User set InfoSubscriptionTmp='1' where InfoSubscription='true';
alter table eZUser_User drop InfoSubscription;
alter table eZUser_User change InfoSubscriptionTmp InfoSubscription int;

alter table eZUser_Cookie add TimeTmp int;
update eZUser_Cookie set TimeTmp= UNIX_TIMESTAMP( Time );
alter table eZUser_Cookie drop Time; 
alter table eZUser_Cookie change TimeTmp Time int; 

alter table eZUser_Forgot add TimeTmp int;
update eZUser_Forgot set TimeTmp= UNIX_TIMESTAMP( Time );
alter table eZUser_Forgot drop Time; 
alter table eZUser_Forgot change TimeTmp Time int; 

alter table eZUser_GroupPermissionLink add IsEnabledTmp int default '0';
update eZUser_GroupPermissionLink set IsEnabledTmp='1' where IsEnabled='true';
alter table eZUser_GroupPermissionLink drop IsEnabled;
alter table eZUser_GroupPermissionLink change IsEnabledTmp IsEnabled int;

# eZ sitemanager

alter table eZSiteManager_Section add CreatedTmp int;
update eZSiteManager_Section set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZSiteManager_Section drop Created; 
alter table eZSiteManager_Section change CreatedTmp Created int;

# eZ link
alter table eZLink_Hit add TimeTmp int;
update eZLink_Hit set TimeTmp= UNIX_TIMESTAMP( Time );
alter table eZLink_Hit drop Time; 
alter table eZLink_Hit change TimeTmp Time int; 

alter table eZLink_Link add ModifiedTmp int;
update eZLink_Link set ModifiedTmp= UNIX_TIMESTAMP( Modified );
alter table eZLink_Link drop Modified; 
alter table eZLink_Link change ModifiedTmp Modified int; 

alter table eZLink_Link add AcceptedTmp int default '0';
update eZLink_Link set AcceptedTmp='1' where Accepted='y';
alter table eZLink_Link drop Accepted;
alter table eZLink_Link change AcceptedTmp Accepted int;

alter table eZLink_Link add CreatedTmp int;
update eZLink_Link set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZLink_Link drop Created; 
alter table eZLink_Link change CreatedTmp Created int; 

# eZ urltranslator
alter table eZURLTranslator_URL add CreatedTmp int;
update eZURLTranslator_URL set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZURLTranslator_URL drop Created; 
alter table eZURLTranslator_URL change CreatedTmp Created int; 


# eZ article
alter table eZArticle_Article add IsPublishedTmp int default '0';
update eZArticle_Article set IsPublishedTmp='1' where IsPublished='true';
alter table eZArticle_Article drop IsPublished;
alter table eZArticle_Article change IsPublishedTmp IsPublished int;
alter table eZArticle_Article add ImportID varchar(255); 


alter table eZArticle_Article add CreatedTmp int;
update eZArticle_Article set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_Article drop Created; 
alter table eZArticle_Article change CreatedTmp Created int; 

alter table eZArticle_Article add ModifiedTmp int;
update eZArticle_Article set ModifiedTmp= UNIX_TIMESTAMP( Modified );
alter table eZArticle_Article drop Modified; 
alter table eZArticle_Article change ModifiedTmp Modified int; 

alter table eZArticle_Article add PublishedTmp int;
update eZArticle_Article set PublishedTmp= UNIX_TIMESTAMP( Published );
alter table eZArticle_Article drop Published; 
alter table eZArticle_Article change PublishedTmp Published int; 

alter table eZArticle_Article add StartDateTmp int;
update eZArticle_Article set StartDateTmp= UNIX_TIMESTAMP( StartDate );
alter table eZArticle_Article drop StartDate; 
alter table eZArticle_Article change StartDateTmp StartDate int; 

alter table eZArticle_Article add StopDateTmp int;
update eZArticle_Article set StopDateTmp= UNIX_TIMESTAMP( StopDate );
alter table eZArticle_Article drop StopDate; 
alter table eZArticle_Article change StopDateTmp StopDate int; 


alter table eZArticle_ArticleFileLink add CreatedTmp int;
update eZArticle_ArticleFileLink set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_ArticleFileLink drop Created; 
alter table eZArticle_ArticleFileLink change CreatedTmp Created int; 

alter table eZArticle_ArticleImageLink add CreatedTmp int;
update eZArticle_ArticleImageLink set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_ArticleImageLink drop Created; 
alter table eZArticle_ArticleImageLink change CreatedTmp Created int; 


alter table eZArticle_Attribute add CreatedTmp int;
update eZArticle_Attribute set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_Attribute drop Created; 
alter table eZArticle_Attribute change CreatedTmp Created int; 

alter table eZArticle_Category add ExcludeFromSearchTmp int default '0';
update eZArticle_Category set ExcludeFromSearchTmp='1' where ExcludeFromSearch='true';
alter table eZArticle_Category drop ExcludeFromSearch;
alter table eZArticle_Category change ExcludeFromSearchTmp ExcludeFromSearch int;


alter table eZArticle_CategoryReaderLink add CreatedTmp int;
update eZArticle_CategoryReaderLink set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_CategoryReaderLink drop Created; 
alter table eZArticle_CategoryReaderLink change CreatedTmp Created int; 

alter table eZArticle_Log add CreatedTmp int;
update eZArticle_Log set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_Log drop Created; 
alter table eZArticle_Log change CreatedTmp Created int; 

alter table eZArticle_Topic add CreatedTmp int;
update eZArticle_Topic set CreatedTmp= UNIX_TIMESTAMP( Created );
alter table eZArticle_Topic drop Created; 
alter table eZArticle_Topic change CreatedTmp Created int; 


# eZ forum
alter table eZForum_Message add PostingTimeTmp int;
update eZForum_Message set PostingTimeTmp= UNIX_TIMESTAMP( PostingTime );
alter table eZForum_Message drop PostingTime; 
alter table eZForum_Message change PostingTimeTmp PostingTime int; 


# eZ poll

# rename field:
alter table eZPoll_PollChoice add Offs int;
update eZPoll_PollChoice set Offs=Offset;
alter table eZPoll_PollChoice drop Offset; 

alter table eZPoll_Poll add AnonymousTmp int default '0';
update eZPoll_Poll set AnonymousTmp='1' where Anonymous='true';
alter table eZPoll_Poll drop Anonymous;
alter table eZPoll_Poll change AnonymousTmp Anonymous int;

alter table eZPoll_Poll add IsEnabledTmp int default '0';
update eZPoll_Poll set IsEnabledTmp='1' where IsEnabled='true';
alter table eZPoll_Poll drop IsEnabled;
alter table eZPoll_Poll change IsEnabledTmp IsEnabled int;

alter table eZPoll_Poll add IsClosedTmp int default '0';
update eZPoll_Poll set IsClosedTmp='1' where IsClosed='true';
alter table eZPoll_Poll drop IsClosed;
alter table eZPoll_Poll change IsClosedTmp IsClosed int;

alter table eZPoll_Poll add ShowResultTmp int default '0';
update eZPoll_Poll set ShowResultTmp='1' where ShowResult='true';
alter table eZPoll_Poll drop ShowResult;
alter table eZPoll_Poll change ShowResultTmp ShowResult int;

# ez newfeed 

alter table eZNewsFeed_News add IsPublishedTmp int default '0';
update eZNewsFeed_News set IsPublishedTmp='1' where IsPublished='true';
alter table eZNewsFeed_News drop IsPublished;
alter table eZNewsFeed_News change IsPublishedTmp IsPublished int;

alter table eZNewsFeed_News add PublishingDateTmp int;
update eZNewsFeed_News set PublishingDateTmp= UNIX_TIMESTAMP( PublishingDate );
alter table eZNewsFeed_News drop PublishingDate; 
alter table eZNewsFeed_News change PublishingDateTmp PublishingDate int; 

alter table eZNewsFeed_News add OriginalPublishingDateTmp int;
update eZNewsFeed_News set OriginalPublishingDateTmp= UNIX_TIMESTAMP( OriginalPublishingDate );
alter table eZNewsFeed_News drop OriginalPublishingDate;
alter table eZNewsFeed_News change OriginalPublishingDateTmp OriginalPublishingDate int; 

alter table eZNewsFeed_SourceSite add IsActiveTmp int default '0';
update eZNewsFeed_SourceSite set IsActiveTmp='1' where IsActive='true';
alter table eZNewsFeed_SourceSite drop IsActive;
alter table eZNewsFeed_SourceSite change IsActiveTmp IsActive int; 

alter table eZNewsFeed_Category change Name Name varchar(150);

alter table eZNewsFeed_News change Name Name varchar(150);
alter table eZNewsFeed_News change KeyWords KeyWords varchar(200);
alter table eZNewsFeed_News change URL URL varchar(200);
alter table eZNewsFeed_News change Origin Origin varchar(150);

alter table eZNewsFeed_SourceSite change URL URL varchar(250);
alter table eZNewsFeed_SourceSite change Login Login varchar(30);
alter table eZNewsFeed_SourceSite change Password Password varchar(30);
alter table eZNewsFeed_SourceSite change Decoder Decoder varchar(50);


alter table eZImageCatalogue_Image add PhotographerID int;
alter table eZImageCatalogue_Image add Created int;


# Speed up listing of categories;

alter table eZArticle_ArticleCategoryLink add index ( ArticleID );
alter table eZArticle_ArticleCategoryLink add index ( CategoryID );
alter table eZArticle_ArticleCategoryLink add index ( Placement );

# Product type
alter table eZTrade_Product add ProductType int default 1;

# Attributes in eZLink

DROP TABLE IF EXISTS eZLink_Attribute;
CREATE TABLE eZLink_Attribute (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  TypeID int(11),
  Name varchar(150),
  Created int(11),
  Placement int(11) DEFAULT '0',
  Unit varchar(8),
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZLink_AttributeValue;
CREATE TABLE eZLink_AttributeValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  LinkID int(11),
  AttributeID int(11),
  Value char(200),
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZLink_Type;
CREATE TABLE eZLink_Type (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZLink_LinkType;
CREATE TABLE eZLink_TypeLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  LinkID int(11),
  TypeID int(11),
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_RequestedPage;
CREATE TABLE eZStats_Archive_RequestedPage (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Month int(11),
  URI char(250),
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_PageView;
CREATE TABLE eZStats_Archive_PageView (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Hour int(11),
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_UniqueVisits;
CREATE TABLE eZStats_Archive_UniqueVisits (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Day int(11),
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_BrowserType;
CREATE TABLE eZStats_Archive_BrowserType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Browser char(250),
  Count int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_RefererURL;
CREATE TABLE eZStats_Archive_RefererURL (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Month int(11),
  Domain char(100) default NULL,
  URI char(200) default NULL,
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_RemoteHost;
CREATE TABLE eZStats_Archive_RemoteHost (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  IP char(15) default NULL,
  HostName char(150) default NULL,
  Count int DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZStats_Archive_Users;
CREATE TABLE eZStats_Archive_Users (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  Month int(11),
  Count int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY(ID)
);

INSERT INTO eZLink_LinkCategoryLink ( LinkID, CategoryID ) SELECT ID, LinkGroup from eZLink_Link;
INSERT INTO eZLink_LinkCategoryDefinition ( LinkID, CategoryID ) SELECT ID, LinkGroup from eZLink_Link;
ALTER TABLE eZLink_Link DROP LinkGroup;

alter table eZLink_Link change Title Name varchar(100);
ALTER TABLE eZLink_Category add ImageID int;
ALTER TABLE eZLink_Category add Description varchar(200);

# eZBulkMail
# eZ forum
alter table eZBulkMail_Mail add SentDateTmp int;
update eZBulkMail_Mail set SentDateTmp= UNIX_TIMESTAMP( SentDate );
alter table eZBulkMail_Mail drop SentDate; 
alter table eZBulkMail_Mail change SentDateTmp SentDate int; 

alter table eZBulkMail_SentLog add SentDateTmp int;
update eZBulkMail_SentLog set SentDateTmp= UNIX_TIMESTAMP( SentDate );
alter table eZBulkMail_SentLog drop SentDate; 
alter table eZBulkMail_SentLog change SentDateTmp SentDate int; 

alter table eZBulkMail_Forgot add TimeTmp int;
update eZBulkMail_Forgot set TimeTmp= UNIX_TIMESTAMP( Time );
alter table eZBulkMail_Forgot drop Time; 
alter table eZBulkMail_Forgot change TimeTmp Time int; 


## fulltext search index tables
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


CREATE INDEX ArticleWord_Word ON eZArticle_Word (Word);
CREATE INDEX ArticleWordLink_ArticleID ON eZArticle_ArticleWordLink (ArticleID);
CREATE INDEX ArticleWordLink_WordID ON eZArticle_ArticleWordLink (WordID);

CREATE INDEX ArticlePermissionObjectID ON eZArticle_ArticlePermission (ObjectID);
CREATE INDEX ArticlePermissionGroupID ON eZArticle_ArticlePermission (GroupID);
CREATE INDEX ArticlePermissionWritePermission ON eZArticle_ArticlePermission (WritePermission);
CREATE INDEX ArticlePermissionReadPermission ON eZArticle_ArticlePermission (ReadPermission);


CREATE INDEX Article_Name ON eZArticle_Article (Name);
CREATE INDEX Article_Published ON eZArticle_Article (Published);

CREATE INDEX Link_ArticleID ON eZArticle_ArticleCategoryLink (ArticleID);
CREATE INDEX Link_CategoryID ON eZArticle_ArticleCategoryLink (CategoryID);
CREATE INDEX Link_Placement ON eZArticle_ArticleCategoryLink (Placement);

CREATE INDEX Def_ArticleID ON eZArticle_ArticleCategoryDefinition (ArticleID);
CREATE INDEX Def_CategoryID ON eZArticle_ArticleCategoryDefinition (CategoryID);

# eZ mediacatalogue

DROP TABLE IF EXISTS eZMediaCatalouge_Category;
CREATE TABLE eZMediaCatalouge_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11),
  UserID int(11),
  WritePermission int(11) DEFAULT '1',
  ReadPermission int(11) DEFAULT '1',
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS eZMediaCatalouge_CategoryPermission;
CREATE TABLE eZMediaCatalouge_CategoryPermission (
  ID int(11) NOT NULL auto_increment,
  ObjectID int(11) default NULL,
  GroupID int(11) default NULL,
  ReadPermission int(11) default '0',
  WritePermission int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

DROP TABLE IF EXISTS eZUser_Trustees;
CREATE TABLE eZUser_Trustees (
  ID int(11) NOT NULL auto_increment,
  OwnerID int(11) NOT NULL,
  UserID int(11) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

