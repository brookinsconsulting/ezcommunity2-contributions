#
# Table structure for table 'eZLink_Category'
#
DROP TABLE IF EXISTS eZLink_Category;
CREATE TABLE eZLink_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11) DEFAULT '0',
  Name char(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_Category'
#

INSERT INTO eZLink_Category VALUES (1,0,'Cool links');
INSERT INTO eZLink_Category VALUES (2,0,'Not so cool links');

#
# Table structure for table 'eZLink_Hit'
#
DROP TABLE IF EXISTS eZLink_Hit;
CREATE TABLE eZLink_Hit (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Link int(11),
  Time timestamp(14),
  RemoteIP char(15),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_Hit'
#




#
# Table structure for table 'eZLink_Link'
#
DROP TABLE IF EXISTS eZLink_Link;
CREATE TABLE eZLink_Link (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Title varchar(100),
  Description text,
  LinkGroup int(11),
  KeyWords varchar(100),
  Modified timestamp(14),
  Accepted enum('Y','N'),
  Created datetime,
  Url varchar(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_Link'
#

INSERT INTO eZLink_Link VALUES (1,'eZ systems as','Test!',1,'Test!',20010125160958,'Y','2001-01-25 16:09:58','ez.no');

#
# Table structure for table 'eZLink_LinkCategoryDefinition'
#
DROP TABLE IF EXISTS eZLink_LinkCategoryDefinition;
CREATE TABLE eZLink_LinkCategoryDefinition (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  LinkID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_LinkCategoryDefinition'
#

INSERT INTO eZLink_LinkCategoryDefinition VALUES (1,1,1);

#
# Table structure for table 'eZLink_LinkCategoryLink'
#
DROP TABLE IF EXISTS eZLink_LinkCategoryLink;
CREATE TABLE eZLink_LinkCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  LinkID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_LinkCategoryLink'
#

INSERT INTO eZLink_LinkCategoryLink VALUES (1,1,1);

#
# Table structure for table 'eZLink_LinkGroup'
#
DROP TABLE IF EXISTS eZLink_LinkGroup;
CREATE TABLE eZLink_LinkGroup (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11) DEFAULT '0',
  Title char(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_LinkGroup'
#

INSERT INTO eZLink_LinkGroup VALUES (1,0,'Cool links');

