# MySQL dump 7.1
#
# Host: localhost    Database: ezlink
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'Hit'
#
CREATE TABLE Hit (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Link int(11),
  Time timestamp(14),
  RemoteIP char(15),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'Link'
#
CREATE TABLE Link (
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
# Table structure for table 'LinkGroup'
#
CREATE TABLE LinkGroup (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11) DEFAULT '0',
  Title char(100),
  PRIMARY KEY (ID)
);

