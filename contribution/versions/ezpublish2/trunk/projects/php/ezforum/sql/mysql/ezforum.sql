CREATE TABLE eZForum_Category (
  Name varchar(20) default NULL,
  Description varchar(40) default NULL,
  IsPrivate int default NULL,
  ID int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZForum_Forum (
  Name varchar(20) NOT NULL default '',
  Description varchar(40) default NULL,
  IsPrivate int default NULL,
  ID int NOT NULL,
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
  ForumID int NOT NULL default '0',
  Topic varchar(60) default NULL,
  Body text,
  UserName varchar default NULL,
  UserID int default NULL,
  Parent int default NULL,
  EmailNotice int NOT NULL default '0',
  PostingTime int NOT NULL,
  TreeID int default NULL,
  ThreadID int default NULL,
  Depth int default NULL,
  ID int NOT NULL,
  IsApproved int NOT NULL default '1',
  IsTemporary int NOT NULL default '0',
  PRIMARY KEY (ID)
);
