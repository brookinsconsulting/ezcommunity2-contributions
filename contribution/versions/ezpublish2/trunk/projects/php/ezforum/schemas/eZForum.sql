# MySQL dump 7.1
#
# Host: localhost    Database: zez
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'eZForum_Category'
#
DROP TABLE IF EXISTS eZForum_Category;
CREATE TABLE eZForum_Category (
  Name varchar(20),
  Description varchar(40),
  Private enum('Y','N') DEFAULT 'N',
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Category'
#


#
# Table structure for table 'eZForum_Message'
#
DROP TABLE IF EXISTS eZForum_Message;
CREATE TABLE eZForum_Message (
  ForumID int(11) DEFAULT '0' NOT NULL,
  Topic varchar(60),
  Body text,
  UserID int(11),
  Parent int(11),
  EmailNotice enum('N','Y') DEFAULT 'N',
  PostingTime timestamp(14),
  TreeID int(11),
  ThreadID int(11),
  Depth int(11),
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Message'
#


#
# Table structure for table 'eZForum_Forum'
#
DROP TABLE IF EXISTS eZForum_Forum;
CREATE TABLE eZForum_Forum (
  CategoryID int(11) DEFAULT '0' NOT NULL,
  Name varchar(20) DEFAULT '' NOT NULL,
  Description varchar(40),
  Moderated enum('Y','N') DEFAULT 'N',
  Private enum('Y','N') DEFAULT 'N',
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Forum'
#


