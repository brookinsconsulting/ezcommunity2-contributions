#
# Table structure for table 'eZContact_Address'
#
DROP TABLE IF EXISTS eZContact_Address;
CREATE TABLE eZContact_Address (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Street1 char(50),
  Street2 char(50),
  AddressTypeID int(11),
  Place char(50),
  Zip char(10),
  CountryID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_Address'
#

#
# Table structure for table 'eZContact_AddressType'
#
DROP TABLE IF EXISTS eZContact_AddressType;
CREATE TABLE eZContact_AddressType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_AddressType'
#

#
# Table structure for table 'eZContact_Company'
#
DROP TABLE IF EXISTS eZContact_Company;
CREATE TABLE eZContact_Company (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CreatorID int(11) DEFAULT '0' NOT NULL,
  Name varchar(50),
  Comment text,
  ContactType int(11),
  CompanyNo varchar(255),
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
# Table structure for table 'eZContact_ConsulationCompanyDict'
#
DROP TABLE IF EXISTS eZContact_ConsulationCompanyDict;
CREATE TABLE eZContact_ConsulationCompanyDict (
  ConsultationID int(11) DEFAULT '0' NOT NULL,
  CompanyID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ConsultationID,CompanyID)
);

#
# Dumping data for table 'eZContact_ConsulationCompanyDict'
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
# Table structure for table 'eZContact_Country'
#
DROP TABLE IF EXISTS eZContact_Country;
CREATE TABLE eZContact_Country (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ISO char(2),
  Name char(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_Country'
#

INSERT INTO eZContact_Country VALUES (2,'AF','Afghanistan');
INSERT INTO eZContact_Country VALUES (3,'AL','Albania');
INSERT INTO eZContact_Country VALUES (4,'DZ','Algeria');
INSERT INTO eZContact_Country VALUES (5,'AS','American Samoa');
INSERT INTO eZContact_Country VALUES (6,'AD','Andorra');
INSERT INTO eZContact_Country VALUES (7,'AO','Angola');
INSERT INTO eZContact_Country VALUES (8,'AI','Anguilla');
INSERT INTO eZContact_Country VALUES (9,'AQ','Antarctica');
INSERT INTO eZContact_Country VALUES (10,'AG','Antigua and Barbuda');
INSERT INTO eZContact_Country VALUES (11,'AR','Argentina');
INSERT INTO eZContact_Country VALUES (12,'AM','Armenia');
INSERT INTO eZContact_Country VALUES (13,'AW','Aruba');
INSERT INTO eZContact_Country VALUES (14,'AU','Australia');
INSERT INTO eZContact_Country VALUES (15,'AT','Austria');
INSERT INTO eZContact_Country VALUES (16,'AZ','Azerbaijan');
INSERT INTO eZContact_Country VALUES (17,'BS','Bahamas');
INSERT INTO eZContact_Country VALUES (18,'BH','Bahrain');
INSERT INTO eZContact_Country VALUES (19,'BD','Bangladesh');
INSERT INTO eZContact_Country VALUES (20,'BB','Barbados');
INSERT INTO eZContact_Country VALUES (21,'BY','Belarus');
INSERT INTO eZContact_Country VALUES (22,'BE','Belgium');
INSERT INTO eZContact_Country VALUES (23,'BZ','Belize');
INSERT INTO eZContact_Country VALUES (24,'BJ','Benin');
INSERT INTO eZContact_Country VALUES (25,'BM','Bermuda');
INSERT INTO eZContact_Country VALUES (26,'BT','Bhutan');
INSERT INTO eZContact_Country VALUES (27,'BO','Bolivia');
INSERT INTO eZContact_Country VALUES (28,'BA','Bosnia and Herzegovina');
INSERT INTO eZContact_Country VALUES (29,'BW','Botswana');
INSERT INTO eZContact_Country VALUES (30,'BV','Bouvet Island');
INSERT INTO eZContact_Country VALUES (31,'BR','Brazil');
INSERT INTO eZContact_Country VALUES (32,'IO','British Indian Ocean Territory');
INSERT INTO eZContact_Country VALUES (33,'BN','Brunei Darussalam');
INSERT INTO eZContact_Country VALUES (34,'BG','Bulgaria');
INSERT INTO eZContact_Country VALUES (35,'BF','Burkina Faso');
INSERT INTO eZContact_Country VALUES (36,'BI','Burundi');
INSERT INTO eZContact_Country VALUES (37,'KH','Cambodia');
INSERT INTO eZContact_Country VALUES (38,'CM','Cameroon');
INSERT INTO eZContact_Country VALUES (39,'CA','Canada');
INSERT INTO eZContact_Country VALUES (40,'CV','Cape Verde');
INSERT INTO eZContact_Country VALUES (41,'KY','Cayman Islands');
INSERT INTO eZContact_Country VALUES (42,'CF','Central African Republic');
INSERT INTO eZContact_Country VALUES (43,'TD','Chad');
INSERT INTO eZContact_Country VALUES (44,'CL','Chile');
INSERT INTO eZContact_Country VALUES (45,'CN','China');
INSERT INTO eZContact_Country VALUES (46,'CX','Christmas Island');
INSERT INTO eZContact_Country VALUES (47,'CC','Cocos (Keeling) Islands');
INSERT INTO eZContact_Country VALUES (48,'CO','Colombia');
INSERT INTO eZContact_Country VALUES (49,'KM','Comoros');
INSERT INTO eZContact_Country VALUES (50,'CG','Congo');
INSERT INTO eZContact_Country VALUES (51,'CK','Cook Islands');
INSERT INTO eZContact_Country VALUES (52,'CR','Costa Rica');
INSERT INTO eZContact_Country VALUES (53,'CI','Cote d\'Ivoire');
INSERT INTO eZContact_Country VALUES (54,'HR','Croatia');
INSERT INTO eZContact_Country VALUES (55,'CU','Cuba');
INSERT INTO eZContact_Country VALUES (56,'CY','Cyprus');
INSERT INTO eZContact_Country VALUES (57,'CZ','Czech Republic');
INSERT INTO eZContact_Country VALUES (58,'DK','Denmark');
INSERT INTO eZContact_Country VALUES (59,'DJ','Djibouti');
INSERT INTO eZContact_Country VALUES (60,'DM','Dominica');
INSERT INTO eZContact_Country VALUES (61,'DO','Dominican Republic');
INSERT INTO eZContact_Country VALUES (62,'TP','East Timor');
INSERT INTO eZContact_Country VALUES (63,'EC','Ecuador');
INSERT INTO eZContact_Country VALUES (64,'EG','Egypt');
INSERT INTO eZContact_Country VALUES (65,'SV','El Salvador');
INSERT INTO eZContact_Country VALUES (66,'GQ','Equatorial Guinea');
INSERT INTO eZContact_Country VALUES (67,'ER','Eritrea');
INSERT INTO eZContact_Country VALUES (68,'EE','Estonia');
INSERT INTO eZContact_Country VALUES (69,'ET','Ethiopia');
INSERT INTO eZContact_Country VALUES (70,'FK','Falkland Islands (Malvinas)');
INSERT INTO eZContact_Country VALUES (71,'FO','Faroe Islands');
INSERT INTO eZContact_Country VALUES (72,'FJ','Fiji');
INSERT INTO eZContact_Country VALUES (73,'FI','Finland');
INSERT INTO eZContact_Country VALUES (74,'FR','France');
INSERT INTO eZContact_Country VALUES (75,'FX','France, Metropolitan');
INSERT INTO eZContact_Country VALUES (76,'GF','French Guiana');
INSERT INTO eZContact_Country VALUES (77,'PF','French Polynesia');
INSERT INTO eZContact_Country VALUES (78,'TF','French Southern Territories');
INSERT INTO eZContact_Country VALUES (79,'GA','Gabon');
INSERT INTO eZContact_Country VALUES (80,'GM','Gambia');
INSERT INTO eZContact_Country VALUES (81,'GE','Georgia');
INSERT INTO eZContact_Country VALUES (82,'DE','Germany');
INSERT INTO eZContact_Country VALUES (83,'GH','Ghana');
INSERT INTO eZContact_Country VALUES (84,'GI','Gibraltar');
INSERT INTO eZContact_Country VALUES (85,'GR','Greece');
INSERT INTO eZContact_Country VALUES (86,'GL','Greenland');
INSERT INTO eZContact_Country VALUES (87,'GD','Grenada');
INSERT INTO eZContact_Country VALUES (88,'GP','Guadeloupe');
INSERT INTO eZContact_Country VALUES (89,'GU','Guam');
INSERT INTO eZContact_Country VALUES (90,'GT','Guatemala');
INSERT INTO eZContact_Country VALUES (91,'GN','Guinea');
INSERT INTO eZContact_Country VALUES (92,'GW','Guinea-Bissau');
INSERT INTO eZContact_Country VALUES (93,'GY','Guyana');
INSERT INTO eZContact_Country VALUES (94,'HT','Haiti');
INSERT INTO eZContact_Country VALUES (95,'HM','Heard Island and McDonald Islands');
INSERT INTO eZContact_Country VALUES (96,'HN','Honduras');
INSERT INTO eZContact_Country VALUES (97,'HK','Hong Kong');
INSERT INTO eZContact_Country VALUES (98,'HU','Hungary');
INSERT INTO eZContact_Country VALUES (99,'IS','Iceland');
INSERT INTO eZContact_Country VALUES (100,'IN','India');
INSERT INTO eZContact_Country VALUES (101,'ID','Indonesia');
INSERT INTO eZContact_Country VALUES (102,'IR','Iran (Islamic Republic of)');
INSERT INTO eZContact_Country VALUES (103,'IQ','Iraq');
INSERT INTO eZContact_Country VALUES (104,'IE','Ireland');
INSERT INTO eZContact_Country VALUES (105,'IL','Israel');
INSERT INTO eZContact_Country VALUES (106,'IT','Italy');
INSERT INTO eZContact_Country VALUES (107,'JM','Jamaica');
INSERT INTO eZContact_Country VALUES (108,'JP','Japan');
INSERT INTO eZContact_Country VALUES (109,'JO','Jordan');
INSERT INTO eZContact_Country VALUES (110,'KZ','Kazakhstan');
INSERT INTO eZContact_Country VALUES (111,'KE','Kenya');
INSERT INTO eZContact_Country VALUES (112,'KI','Kiribati');
INSERT INTO eZContact_Country VALUES (113,'KP','Korea, Democratic People\'s Republic of');
INSERT INTO eZContact_Country VALUES (114,'KR','Korea, Republic of');
INSERT INTO eZContact_Country VALUES (115,'KW','Kuwait');
INSERT INTO eZContact_Country VALUES (116,'KG','Kyrgyzstan');
INSERT INTO eZContact_Country VALUES (117,'LA','Lao People\'s Democratic Republic');
INSERT INTO eZContact_Country VALUES (118,'LT','Latin America');
INSERT INTO eZContact_Country VALUES (119,'LV','Latvia');
INSERT INTO eZContact_Country VALUES (120,'LB','Lebanon');
INSERT INTO eZContact_Country VALUES (121,'LS','Lesotho');
INSERT INTO eZContact_Country VALUES (122,'LR','Liberia');
INSERT INTO eZContact_Country VALUES (123,'LY','Libyan Arab Jamahiriya');
INSERT INTO eZContact_Country VALUES (124,'LI','Liechtenstein');
INSERT INTO eZContact_Country VALUES (125,'LX','Lithuania');
INSERT INTO eZContact_Country VALUES (126,'LU','Luxembourg');
INSERT INTO eZContact_Country VALUES (127,'MO','Macau');
INSERT INTO eZContact_Country VALUES (128,'MK','Macedonia');
INSERT INTO eZContact_Country VALUES (129,'MG','Madagascar');
INSERT INTO eZContact_Country VALUES (130,'MW','Malawi');
INSERT INTO eZContact_Country VALUES (131,'MY','Malaysia');
INSERT INTO eZContact_Country VALUES (132,'MV','Maldives');
INSERT INTO eZContact_Country VALUES (133,'ML','Mali');
INSERT INTO eZContact_Country VALUES (134,'MT','Malta');
INSERT INTO eZContact_Country VALUES (135,'MH','Marshall Islands');
INSERT INTO eZContact_Country VALUES (136,'MQ','Martinique');
INSERT INTO eZContact_Country VALUES (137,'MR','Mauritania');
INSERT INTO eZContact_Country VALUES (138,'MU','Mauritius');
INSERT INTO eZContact_Country VALUES (139,'YT','Mayotte');
INSERT INTO eZContact_Country VALUES (140,'MX','Mexico');
INSERT INTO eZContact_Country VALUES (141,'FM','Micronesia (Federated States of)');
INSERT INTO eZContact_Country VALUES (142,'MD','Moldova, Republic of');
INSERT INTO eZContact_Country VALUES (143,'MC','Monaco');
INSERT INTO eZContact_Country VALUES (144,'MN','Mongolia');
INSERT INTO eZContact_Country VALUES (145,'MS','Montserrat');
INSERT INTO eZContact_Country VALUES (146,'MA','Morocco');
INSERT INTO eZContact_Country VALUES (147,'MZ','Mozambique');
INSERT INTO eZContact_Country VALUES (148,'MM','Myanmar');
INSERT INTO eZContact_Country VALUES (149,'NA','Namibia');
INSERT INTO eZContact_Country VALUES (150,'NR','Nauru');
INSERT INTO eZContact_Country VALUES (151,'NP','Nepal');
INSERT INTO eZContact_Country VALUES (152,'NL','Netherlands');
INSERT INTO eZContact_Country VALUES (153,'AN','Netherlands Antilles');
INSERT INTO eZContact_Country VALUES (154,'NC','New Caledonia');
INSERT INTO eZContact_Country VALUES (155,'NZ','New Zealand');
INSERT INTO eZContact_Country VALUES (156,'NI','Nicaragua');
INSERT INTO eZContact_Country VALUES (157,'NE','Niger');
INSERT INTO eZContact_Country VALUES (158,'NG','Nigeria');
INSERT INTO eZContact_Country VALUES (159,'NU','Niue');
INSERT INTO eZContact_Country VALUES (160,'NF','Norfolk Island');
INSERT INTO eZContact_Country VALUES (161,'MP','Northern Mariana Islands');
INSERT INTO eZContact_Country VALUES (162,'NO','Norway');
INSERT INTO eZContact_Country VALUES (163,'OM','Oman');
INSERT INTO eZContact_Country VALUES (164,'PK','Pakistan');
INSERT INTO eZContact_Country VALUES (165,'PW','Palau');
INSERT INTO eZContact_Country VALUES (166,'PA','Panama');
INSERT INTO eZContact_Country VALUES (167,'PG','Papua New Guinea');
INSERT INTO eZContact_Country VALUES (168,'PY','Paraguay');
INSERT INTO eZContact_Country VALUES (169,'PE','Peru');
INSERT INTO eZContact_Country VALUES (170,'PH','Philippines');
INSERT INTO eZContact_Country VALUES (171,'PN','Pitcairn');
INSERT INTO eZContact_Country VALUES (172,'PL','Poland');
INSERT INTO eZContact_Country VALUES (173,'PT','Portugal');
INSERT INTO eZContact_Country VALUES (174,'PR','Puerto Rico');
INSERT INTO eZContact_Country VALUES (175,'QA','Qatar');
INSERT INTO eZContact_Country VALUES (176,'RE','Reunion');
INSERT INTO eZContact_Country VALUES (177,'RO','Romania');
INSERT INTO eZContact_Country VALUES (178,'RU','Russian Federation');
INSERT INTO eZContact_Country VALUES (179,'RW','Rwanda');
INSERT INTO eZContact_Country VALUES (180,'SH','Saint Helena');
INSERT INTO eZContact_Country VALUES (181,'KN','Saint Kitts and Nevis');
INSERT INTO eZContact_Country VALUES (182,'LC','Saint Lucia');
INSERT INTO eZContact_Country VALUES (183,'PM','Saint Pierre and Miquelon');
INSERT INTO eZContact_Country VALUES (184,'VC','Saint Vincent and the Grenadines');
INSERT INTO eZContact_Country VALUES (185,'WS','Samoa');
INSERT INTO eZContact_Country VALUES (186,'SM','San Marino');
INSERT INTO eZContact_Country VALUES (187,'ST','Sao Tome and Principe');
INSERT INTO eZContact_Country VALUES (188,'SA','Saudi Arabia');
INSERT INTO eZContact_Country VALUES (189,'SN','Senegal');
INSERT INTO eZContact_Country VALUES (190,'SC','Seychelles');
INSERT INTO eZContact_Country VALUES (191,'SL','Sierra Leone');
INSERT INTO eZContact_Country VALUES (192,'SG','Singapore');
INSERT INTO eZContact_Country VALUES (193,'SK','Slovakia');
INSERT INTO eZContact_Country VALUES (194,'SI','Slovenia');
INSERT INTO eZContact_Country VALUES (195,'SB','Solomon Islands');
INSERT INTO eZContact_Country VALUES (196,'SO','Somalia');
INSERT INTO eZContact_Country VALUES (197,'ZA','South Africa');
INSERT INTO eZContact_Country VALUES (198,'GS','South Georgia and the South Sandwich Island');
INSERT INTO eZContact_Country VALUES (199,'ES','Spain');
INSERT INTO eZContact_Country VALUES (200,'LK','Sri Lanka');
INSERT INTO eZContact_Country VALUES (201,'SD','Sudan');
INSERT INTO eZContact_Country VALUES (202,'SR','Suriname');
INSERT INTO eZContact_Country VALUES (203,'SJ','Svalbard and Jan Mayen Islands');
INSERT INTO eZContact_Country VALUES (204,'SZ','Swaziland');
INSERT INTO eZContact_Country VALUES (205,'SE','Sweden');
INSERT INTO eZContact_Country VALUES (206,'CH','Switzerland');
INSERT INTO eZContact_Country VALUES (207,'SY','Syrian Arab Republic');
INSERT INTO eZContact_Country VALUES (208,'TW','Taiwan, Republic of China');
INSERT INTO eZContact_Country VALUES (209,'TJ','Tajikistan');
INSERT INTO eZContact_Country VALUES (210,'TZ','Tanzania, United Republic of');
INSERT INTO eZContact_Country VALUES (211,'TH','Thailand');
INSERT INTO eZContact_Country VALUES (212,'TG','Togo');
INSERT INTO eZContact_Country VALUES (213,'TK','Tokelau');
INSERT INTO eZContact_Country VALUES (214,'TO','Tonga');
INSERT INTO eZContact_Country VALUES (215,'TT','Trinidad and Tobago');
INSERT INTO eZContact_Country VALUES (216,'TN','Tunisia');
INSERT INTO eZContact_Country VALUES (217,'TR','Turkey');
INSERT INTO eZContact_Country VALUES (218,'TM','Turkmenistan');
INSERT INTO eZContact_Country VALUES (219,'TC','Turks and Caicos Islands');
INSERT INTO eZContact_Country VALUES (220,'TV','Tuvalu');
INSERT INTO eZContact_Country VALUES (221,'UG','Uganda');
INSERT INTO eZContact_Country VALUES (222,'UA','Ukraine');
INSERT INTO eZContact_Country VALUES (223,'AE','United Arab Emirates');
INSERT INTO eZContact_Country VALUES (224,'GB','United Kingdom');
INSERT INTO eZContact_Country VALUES (225,'UM','United States Minor Outlying Islands');
INSERT INTO eZContact_Country VALUES (226,'UY','Uruguay');
INSERT INTO eZContact_Country VALUES (227,'UZ','Uzbekistan');
INSERT INTO eZContact_Country VALUES (228,'VU','Vanuatu');
INSERT INTO eZContact_Country VALUES (229,'VA','Vatican City State (Holy See)');
INSERT INTO eZContact_Country VALUES (230,'VE','Venezuela');
INSERT INTO eZContact_Country VALUES (231,'VN','Viet Nam');
INSERT INTO eZContact_Country VALUES (232,'VG','Virgin Islands (British)');
INSERT INTO eZContact_Country VALUES (233,'VI','Virgin Islands (U.S.)');
INSERT INTO eZContact_Country VALUES (234,'WF','Wallis and Futuna Islands');
INSERT INTO eZContact_Country VALUES (235,'EH','Western Sahara');
INSERT INTO eZContact_Country VALUES (236,'YE','Yemen');
INSERT INTO eZContact_Country VALUES (237,'YU','Yugoslavia');
INSERT INTO eZContact_Country VALUES (238,'ZR','Zaire');
INSERT INTO eZContact_Country VALUES (239,'ZM','Zambia');

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
# Table structure for table 'eZContact_Online'
#
DROP TABLE IF EXISTS eZContact_Online;
CREATE TABLE eZContact_Online (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  URL char(255),
  URLType enum('mailto','http','https','ftp','news') DEFAULT 'mailto' NOT NULL,
  OnlineTypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_Online'
#

#
# Table structure for table 'eZContact_OnlineType'
#
DROP TABLE IF EXISTS eZContact_OnlineType;
CREATE TABLE eZContact_OnlineType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_OnlineType'
#

#
# Table structure for table 'eZContact_Person'
#
DROP TABLE IF EXISTS eZContact_Person;
CREATE TABLE eZContact_Person (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CreatorID int(11) DEFAULT '0' NOT NULL,
  FirstName varchar(50),
  LastName varchar(50),
  BirthDate date,
  PersonNo varchar(50),
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
# Table structure for table 'eZContact_Phone'
#
DROP TABLE IF EXISTS eZContact_Phone;
CREATE TABLE eZContact_Phone (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Number varchar(22),
  PhoneTypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_Phone'
#

#
# Table structure for table 'eZContact_PhoneType'
#
DROP TABLE IF EXISTS eZContact_PhoneType;
CREATE TABLE eZContact_PhoneType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_PhoneType'
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

#
# Dumping data for table 'eZContact_UserPersonDict'
#
