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
