#
# Table structure for table 'eZBug_Bug'
#
DROP TABLE IF EXISTS eZBug_Bug;
CREATE TABLE eZBug_Bug (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  UserID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  IsHandled enum('true','false') DEFAULT 'false' NOT NULL,
  PriorityID int(11) DEFAULT '0' NOT NULL,
  StatusID int(11) DEFAULT '0' NOT NULL,
  IsClosed enum('true','false') DEFAULT 'false',
  UserEmail varchar(100) DEFAULT '',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Bug'
#

INSERT INTO eZBug_Bug VALUES (1,'Help!','It dosent work!',33,20010125202931,'false',0,0,'','');

#
# Table structure for table 'eZBug_BugCategoryLink'
#
DROP TABLE IF EXISTS eZBug_BugCategoryLink;
CREATE TABLE eZBug_BugCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  BugID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_BugCategoryLink'
#

INSERT INTO eZBug_BugCategoryLink VALUES (1,2,1);

#
# Table structure for table 'eZBug_BugModuleLink'
#
DROP TABLE IF EXISTS eZBug_BugModuleLink;
CREATE TABLE eZBug_BugModuleLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ModuleID int(11),
  BugID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_BugModuleLink'
#

INSERT INTO eZBug_BugModuleLink VALUES (1,1,1);

#
# Table structure for table 'eZBug_Category'
#
DROP TABLE IF EXISTS eZBug_Category;
CREATE TABLE eZBug_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Category'
#

INSERT INTO eZBug_Category VALUES (1,'GUI','');
INSERT INTO eZBug_Category VALUES (2,'Feature request','');

#
# Table structure for table 'eZBug_Log'
#
DROP TABLE IF EXISTS eZBug_Log;
CREATE TABLE eZBug_Log (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  BugID int(11) DEFAULT '0' NOT NULL,
  UserID int(11) DEFAULT '0' NOT NULL,
  Description text,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Log'
#




#
# Table structure for table 'eZBug_Module'
#
DROP TABLE IF EXISTS eZBug_Module;
CREATE TABLE eZBug_Module (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ParentID int(11),
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Module'
#

INSERT INTO eZBug_Module VALUES (1,0,'My program','');

#
# Table structure for table 'eZBug_Priority'
#
DROP TABLE IF EXISTS eZBug_Priority;
CREATE TABLE eZBug_Priority (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(150) DEFAULT '' NOT NULL,
  Value int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Priority'
#

INSERT INTO eZBug_Priority VALUES (1,'High',NULL);
INSERT INTO eZBug_Priority VALUES (2,'Middels',NULL);
INSERT INTO eZBug_Priority VALUES (3,'Low',NULL);

#
# Table structure for table 'eZBug_Status'
#
DROP TABLE IF EXISTS eZBug_Status;
CREATE TABLE eZBug_Status (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(150) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Status'
#

INSERT INTO eZBug_Status VALUES (1,'Fixed');

