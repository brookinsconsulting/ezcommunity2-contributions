CREATE TABLE eZForum_Category (
  Name varchar(20) default NULL,
  Description varchar(40) default NULL,
  IsPrivate int default NULL,
  ID int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZForum_Forum (
  Name varchar(20) NOT NULL,
  Description varchar(40) not null,
  IsPrivate int default NULL,
  ID int NOT NULL,
  ModeratorID int NOT NULL,
  IsModerated int NOT NULL,
  GroupID int,
  IsAnonymous int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_ForumCategoryLink (
  ID int NOT NULL,
  ForumID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZForum_Message (
  ForumID int NOT NULL,
  Topic varchar(100) default NULL,
  Body lvarchar,
  UserID int default NULL,
  Parent int default NULL,
  EmailNotice int NOT NULL,
  PostingTime int NOT NULL,
  TreeID int default NULL,
  ThreadID int default NULL,
  Depth int default NULL,
  ID int NOT NULL,
  IsApproved int NOT NULL,
  IsTemporary int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE INDEX  ForumMessageTopic ON eZForum_Message (Topic);
CREATE INDEX  ForumMessageTreeID ON eZForum_Message (TreeID);
CREATE INDEX  ForumMessageThreadID ON eZForum_Message (ThreadID);
CREATE INDEX  ForumMessageDepth ON eZForum_Message (Depth);
CREATE INDEX  ForumMessageIsApproved ON eZForum_Message (IsApproved);
CREATE INDEX  ForumMessageParent ON eZForum_Message (Parent);
CREATE INDEX  ForumMessageForumID ON eZForum_Message (ForumID);
