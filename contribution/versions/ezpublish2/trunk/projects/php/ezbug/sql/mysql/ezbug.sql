CREATE TABLE eZBug_Bug (
  ID int(11) NOT NULL,
  Name varchar(150),
  Description text,
  UserID int(11) DEFAULT '0' NOT NULL,
  Created int,
  IsHandled int DEFAULT '0' NOT NULL,
  PriorityID int(11) DEFAULT '0' NOT NULL,
  StatusID int(11) DEFAULT '0' NOT NULL,
  IsClosed int DEFAULT '0',
  Version varchar(150) DEFAULT '',
  UserEmail varchar(100) DEFAULT '',
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Bug VALUES (1,'Help!','It dosent work!',33,997357856,0,0,0,'','','');

CREATE TABLE eZBug_BugCategoryLink (
  ID int(11) NOT NULL,
  CategoryID int(11),
  BugID int(11),
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_BugCategoryLink VALUES (1,2,1);

CREATE TABLE eZBug_BugModuleLink (
  ID int(11) NOT NULL,
  ModuleID int(11),
  BugID int(11),
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_BugModuleLink VALUES (1,1,1);

CREATE TABLE eZBug_Category (
  ID int(11) NOT NULL,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Category VALUES (1,'GUI','');
INSERT INTO eZBug_Category VALUES (2,'Feature request','');

CREATE TABLE eZBug_Log (
  ID int(11) NOT NULL,
  BugID int(11) DEFAULT '0' NOT NULL,
  UserID int(11) DEFAULT '0' NOT NULL,
  Description text,
  Created int,
  PRIMARY KEY (ID)
);


CREATE TABLE eZBug_Module (
  ID int(11) NOT NULL,
  ParentID int(11),
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Module VALUES (1,0,'My program','');

CREATE TABLE eZBug_Priority (
  ID int(11) NOT NULL,
  Name char(150) DEFAULT '' NOT NULL,
  Value int(11),
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Priority VALUES (1,'High',NULL);
INSERT INTO eZBug_Priority VALUES (2,'Middels',NULL);
INSERT INTO eZBug_Priority VALUES (3,'Low',NULL);

CREATE TABLE eZBug_Status (
  ID int(11) NOT NULL,
  Name char(150) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZBug_Status VALUES (1,'Fixed');

