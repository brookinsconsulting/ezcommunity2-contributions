#
# Table structure for table 'eZUser_Group'
#
DROP TABLE IF EXISTS eZUser_Group;
CREATE TABLE eZUser_Group (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  PRIMARY KEY (ID)
);

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
# Table structure for table 'eZUser_User'
#
DROP TABLE IF EXISTS eZUser_User;
CREATE TABLE eZUser_User (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Login char(50) DEFAULT '' NOT NULL,
  Password char(50) DEFAULT '' NOT NULL,
  Email char(50),
  FirstName char(50),
  LastName char(50),
  PRIMARY KEY (ID),
  UNIQUE Login (Login)
);

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
# Table structure for table 'eZUser_UserGroupLink'
#
DROP TABLE IF EXISTS eZUser_UserGroupLink;
CREATE TABLE eZUser_UserGroupLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  GroupID int(11),
  PRIMARY KEY (ID)
);

