INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVAdd' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';
INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVDelete' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';
INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVModify' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';
INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVView' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';
INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVList' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';

INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVTypeAdd' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';
INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVTypeDelete' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';
INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVTypeModify' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';
INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVTypeView' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';
INSERT INTO eZUser_Permission ( ModuleID, Name ) SELECT DISTINCT Module.ID, 'CVTypeList' FROM eZUser_Module AS Module WHERE Module.Name='eZCV';

DROP TABLE IF EXISTS eZCV_MaritalStatus;
CREATE TABLE eZCV_MaritalStatus
(
    ID int(2) DEFAULT '0' NOT NULL auto_increment,
    Name varchar(32) DEFAULT 'unknown' NOT NULL,
    UNIQUE INDEX( NAME ),
    PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZCV_Sex;
CREATE TABLE eZCV_Sex
(
    ID int(1) DEFAULT '0' NOT NULL auto_increment,
    Name varchar(32) DEFAULT 'unknown' NOT NULL,
    UNIQUE INDEX( NAME ),
    PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZCV_ArmyStatus;
CREATE TABLE eZCV_ArmyStatus
(
    ID int(2) DEFAULT '0' NOT NULL auto_increment,
    Name varchar(32) DEFAULT 'unknown' NOT NULL,
    UNIQUE INDEX( NAME ),
    PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZCV_WorkStatus;
CREATE TABLE eZCV_WorkStatus
(
    ID int(2) DEFAULT '0' NOT NULL auto_increment,
    Name varchar(32) DEFAULT 'unknown' NOT NULL,
    UNIQUE INDEX( NAME ),
    PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZCV_CV;
CREATE TABLE eZCV_CV
(
    ID int(11) DEFAULT '0' NOT NULL auto_increment,
    PersonID int(11) DEFAULT '0' NOT NULL,
    NationalityID int(11) DEFAULT '0' NOT NULL,
    Sex             int(1) DEFAULT '0' NOT NULL REFERENCES SEX( ID ), 
    ArmyStatus      int(2) DEFAULT '0' NOT NULL REFERENCES ArmyStatus( ID ),
    MaritalStatus   int(2) DEFAULT '0' NOT NULL REFERENCES MaritalStatus( ID ),
    WorkStatus      int(2) DEFAULT '0' NOT NULL REFERENCES WorkStatus( ID ),
    Children int(2) DEFAULT '0' NOT NULL,
    Comment text NOT NULL,
    Created timestamp NOT NULL,
    Updated timestamp DEFAULT 'now()' NOT NULL,
    ValidUntil date DEFAULT 'today() + 30' NOT NULL,
    INDEX(ValidUntil),
    UNIQUE(PersonID),
    PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZCV_Extracurricular;
CREATE TABLE eZCV_Extracurricular
(
    ID int(11) DEFAULT '0' NOT NULL auto_increment,
    Start date DEFAULT 'today()' NOT NULL,
    End date DEFAULT 'today()' NOT NULL,
    Organization varchar(255) DEFAULT 'unknown' NOT NULL,
    Position varchar(255) DEFAULT 'unknown' NOT NULL,
    Speciality varchar(255) DEFAULT 'none' NOT NULL,
    Comment text NOT NULL,
    INDEX(End),
    PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZCV_CVExtracurricularDict;
CREATE TABLE eZCV_CVExtracurricularDict
(
    CVID int(11) DEFAULT '0' NOT NULL,
    ExtracurricularID int(11) DEFAULT '0' NOT NULL,
    PRIMARY KEY( CVID, ExtracurricularID )
);


DROP TABLE IF EXISTS eZCV_Education;
CREATE TABLE eZCV_Education
(
    ID int(11) DEFAULT '0' NOT NULL auto_increment,
    Start date DEFAULT 'today()' NOT NULL,
    End date DEFAULT 'today()' NOT NULL,
    Institution varchar(255) DEFAULT 'unknown' NOT NULL,
    Direction varchar(255) DEFAULT 'unknown' NOT NULL,
    Speciality varchar(255) DEFAULT 'none' NOT NULL,
    Comment text NOT NULL,
    INDEX(End),
    PRIMARY KEY(ID)
);


DROP TABLE IF EXISTS eZCV_CVEducationDict;
CREATE TABLE eZCV_CVEducationDict
(
    CVID int(11) DEFAULT '0' NOT NULL,
    EducationID int(11) DEFAULT '0' NOT NULL,
    PRIMARY KEY(CVID, EducationID)
);

DROP TABLE IF EXISTS eZCV_Experience;
CREATE TABLE eZCV_Experience
(
    ID int(11) DEFAULT '0' NOT NULL auto_increment,
    Start date DEFAULT 'today()' NOT NULL,
    End date DEFAULT 'today()' NOT NULL,
    Employer varchar(255) DEFAULT 'unknown' NOT NULL,
    Position varchar(255) DEFAULT 'unknown' NOT NULL,
    wasFullTime varchar(20) DEFAULT 'full time' NOT NULL,
    Tasks text NOT NULL,
    Comments text NOT NULL,
    INDEX(End),
    PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZCV_CVExperienceDict;
CREATE TABLE eZCV_CVExperienceDict
(
    CVID int(11) DEFAULT '0' NOT NULL,
    ExperienceID int(11) DEFAULT '0' NOT NULL,
    PRIMARY KEY(CVID, ExperienceID)
);



DROP TABLE IF EXISTS eZCV_CertificateCategory;
CREATE TABLE eZCV_CertificateCategory
(
    ID int(11) DEFAULT '0' NOT NULL auto_increment,
    ParentID int(11) DEFAULT '0' NOT NULL,
    Name varchar(255) DEFAULT 'unknown' NOT NULL,
    Institution varchar(255) DEFAULT 'unknown' NOT NULL,
    Description text,
    INDEX (ParentID),
    PRIMARY KEY(ID)
);


DROP TABLE IF EXISTS eZCV_CertificateType;
CREATE TABLE eZCV_CertificateType
(
    ID int(11) DEFAULT '0' NOT NULL auto_increment,
    CertificateCategoryID int(11) DEFAULT '0' NOT NULL,
    Name varchar(255) DEFAULT 'unknown' NOT NULL,
    Description text,
    INDEX (CertificateCategoryID),
    PRIMARY KEY(ID)
);


DROP TABLE IF EXISTS eZCV_Certificate;
CREATE TABLE eZCV_Certificate
(
    ID int(11) DEFAULT '0' NOT NULL auto_increment,
    CertificateTypeID int(11) DEFAULT '0' NOT NULL,
    Received date DEFAULT 'today()' NOT NULL,
    End date DEFAULT 'today()' NOT NULL,
    INDEX(End),
    PRIMARY KEY(ID)
);

DROP TABLE IF EXISTS eZCV_CVCertificateDict;
CREATE TABLE eZCV_CVCertificateDict
(
    CVID int(11) DEFAULT '0' NOT NULL,
    CertificateID int(11) DEFAULT '0' NOT NULL,
    PRIMARY KEY(CVID, CertificateID)
);
