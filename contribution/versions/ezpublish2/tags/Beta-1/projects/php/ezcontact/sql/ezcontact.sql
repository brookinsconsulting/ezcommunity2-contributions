
#
# Table structure for table 'eZContact_Address'
#
DROP TABLE IF EXISTS eZContact_Address;
CREATE TABLE eZContact_Address (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Street1 char(50),
  Street2 char(50),
  AddressType int(11),
  Place char(50),
  Zip char(10),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_AddressType'
#
DROP TABLE IF EXISTS eZContact_AddressType;
CREATE TABLE eZContact_AddressType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_Company'
#
DROP TABLE IF EXISTS eZContact_Company;
CREATE TABLE eZContact_Company (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Owner int(11),
  Name varchar(50),
  Comment text,
  ContactType int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_CompanyAddressDict'
#
DROP TABLE IF EXISTS eZContact_CompanyAddressDict;
CREATE TABLE eZContact_CompanyAddressDict (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CompanyID int(11),
  AddressID int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_CompanyConsultDict'
#
DROP TABLE IF EXISTS eZContact_CompanyConsultDict;
CREATE TABLE eZContact_CompanyConsultDict (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CompanyID int(11),
  ConsultID int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_CompanyPhoneDict'
#
DROP TABLE IF EXISTS eZContact_CompanyPhoneDict;
CREATE TABLE eZContact_CompanyPhoneDict (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CompanyID int(11),
  PhoneID int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_CompanyType'
#
DROP TABLE IF EXISTS eZContact_CompanyType;
CREATE TABLE eZContact_CompanyType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(50),
  Description text,
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_Consult'
#
DROP TABLE IF EXISTS eZContact_Consult;
CREATE TABLE eZContact_Consult (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Title varchar(100),
  Body text,
  UserID int(11),
  Created datetime,
  Modified datetime,
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_Note'
#
DROP TABLE IF EXISTS eZContact_Note;
CREATE TABLE eZContact_Note (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  Title varchar(50),
  Body text,
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_Person'
#
DROP TABLE IF EXISTS eZContact_Person;
CREATE TABLE eZContact_Person (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  FirstName varchar(50),
  LastName varchar(50),
  Owner int(11),
  PersonNr int(11),
  ContactType int(11),
  Comment text,
  Company int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_PersonAddressDict'
#
DROP TABLE IF EXISTS eZContact_PersonAddressDict;
CREATE TABLE eZContact_PersonAddressDict (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PersonID int(11),
  AddressID int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_PersonConsultDict'
#
DROP TABLE IF EXISTS eZContact_PersonConsultDict;
CREATE TABLE eZContact_PersonConsultDict (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PersonID int(11),
  ConsultID int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_PersonPhoneDict'
#
DROP TABLE IF EXISTS eZContact_PersonPhoneDict;
CREATE TABLE eZContact_PersonPhoneDict (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PersonID int(11),
  PhoneID int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_PersonType'
#
DROP TABLE IF EXISTS eZContact_PersonType;
CREATE TABLE eZContact_PersonType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(50),
  Description text,
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_Phone'
#
DROP TABLE IF EXISTS eZContact_Phone;
CREATE TABLE eZContact_Phone (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Number char(50),
  Type int(11),
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZContact_PhoneType'
#
DROP TABLE IF EXISTS eZContact_PhoneType;
CREATE TABLE eZContact_PhoneType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  PRIMARY KEY (ID)
);
