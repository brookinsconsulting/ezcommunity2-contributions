CREATE TABLE eZBug_Bug (
  ID int NOT NULL,
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
  OwnerID int default NULL,
  IsPrivate int default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Bug VALUES (1,'Help!','It dosent work!',33,997357856,0,0,0,'','','','','0');

CREATE TABLE eZBug_BugCategoryLink (
  ID int NOT NULL,
  CategoryID int,
  BugID int,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_BugCategoryLink VALUES (1,2,1);

CREATE TABLE eZBug_BugFileLink (
  ID int NOT NULL,
  BugID int NOT NULL default '0',
  FileID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_BugImageLink (
  ID int NOT NULL,
  BugID int NOT NULL default '0',
  ImageID int NOT NULL default '0',
  Created int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZBug_BugModuleLink (
  ID int NOT NULL,
  ModuleID int,
  BugID int,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_BugModuleLink VALUES (1,1,1);

CREATE TABLE eZBug_Category (
  ID int NOT NULL,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Category VALUES (1,'GUI','');
INSERT INTO eZBug_Category VALUES (2,'Feature request','');

CREATE TABLE eZBug_Log (
  ID int NOT NULL,
  BugID int DEFAULT '0' NOT NULL,
  UserID int DEFAULT '0' NOT NULL,
  Description text,
  Created int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZBug_Module (
  ID int NOT NULL,
  ParentID int,
  Name varchar(150),
  Description text,
  OwnerGroupID int default '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Module VALUES (1,0,'My program',1);

CREATE TABLE eZBug_Priority (
  ID int NOT NULL,
  Name varchar(150) DEFAULT '' NOT NULL,
  Value int,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Priority VALUES (1,'High',NULL);
INSERT INTO eZBug_Priority VALUES (2,'Middels',NULL);
INSERT INTO eZBug_Priority VALUES (3,'Low',NULL);

CREATE TABLE eZBug_Status (
  ID int NOT NULL,
  Name varchar(150) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Status VALUES (1,'Fixed');

CREATE TABLE eZBug_ModulePermission (
  ID int NOT NULL,
  ObjectID int default NULL,
  GroupID int default NULL,
  ReadPermission int default '0',
  WritePermission int default '0',
  PRIMARY KEY (ID)
);
