CREATE TABLE eZBug_Bug (
  ID int DEFAULT '0' NOT NULL,
  Name varchar(150),
  Description text,
  UserID int DEFAULT '0' NOT NULL,
  Created int,
  IsHandled int DEFAULT '0' NOT NULL,
  PriorityID int DEFAULT '0' NOT NULL,
  StatusID int DEFAULT '0' NOT NULL,
  IsClosed int DEFAULT '0',
  Version varchar(150) DEFAULT '',
  UserEmail varchar(100) DEFAULT '',
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_BugCategoryLink (
  ID int DEFAULT '0' NOT NULL,
  CategoryID int,
  BugID int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_BugModuleLink (
  ID int DEFAULT '0' NOT NULL,
  ModuleID int,
  BugID int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_Category (
  ID int DEFAULT '0' NOT NULL,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_Log (
  ID int DEFAULT '0' NOT NULL,
  BugID int DEFAULT '0' NOT NULL,
  UserID int DEFAULT '0' NOT NULL,
  Description text,
  Created int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_Module (
  ID int DEFAULT '0' NOT NULL,
  ParentID int,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_Priority (
  ID int DEFAULT '0' NOT NULL,
  Name char(150) DEFAULT '' NOT NULL,
  Value int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_Status (
  ID int DEFAULT '0' NOT NULL,
  Name char(150) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);
