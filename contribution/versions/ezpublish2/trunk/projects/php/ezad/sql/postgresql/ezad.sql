drop table eZAd_Ad;
drop table eZAd_AdCategoryLink;
drop table eZAd_Category;
drop table eZAd_Click;
drop table eZAd_View;

CREATE TABLE eZAd_Ad(
  ID int NOT NULL,
  Name varchar(150) default NULL,
  ImageID int default NULL,
  ViewStartDate int default null,
  ViewStopDate int default null,
  ViewRule int,
  URL varchar(200) default NULL,
  Description text,
  IsActive int not null,
  ViewPrice float default 0.0,
  ClickPrice float default 0.0,
  HTMLBanner text default null,
  UseHTML int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_AdCategoryLink (
  ID int NOT NULL,
  CategoryID int default NULL,
  AdID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_Category (
  ID int NOT NULL,
  Name varchar(150) default NULL,
  Description text,
  ParentID int not NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_Click (
  ID int NOT NULL,
  AdID int default NULL,
  ClickCount int default NULL,
  ClickOffsetCount int default NULL,
  ClickPrice float,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_View (
  ID int NOT NULL,
  AdID int default NULL,
  ViewCount int NOT NULL,
  ViewOffsetCount int NOT NULL,
  ViewPrice float NOT NULL,
  PRIMARY KEY (ID)
);


# fix 
CREATE TABLE eZAd_Ad:
  ViewStartDate timestamp(14) NOT NULL,
  ViewStopDate timestamp(14) NOT NULL,
  ViewRule enum('Period','Click') default 'Click', ( 1-2 default 1 )
  IsActive enum('true','false') default NULL,


