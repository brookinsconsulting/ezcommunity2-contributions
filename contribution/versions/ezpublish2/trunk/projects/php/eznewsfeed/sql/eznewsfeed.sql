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
  IsPublished int NOT NULL DEFAULT '0',
  PublishingDate int NOT NULL,
  OriginalPublishingDate int NOT NULL,
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
  URL varchar(250),
  Login varchar(30),
  Password varchar(30),
  CategoryID int(11) DEFAULT '0' NOT NULL,
  Name varchar(100),
  Decoder varchar(50),
  IsActive int DEFAULT '0',
  AutoPublish int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNewsFeed_SourceSite'
#

INSERT INTO eZNewsFeed_SourceSite VALUES (1,'http://freshmeat.net/backend/fm.rdf','','',1,'Freshmeat','',0,0);

