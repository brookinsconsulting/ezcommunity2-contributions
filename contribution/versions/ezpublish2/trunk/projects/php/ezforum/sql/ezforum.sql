#
# Table structure for table 'eZForum_Category'
#
CREATE TABLE eZForum_Category (
  Name varchar(20),
  Description varchar(40),
  Private enum('Y','N') DEFAULT 'N',
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Category'
#

INSERT INTO eZForum_Category VALUES ('Talk center','General talk','N',1);

#
# Table structure for table 'eZForum_Forum'
#
CREATE TABLE eZForum_Forum (
  Name varchar(20) DEFAULT '' NOT NULL,
  Description varchar(40),
  Moderated enum('Y','N') DEFAULT 'N',
  Private enum('Y','N') DEFAULT 'N',
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Forum'
#

INSERT INTO eZForum_Forum VALUES ('Discussion','Discuss everything here','','',1);
INSERT INTO eZForum_Forum VALUES ('Special talk','Talk about something else here','','',2);
INSERT INTO eZForum_Forum VALUES ('Demo article','','','',3);
INSERT INTO eZForum_Forum VALUES ('eZ publish introduct','','','',4);
INSERT INTO eZForum_Forum VALUES ('About eZ publish','','','',6);

#
# Table structure for table 'eZForum_ForumCategoryLink'
#
CREATE TABLE eZForum_ForumCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ForumID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_ForumCategoryLink'
#

INSERT INTO eZForum_ForumCategoryLink VALUES (1,1,1);
INSERT INTO eZForum_ForumCategoryLink VALUES (2,2,1);

#
# Table structure for table 'eZForum_Message'
#
CREATE TABLE eZForum_Message (
  ForumID int(11) DEFAULT '0' NOT NULL,
  Topic varchar(60),
  Body text,
  UserID int(11),
  Parent int(11),
  EmailNotice enum('N','Y') DEFAULT 'N',
  PostingTime timestamp(14),
  TreeID int(11),
  ThreadID int(11),
  Depth int(11),
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Message'
#

INSERT INTO eZForum_Message VALUES (1,'First post!','This is the first post!',27,0,'N',20001101195844,1,0,0,1);
INSERT INTO eZForum_Message VALUES (1,'SV: First post!','> This is the first post!\r\nThis is the reply!',27,1,'N',20001101195844,0,0,1,2);
INSERT INTO eZForum_Message VALUES (2,'First post!','YES!',27,0,'N',20001101200642,3,1,0,3);
INSERT INTO eZForum_Message VALUES (2,'RE: First post!','> YES!\r\nhmm',27,3,'N',20001101200642,2,1,1,4);
