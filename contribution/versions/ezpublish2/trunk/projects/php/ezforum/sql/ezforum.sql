#
# Table structure for table 'eZForum_Category'
#
DROP TABLE IF EXISTS eZForum_Category;
CREATE TABLE eZForum_Category (
  Name varchar(20),
  Description varchar(40),
  IsPrivate int(1) DEFAULT '0' NOT NULL,
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Category'
#

INSERT INTO eZForum_Category VALUES ('Talk center','General talk','0',1);

#
# Table structure for table 'eZForum_Forum'
#
DROP TABLE IF EXISTS eZForum_Forum;
CREATE TABLE eZForum_Forum (
  Name varchar(20) DEFAULT '' NOT NULL,
  Description varchar(40),
  IsPrivate int(1) DEFAULT '0' NOT NULL,
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ModeratorID int(11) DEFAULT '0' NOT NULL,
  IsModerated int(1) DEFAULT '0' NOT NULL,
  IsAnonymous int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Forum'
#

INSERT INTO eZForum_Forum VALUES ('Discussion','Discuss everything here','',1,27,0, 0);
INSERT INTO eZForum_Forum VALUES ('Demo article','','',2,0,0, 0);
INSERT INTO eZForum_Forum VALUES ('What is New?','','',3,0,0, 0);
INSERT INTO eZForum_Forum VALUES ('What can eZ publish','','',4,0,0, 0);
INSERT INTO eZForum_Forum VALUES ('eZ Newsfeed','','',5,0,0, 0);
INSERT INTO eZForum_Forum VALUES ('eZ Article','','',6,0,0, 0);

#
# Table structure for table 'eZForum_ForumCategoryLink'
#
DROP TABLE IF EXISTS eZForum_ForumCategoryLink;
CREATE TABLE eZForum_ForumCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ForumID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_ForumCategoryLink'
#

INSERT INTO eZForum_ForumCategoryLink VALUES (3,1,1);

#
# Table structure for table 'eZForum_Message'
#
DROP TABLE IF EXISTS eZForum_Message;
CREATE TABLE eZForum_Message (
  ForumID int(11) DEFAULT '0' NOT NULL,
  Topic varchar(60),
  Body text,
  UserID int(11),
  Parent int(11),
  EmailNotice int(1) DEFAULT '0' NOT NULL,
  PostingTime timestamp(14),
  TreeID int(11),
  ThreadID int(11),
  Depth int(11),
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  IsApproved int(1) DEFAULT '1' NOT NULL,
  IsTemporary int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Message'
#

INSERT INTO eZForum_Message VALUES (1,'First post!','This is the first post!',27,0,'N',20010122104742,1,0,0,1,1,0);
INSERT INTO eZForum_Message VALUES (1,'SV: First post!','> This is the first post!\r\nThis is the reply!',27,1,'N',20010122104747,0,0,1,2,1,0);

