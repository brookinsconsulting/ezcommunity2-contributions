drop table eZAd_Ad;

CREATE TABLE eZAd_Ad(
  ID int NOT NULL,
  Name varchar(150) default NULL,
  ImageID int default NULL,
  ViewStartDate int NOT NULL,
  ViewStopDate int NOT NULL,
  ViewRule int default '1',
  URL varchar(200) default NULL,
  Description text,
  IsActive int not null default '0',
  ViewPrice float not null default '0.0',
  ClickPrice float not null default '0.0',
  HTMLBanner text NOT NULL,
  UseHTML int NOT NULL default '0',
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
  ParentID int default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_Click (
  ID int NOT NULL,
  AdID int default NULL,
  PageViewID int default NULL,
  ClickPrice float(10,2) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAd_View (
  ID int NOT NULL,
  AdID int default NULL,
  Date date default NULL,
  ViewCount int NOT NULL default '0',
  ViewPrice int NOT NULL default '0',
  PRIMARY KEY (ID)
);


# fix 
CREATE TABLE eZAd_Ad:
  ViewStartDate timestamp(14) NOT NULL,
  ViewStopDate timestamp(14) NOT NULL,
  ViewRule enum('Period','Click') default 'Click', ( 1-2 default 1 )
  IsActive enum('true','false') default NULL,


