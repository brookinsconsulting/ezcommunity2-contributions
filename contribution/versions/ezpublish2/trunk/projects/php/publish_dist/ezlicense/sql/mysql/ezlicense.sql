DROP TABLE eZLicense_Program;
DROP TABLE eZLicense_ProgramVersion;
DROP TABLE eZLicense_LicenseType;
DROP TABLE eZLicense_LicenseCost;
DROP TABLE eZLicense_Reseller;
DROP TABLE eZLicense_License;
DROP TABLE eZLicense_LicenseBuyerOrderLink;
DROP TABLE eZLicense_SubLicense;
DROP TABLE eZLicense_LicenseHost;

CREATE TABLE eZLicense_Program
(
    ID int NOT NULL,
    Name varchar(255) NOT NULL,
    PRIMARY KEY(ID)
) TYPE=MyISAM;

INSERT INTO eZLicense_Program VALUES (1,'eZ publish desktop edition');

CREATE TABLE eZLicense_ProgramVersion
(
    ID int NOT NULL,
    ProgramID int NOT NULL,
    Major int NOT NULL default '0',
    Minor int NOT NULL default '0',
    PRIMARY KEY(ID),
    UNIQUE( ProgramID, Major, Minor )
) TYPE=MyISAM;

INSERT INTO eZLicense_ProgramVersion VALUES (1, 1, 2, 2);

CREATE TABLE eZLicense_LicenseType
(
    ID int NOT NULL,
    Name varchar(50),
    PRIMARY KEY(ID)
) TYPE=MyISAM;

INSERT INTO eZLicense_LicenseType VALUES (1,'unlimited');
INSERT INTO eZLicense_LicenseType VALUES (2,'host_limited');
INSERT INTO eZLicense_LicenseType VALUES (3,'time_limited');
INSERT INTO eZLicense_LicenseType VALUES (4,'user_limited');

CREATE TABLE eZLicense_LicenseCost
(
    ID int NOT NULL,
    ProgramVersionID int NOT NULL default '0',
    LicenseTypeID int NOT NULL default '0',
    Cost int NOT NULL default '0',
    CostNonProfessional int NOT NULL default '0',
    ProductID int NOT NULL default '0',
    PRIMARY KEY(ID),
    UNIQUE(ProgramVersionID, LicenseTypeID )
) TYPE=MyISAM;

CREATE TABLE eZLicense_Reseller (
    ID int NOT NULL,
    UserID int NOT NULL,
    PRIMARY KEY(ID)
)  TYPE=MyISAM;

CREATE TABLE eZLicense_License
(
    ID int NOT NULL,
    LicenseType int NOT NULL default '0',
    UserLimit int NOT NULL default '0',
    StartDate int NOT NULL default '0',
    ExpiryDate int NOT NULL default '0',
    Price int NOT NULL default '0',
    MailTo varchar(255) NOT NULL,
    Reminder int NOT NULL default '0',
    ProgramName varchar(255) NOT NULL,
    Major int NOT NULL default '0',
    Minor int NOT NULL default '0',
    PRIMARY KEY(ID)
)  TYPE=MyISAM;

CREATE TABLE eZLicense_LicenseBuyerOrderLink
(
    ID int NOT NULL,
    LicenseID int NOT NULL default '0',
    UserID int NOT NULL default '0',
    OrderID int NOT NULL default '0',
    CartID int NOT NULL default '0',
    ProductID int NOT NULL default '0',
    LicenseQTY int NOT NULL default '0',
    PRIMARY KEY(ID),
    UNIQUE(LicenseID, UserID)    
) TYPE=MyISAM;

CREATE TABLE eZLicense_SubLicense
(
    LicenseID  int NOT NULL,
    SubLicense int NOT NULL default '0',
    Name varchar(255) NOT NULL,
    Address text NOT NULL,
    Employee varchar(255) NOT NULL,
    PRIMARY KEY(LicenseID),
    UNIQUE(LicenseID, SubLicense)
) TYPE=MyISAM;

CREATE TABLE eZLicense_LicenseHost
(
    ID int NOT NULL,
    LicenseID  int NOT NULL default '0',
    Host varchar(255) NOT NULL,
    PRIMARY KEY(ID),
    UNIQUE(LicenseID, Host)
)  TYPE=MyISAM;
