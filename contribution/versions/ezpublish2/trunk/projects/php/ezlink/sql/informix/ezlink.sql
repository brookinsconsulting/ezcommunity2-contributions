
CREATE TABLE eZLink_Hit (
  ID int NOT NULL,
  Link int default NULL,
  Time int NOT NULL,
  RemoteIP varchar(15) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_Link (
  ID int NOT NULL ,
  Title varchar(100) default NULL,
  Description lvarchar,
  LinkGroup int default NULL,
  KeyWords varchar(100) default NULL,
  Modified int NOT NULL,
  Accepted int,
  Created int default NULL,
  Url varchar(100) default NULL,
  ImageID int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZLink_LinkCategoryDefinition (
  ID int NOT NULL ,
  LinkID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_LinkCategoryLink (
  ID int NOT NULL ,
  LinkID int NOT NULL,
  CategoryID int NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZLink_LinkGroup (
  ID int NOT NULL,
  Parent int,
  Title varchar(100) default NULL,
  ImageID int default NULL,
  Description lvarchar,
  PRIMARY KEY (ID)
);
