#
# Table structure for table 'eZContact_Company'
#
DROP TABLE IF EXISTS eZContact_Company;
CREATE TABLE eZContact_Company (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CreatorID int(11) DEFAULT '0' NOT NULL,
  Name varchar(50) DEFAULT '' NOT NULL,
  Comment text,
  ContactType int(11) DEFAULT '0' NOT NULL,
  CompanyNo varchar(20) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_Company'
#




#
# Table structure for table 'eZContact_CompanyAddressDict'
#
DROP TABLE IF EXISTS eZContact_CompanyAddressDict;
CREATE TABLE eZContact_CompanyAddressDict (
  CompanyID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,AddressID)
);

#
# Dumping data for table 'eZContact_CompanyAddressDict'
#




#
# Table structure for table 'eZContact_CompanyImageDefinition'
#
DROP TABLE IF EXISTS eZContact_CompanyImageDefinition;
CREATE TABLE eZContact_CompanyImageDefinition (
  CompanyID int(11) DEFAULT '0' NOT NULL,
  CompanyImageID int(11) DEFAULT '0' NOT NULL,
  LogoImageID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID)
);

#
# Dumping data for table 'eZContact_CompanyImageDefinition'
#




#
# Table structure for table 'eZContact_CompanyOnlineDict'
#
DROP TABLE IF EXISTS eZContact_CompanyOnlineDict;
CREATE TABLE eZContact_CompanyOnlineDict (
  CompanyID int(11) DEFAULT '0' NOT NULL,
  OnlineID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,OnlineID)
);

#
# Dumping data for table 'eZContact_CompanyOnlineDict'
#




#
# Table structure for table 'eZContact_CompanyPersonDict'
#
DROP TABLE IF EXISTS eZContact_CompanyPersonDict;
CREATE TABLE eZContact_CompanyPersonDict (
  CompanyID int(11) DEFAULT '0' NOT NULL,
  PersonID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,PersonID)
);

#
# Dumping data for table 'eZContact_CompanyPersonDict'
#




#
# Table structure for table 'eZContact_CompanyPhoneDict'
#
DROP TABLE IF EXISTS eZContact_CompanyPhoneDict;
CREATE TABLE eZContact_CompanyPhoneDict (
  CompanyID int(11) DEFAULT '0' NOT NULL,
  PhoneID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,PhoneID)
);

#
# Dumping data for table 'eZContact_CompanyPhoneDict'
#

DROP TABLE IF EXISTS eZContact_CompanyImageDict;
CREATE TABLE eZContact_CompanyImageDict (
  CompanyID int(11) DEFAULT '0' NOT NULL,
  ImageID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,ImageID)
);



#
# Table structure for table 'eZContact_CompanyProjectDict'
#
DROP TABLE IF EXISTS eZContact_CompanyProjectDict;
CREATE TABLE eZContact_CompanyProjectDict (
  CompanyID int(11) DEFAULT '0' NOT NULL,
  ProjectID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,ProjectID)
);

#
# Dumping data for table 'eZContact_CompanyProjectDict'
#




#
# Table structure for table 'eZContact_CompanyType'
#
DROP TABLE IF EXISTS eZContact_CompanyType;
CREATE TABLE eZContact_CompanyType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(50) DEFAULT '' NOT NULL,
  Description text,
  ParentID int(11) DEFAULT '0' NOT NULL,
  ImageID int(11) DEFAULT '0' NOT NULL,
  KEY ParentID (ParentID),
  KEY Name (Name),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_CompanyType'
#




#
# Table structure for table 'eZContact_CompanyTypeDict'
#
DROP TABLE IF EXISTS eZContact_CompanyTypeDict;
CREATE TABLE eZContact_CompanyTypeDict (
  CompanyTypeID int(11) DEFAULT '0' NOT NULL,
  CompanyID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyTypeID,CompanyID)
);

#
# Dumping data for table 'eZContact_CompanyTypeDict'
#




#
# Table structure for table 'eZContact_Consultation'
#
DROP TABLE IF EXISTS eZContact_Consultation;
CREATE TABLE eZContact_Consultation (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ShortDesc varchar(100) DEFAULT '' NOT NULL,
  Description text NOT NULL,
  Date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  StateID int(11) DEFAULT '0' NOT NULL,
  EmailNotifications varchar(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_Consultation'
#




#
# Table structure for table 'eZContact_ConsultationCompanyUserDict'
#
DROP TABLE IF EXISTS eZContact_ConsultationCompanyUserDict;
CREATE TABLE eZContact_ConsultationCompanyUserDict (
  ConsultationID int(11) DEFAULT '0' NOT NULL,
  CompanyID int(11) DEFAULT '0' NOT NULL,
  UserID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,CompanyID,UserID)
);

#
# Dumping data for table 'eZContact_ConsultationCompanyUserDict'
#




#
# Table structure for table 'eZContact_ConsultationGroupsDict'
#
DROP TABLE IF EXISTS eZContact_ConsultationGroupsDict;
CREATE TABLE eZContact_ConsultationGroupsDict (
  ConsultationID int(11) DEFAULT '0' NOT NULL,
  GroupID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,GroupID)
);

