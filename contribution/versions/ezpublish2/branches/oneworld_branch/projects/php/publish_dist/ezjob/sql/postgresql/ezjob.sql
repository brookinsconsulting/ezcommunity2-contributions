CREATE TABLE ezJob_Job
(
  ID int NOT NULL,
  Title text default NULL,
  CareerSectorID int default '0' NOT NULL,
  Description text default NULL,
  Location text default NULL,
  Salary text default NULL,
  OrganisationID int default '0' NOT NULL,
  URL text default NULL,
  Volunteer int default '0' NOT NULL,
  Instructions text default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_JobWorkTypeLink
(
  ID int NOT NULL,
  JobID int default '0' NOT NULL,
  WorkTypeID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_JobRelatedSectorLink
(
  ID int NOT NULL,
  JobID int default '0' NOT NULL,
  RelatedSectorID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_JobLanguageLink
(
  ID int NOT NULL,
  JobID int default '0' NOT NULL,
  LanguageID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZJob_Organisation
(
  ID int NOT NULL,
  Name text default NULL,
  Acronym text default NULL,
  Description text default NULL,
  Notes text default NULL,
  MainLanguage int default '0' NOT NULL,
  MainContact varchar(256) NOT NULL,
  CreationDate int default '0' NOT NULL,
  ModificationDate int default '0' NOT NULL,
  EditorialLinks int default '0' NOT NULL,  
  DBOGroup int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_OrganisationAddressLink
(
  ID int NOT NULL,
  OrganisationID int NOT NULL,
  UserID int NOT NULL,
  PRIMARY KEY (ID)  
);

CREATE TABLE eZJob_OrganisationTypeLink
(
  ID int NOT NULL,
  OrganisationID int default '0' NOT NULL,
  OrganisationTypeID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_OrganisationCountryLink
(
  ID int NOT NULL,
  OrganisationID int default '0' NOT NULL,
  CountryID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_OrganisationLanguageLink
(
  ID int NOT NULL,
  OrganisationID int default '0' NOT NULL,
  LanguageID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_OrganisationMainSectorLink
(
  ID int NOT NULL,
  OrganisationID int default '0' NOT NULL,
  MainSectorID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_OrganisationWorkTypeLink
(
  ID int NOT NULL,
  OrganisationID int default '0' NOT NULL,
  WorkTypeID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_Parter
(
  ID int NOT NULL,
  ShortName varchar(32) default NULL,
  Band text default NULL,
  Status text default NULL,
  StartDate int default '0' NOT NULL,
  AffiliatedGroup int default '0' NOT NULL,
  WebSpace text default NULL,
  URL text default NULL,
  Information text default NULL,  
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_PartnerFollowUp
(
  ID int NOT NULL,
  PartnerID int default '0' NOT NULL,
  Date int default '0' NOT NULL,
  Description text default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_Credit
(
  ID int NOT NULL,
  AccountID int default '0' NOT NULL,
  Available int default NULL,
  Used int default '0' NOT NULL,
  ExpiryDate int default '0' NOT NULL,
  Price float default '0' NOT NULL,
  Paid float default '0' NOT NULL,
  CreatedDate int default '0' NOT NULL,
  CreatedUserID int default '0' NOT NULL,
  PaidDate int default '0' NOT NULL,
  PaidUser int default '0' NOT NULL,
  Status int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_Advert
(
  ID int NOT NULL,
  CreditID int default '0' NOT NULL,
  CreatedDate int default '0' NOT NULL,
  CreatedUser int default '0' NOT NULL,
  StartDate int default '0' NOT NULL,
  ClosingDate int default '0' NOT NULL,
  CountryID int default '0' NOT NULL,
  IsPaid int default '0' NOT NULL,
  ViewCount int default '0' NOT NULL,
  Status varchar(128) default NULL,
);

CREATE TABLE eZJob_AdvertInfo
(
  ID int NOT NULL,
  LanguageID int default '0' NOT NULL,
  Title text default NULL,
  Summary text default NULL,
  Description text default NULL,
  ApplyInfo text default NULL,
  URL text default NULL,
  Salary text default NULL,
  LocationComment text default NULL,
  PRIMARY KEY (ID)  
);

CREATE TABLE eZJob_AdvertTopicLink
(
  ID int NOT NULL,
  AdvertID int default '0' NOT NULL,
  TopicID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_AdvertLanguageLink
(
  ID int NOT NULL,
  AdvertID int default '0' NOT NULL,
  LanguageID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_AdvertWorkTypeLink
(
  ID int NOT NULL,
  AdvertID int default '0' NOT NULL,
  WorkTypeID int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_Event
(
  ID int NOT NULL,
  Title text default NULL,
  Summary text default NULL,
  Description text default NULL,
  StartDate int default '0' NOT NULL,
  EndDate int default '0' NOT NULL,
  StartTime int default '0' NOT NULL,
  EndTime int default '0' NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_WorkType
(
  ID int NOT NULL,
  Name varchar(128) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_Sectors
(
  ID int NOT NULL,
  Name varchar(128) default NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZJob_CareerSector
(
  ID int NOT NULL,
  Name varchar(128) default NULL,
  PRIMARY KEY (ID)
);