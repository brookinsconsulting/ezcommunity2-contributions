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

alter table eZArticle_Category add SectionID int(11) default 0;

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
# Dumping data for table 'eZArticle_ArticleFormDict'
#

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