#
# Dumping data for table 'eZContact_ConsultationGroupsDict'
#




#
# Table structure for table 'eZContact_ConsultationPersonUserDict'
#
DROP TABLE IF EXISTS eZContact_ConsultationPersonUserDict;
CREATE TABLE eZContact_ConsultationPersonUserDict (
  ConsultationID int(11) DEFAULT '0' NOT NULL,
  PersonID int(11) DEFAULT '0' NOT NULL,
  UserID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,PersonID,UserID)
);

#
# Dumping data for table 'eZContact_ConsultationPersonUserDict'
#




#
# Table structure for table 'eZContact_ConsultationType'
#
DROP TABLE IF EXISTS eZContact_ConsultationType;
CREATE TABLE eZContact_ConsultationType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_ConsultationType'
#




#
# Table structure for table 'eZContact_ContactType'
#
DROP TABLE IF EXISTS eZContact_ContactType;
CREATE TABLE eZContact_ContactType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(50) DEFAULT '' NOT NULL,
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_ContactType'
#




#
# Table structure for table 'eZContact_ImageType'
#
DROP TABLE IF EXISTS eZContact_ImageType;
CREATE TABLE eZContact_ImageType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_ImageType'
#




#
# Table structure for table 'eZContact_Person'
#
DROP TABLE IF EXISTS eZContact_Person;
CREATE TABLE eZContact_Person (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  FirstName varchar(50),
  LastName varchar(50),
  BirthDate date,
  Comment text,
  ContactTypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_Person'
#




#
# Table structure for table 'eZContact_PersonAddressDict'
#
DROP TABLE IF EXISTS eZContact_PersonAddressDict;
CREATE TABLE eZContact_PersonAddressDict (
  PersonID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (PersonID,AddressID)
);

#
# Dumping data for table 'eZContact_PersonAddressDict'
#




#
# Table structure for table 'eZContact_PersonOnlineDict'
#
DROP TABLE IF EXISTS eZContact_PersonOnlineDict;
CREATE TABLE eZContact_PersonOnlineDict (
  PersonID int(11) DEFAULT '0' NOT NULL,
  OnlineID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (PersonID,OnlineID)
);

#
# Dumping data for table 'eZContact_PersonOnlineDict'
#




#
# Table structure for table 'eZContact_PersonPhoneDict'
#
DROP TABLE IF EXISTS eZContact_PersonPhoneDict;
CREATE TABLE eZContact_PersonPhoneDict (
  PersonID int(11) DEFAULT '0' NOT NULL,
  PhoneID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (PersonID,PhoneID)
);

#
# Dumping data for table 'eZContact_PersonPhoneDict'
#




#
# Table structure for table 'eZContact_PersonProjectDict'
#
DROP TABLE IF EXISTS eZContact_PersonProjectDict;
CREATE TABLE eZContact_PersonProjectDict (
  PersonID int(11) DEFAULT '0' NOT NULL,
  ProjectID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (PersonID,ProjectID)
);

#
# Dumping data for table 'eZContact_PersonProjectDict'
#




#
# Table structure for table 'eZContact_ProjectType'
#
DROP TABLE IF EXISTS eZContact_ProjectType;
CREATE TABLE eZContact_ProjectType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50) DEFAULT '' NOT NULL,
  ListOrder int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_ProjectType'
#




#
# Table structure for table 'eZContact_UserCompanyDict'
#
DROP TABLE IF EXISTS eZContact_UserCompanyDict;
CREATE TABLE eZContact_UserCompanyDict (
  UserID int(11) DEFAULT '0' NOT NULL,
  CompanyID int(11) DEFAULT '0' NOT NULL,
  UNIQUE UserID (UserID),
  UNIQUE CompanyID (CompanyID),
  PRIMARY KEY (UserID,CompanyID)
);

#
# Dumping data for table 'eZContact_UserCompanyDict'
#




#
# Table structure for table 'eZContact_UserPersonDict'
#
DROP TABLE IF EXISTS eZContact_UserPersonDict;
CREATE TABLE eZContact_UserPersonDict (
  UserID int(11) DEFAULT '0' NOT NULL,
  PersonID int(11) DEFAULT '0' NOT NULL,
  UNIQUE UserID (UserID),
  UNIQUE PersonID (PersonID),
  PRIMARY KEY (UserID,PersonID)
);

DROP TABLE IF EXISTS eZContact_CompanyView;
CREATE TABLE eZContact_CompanyView (
  ID int(11) NOT NULL auto_increment,
  CompanyID int(11) NOT NULL default '0',
  Count int(11) NOT NULL default '0',
  Date int NOT NULL,
  PRIMARY KEY (ID,CompanyID,Date)
) TYPE=MyISAM;



