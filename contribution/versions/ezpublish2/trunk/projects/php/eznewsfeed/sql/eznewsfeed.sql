#
# Table structure for table 'eZNewsFeed_Category'
#
DROP TABLE IF EXISTS eZNewsFeed_Category;
CREATE TABLE eZNewsFeed_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150) DEFAULT '' NOT NULL,
  Description text,
  ParentID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNewsFeed_Category'
#

INSERT INTO eZNewsFeed_Category VALUES (1,'News from freshmeat','',0);

#
# Table structure for table 'eZNewsFeed_News'
#
DROP TABLE IF EXISTS eZNewsFeed_News;
CREATE TABLE eZNewsFeed_News (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  IsPublished enum('true','false') DEFAULT 'false',
  PublishingDate timestamp(14),
  OriginalPublishingDate timestamp(14),
  Name varchar(150) DEFAULT '' NOT NULL,
  Intro text,
  KeyWords varchar(200),
  URL varchar(200),
  Origin varchar(150),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNewsFeed_News'
#




#
# Table structure for table 'eZNewsFeed_NewsCategoryLink'
#
DROP TABLE IF EXISTS eZNewsFeed_NewsCategoryLink;
CREATE TABLE eZNewsFeed_NewsCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  NewsID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNewsFeed_NewsCategoryLink'
#




#
# Table structure for table 'eZNewsFeed_SourceSite'
#
DROP TABLE IF EXISTS eZNewsFeed_SourceSite;
CREATE TABLE eZNewsFeed_SourceSite (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  URL char(250),
  Login char(30),
  Password char(30),
  CategoryID int(11) DEFAULT '0' NOT NULL,
  Name char(100),
  Decoder char(50),
  IsActive enum('true','false') DEFAULT 'false',
  AutoPublish int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNewsFeed_SourceSite'
#

INSERT INTO eZNewsFeed_SourceSite VALUES (1,'http://freshmeat.net/backend/fm.rdf','','',1,'Freshmeat','','false',0);

