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
# Table structure for table 'eZLink_LinkGroup'
#
DROP TABLE IF EXISTS eZLink_LinkGroup;
CREATE TABLE eZLink_LinkGroup (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11) DEFAULT '0',
  Title char(100),
  PRIMARY KEY (ID)
);
