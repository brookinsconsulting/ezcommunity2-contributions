CREATE TABLE eZForum_Category (
  ID int NOT NULL,
  Name varchar(20) default NULL,
  Description varchar(40) default NULL,
  IsPrivate int default NULL,
  SectionID int default 1,
  PRIMARY KEY (ID)
);


CREATE TABLE eZForum_Forum (
  ID int NOT NULL,
  Name varchar(20) NOT NULL default '',
  Description varchar(40) default NULL,
  IsPrivate int default NULL,
  ModeratorID int NOT NULL default '0',
  IsModerated int NOT NULL default '0',
  GroupID int default '0',
  IsAnonymous int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_ForumCategoryLink (
  ID int NOT NULL,
  ForumID int NOT NULL default '0',
  CategoryID int NOT NULL default '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_Message (
  ID int NOT NULL,
  ForumID int NOT NULL default '0',
  Topic varchar(60) default NULL,
  Body text,
  UserName varchar(60) default NULL,
  UserID int default NULL,
  Parent int default NULL,
  EmailNotice int NOT NULL default '0',
  PostingTime int NOT NULL,
  TreeID int default NULL,
  ThreadID int default NULL,
  Depth int default NULL,
  IsApproved int NOT NULL default '1',
  IsTemporary int NOT NULL default '0',
  PRIMARY KEY (ID)
);


CREATE TABLE eZForum_MessageWordLink (
  MessageID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  WordID int(11) NOT NULL default '0'
);


CREATE TABLE eZForum_Word (
  ID int(11) NOT NULL default '0',
  Frequency float default 0.2,
  Word varchar(50) NOT NULL default ''
);


CREATE INDEX Forum_PostingTime ON eZForum_Message (PostingTime);
CREATE INDEX Forum_TreeID ON eZForum_Message (ThreeID);
CREATE INDEX Forum_ThreadID ON eZForum_Message (ThreadID);
CREATE INDEX Forum_Depth ON eZForum_Message (Depth);
CREATE INDEX Forum_ForumID ON eZForum_Message (ForumID);

CREATE INDEX Forum_PostingTime ON eZForum_Message (PostingTime);
CREATE INDEX ForumWordLink_MessageID ON eZForum_MessageWordLink (MessageID);
CREATE INDEX ForumWordLink_WordID ON eZForum_MessageWordLink (WordID);
CREATE INDEX ForumWord_Word ON eZForum_Word (Word);
CREATE UNIQUE INDEX ForumWord_ID ON eZForum_Word (ID);


