#
# Table structure for table 'eZAd_Ad'
#
DROP TABLE IF EXISTS eZAd_Ad;
CREATE TABLE eZAd_Ad (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  ImageID int(11),
  URL varchar(200),
  Description text,
  IsActive enum('true','false'),
  ViewPrice float(10,2),
  ClickPrice float(10,2),
  HTMLBanner text NOT NULL,
  UseHTML int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_Ad'
#




#
# Table structure for table 'eZAd_AdCategoryLink'
#
DROP TABLE IF EXISTS eZAd_AdCategoryLink;
CREATE TABLE eZAd_AdCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  AdID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_AdCategoryLink'
#




#
# Table structure for table 'eZAd_Category'
#
DROP TABLE IF EXISTS eZAd_Category;
CREATE TABLE eZAd_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  ParentID int(11),
  ExcludeFromSearch enum('true','false') DEFAULT 'false',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_Category'
#




#
# Table structure for table 'eZAd_Click'
#
DROP TABLE IF EXISTS eZAd_Click;
CREATE TABLE eZAd_Click (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  AdID int(11),
  PageViewID int(11),
  ClickPrice float(10,2),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_Click'
#




#
# Table structure for table 'eZAd_View'
#
DROP TABLE IF EXISTS eZAd_View;
CREATE TABLE eZAd_View (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  AdID int(11),
  Date date,
  ViewOffsetCount(11) int NOT NULL,
  ViewCount int(11) DEFAULT '0' NOT NULL,
  ViewPrice int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_View'
#




