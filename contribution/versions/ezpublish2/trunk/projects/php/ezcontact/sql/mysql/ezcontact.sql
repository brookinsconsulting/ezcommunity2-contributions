CREATE TABLE eZContact_Company (
  ID int NOT NULL,
  CreatorID int DEFAULT '0' NOT NULL,
  Name varchar(50) DEFAULT '' NOT NULL,
  Comment text,
  ContactType int DEFAULT '0' NOT NULL,
  CompanyNo varchar(20) DEFAULT '' NOT NULL,
  ContactID int DEFAULT '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZContact_CompanyAddressDict (
  CompanyID int NOT NULL,
  AddressID int NOT NULL,
  PRIMARY KEY (CompanyID,AddressID)
) TYPE=MyISAM;

CREATE TABLE eZContact_CompanyImageDefinition (
  CompanyID int NOT NULL,
  CompanyImageID int DEFAULT '0' NOT NULL,
  LogoImageID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID)
) TYPE=MyISAM;

CREATE TABLE eZContact_CompanyIndex (
  CompanyID int(11) NOT NULL default '0',
  Value varchar(255) NOT NULL default '',
  Type int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID,Value)
) TYPE=MyISAM;

CREATE TABLE eZContact_CompanyOnlineDict (
  CompanyID int NOT NULL,
  OnlineID int NOT NULL,
  PRIMARY KEY (CompanyID,OnlineID)
) TYPE=MyISAM;

CREATE TABLE eZContact_CompanyPersonDict (
  CompanyID int NOT NULL,
  PersonID int NOT NULL,
  PRIMARY KEY (CompanyID,PersonID)
) TYPE=MyISAM;

CREATE TABLE eZContact_CompanyPhoneDict (
  CompanyID int NOT NULL,
  PhoneID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,PhoneID)
) TYPE=MyISAM;

CREATE TABLE eZContact_CompanyProjectDict (
  CompanyID int NOT NULL,
  ProjectID int NOT NULL,
  PRIMARY KEY (CompanyID,ProjectID)
) TYPE=MyISAM;

CREATE TABLE eZContact_CompanyType (
  ID int NOT NULL,
  Name varchar(50) DEFAULT '' NOT NULL,
  Description text,
  ParentID int DEFAULT '0' NOT NULL,
  ImageID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE INDEX CompanyType_ParentID ON eZContact_CompanyType (ParentID);
CREATE INDEX CompanyType_Name ON eZContact_CompanyType (Name);

CREATE TABLE eZContact_CompanyTypeDict (
  CompanyTypeID int NOT NULL,
  CompanyID int NOT NULL,
  PRIMARY KEY (CompanyTypeID,CompanyID)
) TYPE=MyISAM;

CREATE TABLE eZContact_Consultation (
  ID int NOT NULL,
  ShortDesc varchar(100) DEFAULT '' NOT NULL,
  Description text NOT NULL,
  Date int,
  StateID int DEFAULT '0' NOT NULL,
  EmailNotifications varchar(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZContact_ConsultationCompanyDict (
  ConsultationID int DEFAULT '0' NOT NULL,
  CompanyID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,CompanyID)
) TYPE=MyISAM;

CREATE TABLE eZContact_ConsultationCompanyUserDict (
  ConsultationID int NOT NULL,
  CompanyID int DEFAULT '0' NOT NULL,
  UserID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,CompanyID,UserID)
) TYPE=MyISAM;

CREATE TABLE eZContact_ConsultationGroupsDict (
  ConsultationID int NOT NULL,
  GroupID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,GroupID)
) TYPE=MyISAM;

CREATE TABLE eZContact_ConsultationPersonUserDict (
  ConsultationID int NOT NULL,
  PersonID int DEFAULT '0' NOT NULL,
  UserID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,PersonID,UserID)
) TYPE=MyISAM;

CREATE TABLE eZContact_ConsultationType (
  ID int NOT NULL,
  Name varchar(50),
  ListOrder int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZContact_ContactType (
  ID int NOT NULL,
  Name varchar(50) DEFAULT '' NOT NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZContact_Person (
  ID int NOT NULL,
  FirstName varchar(50),
  LastName varchar(50),
  BirthDate int,
  Comment text,
  ContactTypeID int,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZContact_PersonAddressDict (
  PersonID int NOT NULL,
  AddressID int NOT NULL,
  PRIMARY KEY (PersonID,AddressID)
) TYPE=MyISAM;

CREATE TABLE eZContact_PersonIndex (
  PersonID int(11) NOT NULL default '0',
  Value varchar(255) NOT NULL default '',
  Type int(11) NOT NULL default '0',
  PRIMARY KEY (PersonID,Value)
) TYPE=MyISAM;

CREATE TABLE eZContact_PersonOnlineDict (
  PersonID int NOT NULL,
  OnlineID int NOT NULL,
  PRIMARY KEY (PersonID,OnlineID)
) TYPE=MyISAM;

CREATE TABLE eZContact_PersonPhoneDict (
  PersonID int NOT NULL,
  PhoneID int NOT NULL,
  PRIMARY KEY (PersonID,PhoneID)
) TYPE=MyISAM;

CREATE TABLE eZContact_PersonProjectDict (
  PersonID int NOT NULL,
  ProjectID int NOT NULL,
  PRIMARY KEY (PersonID,ProjectID)
) TYPE=MyISAM;

CREATE TABLE eZContact_ProjectType (
  ID int NOT NULL,
  Name varchar(50) DEFAULT '' NOT NULL,
  ListOrder int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZContact_UserCompanyDict (
  UserID int NOT NULL,
  CompanyID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (UserID,CompanyID)
) TYPE=MyISAM;

CREATE UNIQUE INDEX eZContactUserCompanyDictCompanyID ON eZContact_UserCompanyDict(CompanyID);
CREATE UNIQUE INDEX eZContactUserCompanyDictUserID ON eZContact_UserCompanyDict(UserID);

CREATE TABLE eZContact_UserPersonDict (
  UserID int NOT NULL,
  PersonID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (UserID,PersonID)
) TYPE=MyISAM;

CREATE UNIQUE INDEX eZContactUserPersonDictPersonID ON eZContact_UserPersonDict(PersonID);
CREATE UNIQUE INDEX eZContactUserPersonDictUserID ON eZContact_UserPersonDict(UserID);

CREATE TABLE eZContact_CompanyView (
  ID int NOT NULL,
  CompanyID int default '0' NOT NULL,
  Count int default '0' NOT NULL,
  Date int NOT NULL,
  PRIMARY KEY (ID,CompanyID,Date)
) TYPE=MyISAM;

CREATE TABLE eZContact_CompanyImageDict (
  CompanyID int DEFAULT '0' NOT NULL,
  ImageID int DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,ImageID)
) TYPE=MyISAM;

