#
# Table structure for table 'eZUser_Forgot'
#
DROP TABLE IF EXISTS eZUser_Forgot;
CREATE TABLE eZUser_Forgot (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  Hash char(33),
  Time timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_Forgot'
#




#
# Table structure for table 'eZUser_Group'
#
DROP TABLE IF EXISTS eZUser_Group;
CREATE TABLE eZUser_Group (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  SessionTimeout int(11) DEFAULT '60',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_Group'
#

INSERT INTO eZUser_Group VALUES (2,'Anonymous','Users that register themself on the user page, eg forum users.',60);
INSERT INTO eZUser_Group VALUES (1,'Administrators','All rights',7200);

#
# Table structure for table 'eZUser_GroupPermissionLink'
#
DROP TABLE IF EXISTS eZUser_GroupPermissionLink;
CREATE TABLE eZUser_GroupPermissionLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  GroupID int(11),
  PermissionID int(11),
  IsEnabled enum('true','false'),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_GroupPermissionLink'
#

INSERT INTO eZUser_GroupPermissionLink VALUES (1,1,1,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (2,1,2,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (3,1,3,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (4,1,4,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (5,1,5,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (6,1,6,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (7,1,8,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (8,2,1,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (9,2,2,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (10,2,3,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (11,2,4,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (12,2,5,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (13,2,6,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (14,2,8,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (21,1,15,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (20,1,14,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (19,1,13,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (18,1,12,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (17,1,11,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (16,1,10,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (15,1,9,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (22,1,16,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (23,1,17,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (24,1,18,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (25,1,19,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (26,1,20,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (27,1,21,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (28,1,22,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (29,1,23,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (30,1,24,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (31,1,25,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (32,1,26,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (33,1,27,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (34,1,28,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (35,1,29,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (36,1,30,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (37,1,31,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (38,1,32,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (39,1,33,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (40,1,34,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (74,1,35,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (75,1,36,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (76,1,37,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (77,1,38,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (78,1,39,'true');

#
# Table structure for table 'eZUser_Module'
#
DROP TABLE IF EXISTS eZUser_Module;
CREATE TABLE eZUser_Module (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(100) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE Name (Name)
);

#
# Dumping data for table 'eZUser_Module'
#

INSERT INTO eZUser_Module VALUES (1,'eZTrade');
INSERT INTO eZUser_Module VALUES (2,'eZPoll');
INSERT INTO eZUser_Module VALUES (3,'eZUser');
INSERT INTO eZUser_Module VALUES (4,'eZTodo');
INSERT INTO eZUser_Module VALUES (5,'eZNews');
INSERT INTO eZUser_Module VALUES (6,'eZContact');
INSERT INTO eZUser_Module VALUES (7,'eZForum');
INSERT INTO eZUser_Module VALUES (8,'eZLink');
INSERT INTO eZUser_Module VALUES (9,'eZFileManager');
INSERT INTO eZUser_Module VALUES (10,'eZImageCatalogue');

#
# Table structure for table 'eZUser_Permission'
#
DROP TABLE IF EXISTS eZUser_Permission;
CREATE TABLE eZUser_Permission (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ModuleID int(11),
  Name char(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_Permission'
#

INSERT INTO eZUser_Permission VALUES (1,3,'UserAdd');
INSERT INTO eZUser_Permission VALUES (2,3,'UserDelete');
INSERT INTO eZUser_Permission VALUES (3,3,'UserModify');
INSERT INTO eZUser_Permission VALUES (4,3,'GroupDelete');
INSERT INTO eZUser_Permission VALUES (5,3,'GroupAdd');
INSERT INTO eZUser_Permission VALUES (6,3,'GroupModify');
INSERT INTO eZUser_Permission VALUES (11,8,'LinkGroupModify');
INSERT INTO eZUser_Permission VALUES (8,3,'AdminLogin');
INSERT INTO eZUser_Permission VALUES (10,8,'LinkGroupAdd');
INSERT INTO eZUser_Permission VALUES (9,8,'LinkGroupDelete');
INSERT INTO eZUser_Permission VALUES (12,8,'LinkModify');
INSERT INTO eZUser_Permission VALUES (13,8,'LinkAdd');
INSERT INTO eZUser_Permission VALUES (14,8,'LinkDelete');
INSERT INTO eZUser_Permission VALUES (15,7,'CategoryAdd');
INSERT INTO eZUser_Permission VALUES (16,7,'CategoryModify');
INSERT INTO eZUser_Permission VALUES (17,7,'CategoryDelete');
INSERT INTO eZUser_Permission VALUES (18,7,'ForumDelete');
INSERT INTO eZUser_Permission VALUES (19,7,'ForumAdd');
INSERT INTO eZUser_Permission VALUES (20,7,'ForumModify');
INSERT INTO eZUser_Permission VALUES (21,7,'MessageModify');
INSERT INTO eZUser_Permission VALUES (22,7,'MessageAdd');
INSERT INTO eZUser_Permission VALUES (23,7,'MessageDelete');
INSERT INTO eZUser_Permission VALUES (24,6,'PersonAdd');
INSERT INTO eZUser_Permission VALUES (25,6,'CompanyAdd');
INSERT INTO eZUser_Permission VALUES (26,6,'TypeAdd');
INSERT INTO eZUser_Permission VALUES (27,6,'PersonDelete');
INSERT INTO eZUser_Permission VALUES (28,6,'CompanyDelete');
INSERT INTO eZUser_Permission VALUES (29,6,'TypeDelete');
INSERT INTO eZUser_Permission VALUES (30,6,'PersonModify');
INSERT INTO eZUser_Permission VALUES (31,6,'CompanyModify');
INSERT INTO eZUser_Permission VALUES (32,6,'TypeModify');
INSERT INTO eZUser_Permission VALUES (33,6,'PersonView');
INSERT INTO eZUser_Permission VALUES (34,6,'PersonList');
INSERT INTO eZUser_Permission VALUES (35,3,'UserLogin');
INSERT INTO eZUser_Permission VALUES (36,9,'WriteToRoot');
INSERT INTO eZUser_Permission VALUES (37,9,'WritePermission');
INSERT INTO eZUser_Permission VALUES (38,10,'WritePermission');
INSERT INTO eZUser_Permission VALUES (39,10,'WriteToRoot');

#
# Table structure for table 'eZUser_User'
#
DROP TABLE IF EXISTS eZUser_User;
CREATE TABLE eZUser_User (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Login varchar(50) DEFAULT '' NOT NULL,
  Password varchar(50) DEFAULT '' NOT NULL,
  Email varchar(50),
  FirstName varchar(50),
  LastName varchar(50),
  InfoSubscription enum('true','false') DEFAULT 'false',
  SimultaneousLogins int(11) DEFAULT '0' NOT NULL,
  Signature text NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE Login (Login)
);

#
# Dumping data for table 'eZUser_User'
#

INSERT INTO eZUser_User VALUES (27,'admin','0c947f956f7aa781','bf@ez.no','admin','user','false','');

#
# Table structure for table 'eZUser_UserAddressLink'
#
DROP TABLE IF EXISTS eZUser_UserAddressLink;
CREATE TABLE eZUser_UserAddressLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_UserAddressLink'
#

INSERT INTO eZUser_UserAddressLink VALUES (1,27,1);

#
# Table structure for table 'eZUser_UserGroupLink'
#
DROP TABLE IF EXISTS eZUser_UserGroupLink;
CREATE TABLE eZUser_UserGroupLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  GroupID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_UserGroupLink'
#

INSERT INTO eZUser_UserGroupLink VALUES (52,27,1);

