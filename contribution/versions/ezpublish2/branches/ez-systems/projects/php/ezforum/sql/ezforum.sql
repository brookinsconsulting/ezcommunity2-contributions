# MySQL dump 7.1
#
# Host: localhost    Database: ezforum
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'AclTable'
#
CREATE TABLE AclTable (
  Id int(11) DEFAULT '0' NOT NULL auto_increment,
  UserId int(11) DEFAULT '0' NOT NULL,
  ForumId int(11) DEFAULT '0' NOT NULL,
  CategoryId int(11) DEFAULT '0' NOT NULL,
  Rights set('READ','WRITE','MODIFY','DELETE'),
  PRIMARY KEY (Id)
);

#
# Dumping data for table 'AclTable'
#


#
# Table structure for table 'CategoryTable'
#
CREATE TABLE CategoryTable (
  Id int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(20),
  Description varchar(40),
  Private enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (Id)
);

#
# Table structure for table 'ForumTable'
#
CREATE TABLE ForumTable (
  Id int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryId int(11) DEFAULT '0' NOT NULL,
  Name varchar(20) DEFAULT '' NOT NULL,
  Description varchar(40),
  Moderated enum('Y','N') DEFAULT 'N',
  Private enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (Id)
);

#
# Table structure for table 'MessageTable'
#
CREATE TABLE MessageTable (
  Id int(11) DEFAULT '0' NOT NULL auto_increment,
  ForumId int(11) DEFAULT '0' NOT NULL,
  Topic varchar(60),
  Body text,
  UserId int(11),
  PostingTime timestamp(12),
  Parent int(11),
  PRIMARY KEY (Id)
);

#
# Dumping data for table 'MessageTable'
#

#
# Table structure for table 'UserTable'
#
CREATE TABLE UserTable (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  group_id int(11) DEFAULT '0' NOT NULL,
  first_name varchar(30),
  last_name varchar(35),
  nick_name varchar(15),
  email varchar(50),
  passwd varchar(16),
  auth_hash varchar(32),
  state enum('E','D') DEFAULT 'E',
  company varchar(30),
  phone_number varchar(14),
  mobile_number varchar(14),
  fax_number varchar(14),
  Address_one varchar(60),
  Address_two varchar(60),
  zip_code varchar(6),
  city varchar(25),
  country varchar(20),
  region_info char(2),
  PRIMARY KEY (id)
);

#
# Table structure for table 'session'
#
CREATE TABLE session (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  sid char(32) DEFAULT '' NOT NULL,
  usr int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (id)
);
