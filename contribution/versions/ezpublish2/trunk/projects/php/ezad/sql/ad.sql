
DROP TABLE IF EXISTS eZAd_Ad;
CREATE TABLE eZAd_Ad(
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  ImageID int,
  ViewStartDate timestamp(14),
  ViewStopDate timestamp(14),
  ViewRule enum( 'Period', 'Click' ) DEFAULT 'Click',
  URL varchar(200),
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS eZAd_Category;
CREATE TABLE eZAd_Category(
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  ParentID int(11),
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS eZAd_AdCategoryLink;
CREATE TABLE eZAd_AdCategoryLink(
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  AdID int(11),
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS eZAd_View;
CREATE TABLE eZAd_View(
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  AdID int(11),
  ViewTime timestamp(14),
  UserID int DEFAULT '0' NOT NULL,
  ViewPrice float,
  PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS eZAd_Click;
CREATE TABLE eZAd_Click(
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  AdID int(11),
  ClickTime timestamp(14),
  UserID int DEFAULT '0' NOT NULL,
  ClickPrice float,
  PRIMARY KEY (ID)
);
