# MySQL dump 7.1
#
# Host: localhost    Database: trade
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'eZArticle_Article'
#
DROP TABLE IF EXISTS eZArticle_Article;
CREATE TABLE eZArticle_Article (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Contents text,
  AuthorText varchar(100),
  LinkText varchar(50),
  AuthorID int(11) DEFAULT '0' NOT NULL,
  Modified timestamp(14),
  Created timestamp(14),
  PageCount int(11),
  IsPublished enum('true','false') DEFAULT 'false',
  Published timestamp(14),
  Keywords text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_Article'
#

INSERT INTO eZArticle_Article VALUES (1,'Demo article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This is a demo article. It will demontrate the power of the eZTechRenderer used for generating articles.</intro><body><page><header>Here I will demonstrate some simple tags</header>\r\n\r\n<bold> this is bold text</bold> \r\n<italic>this is italic text</italic>\r\n<strike>this is strike through text</strike>\r\n\r\n<link href=\"ez.no\" text=\"this is a link\" />\r\n<mail to=\"bf@ez.no\" subject=\"demo\" text=\"mail me\" /> a mail link with subject set to demo\r\n\r\n</page><page>\r\n\r\n<header>Here I will demonstrate images</header>\r\n\r\nAs you see the images are generated on the fly, so you can request any size (small, medium, large) at any time.\r\n\r\n<image id=\"1\" align=\"left\" size=\"small\" /> This is a small image. Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla .\r\n\r\nbla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla .\r\n\r\n<header>A large image:</header>\r\n\r\n<image id=\"1\" align=\"center\" size=\"large\" /> \r\n\r\n</page><page>\r\n\r\n<header>Coding tags</header>\r\n\r\nHere I will demonstrate som programming tags.\r\n\r\n<php>\r\n// this is php code\r\nfunction foo()\r\n{\r\n  bar();\r\n}\r\n</php>\r\n\r\nAnd some cpp:\r\n<cpp>\r\nclass foo\r\n{\r\n  foo();\r\n  void bar();\r\n}\r\n</cpp>\r\n\r\nAnd \r\n<ezhtml>\r\n&lt;html&gt;\r\n&lt;head&gt;\r\n  &lt;title&gt;\r\n  Title\r\n  &lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\nthis is the body\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n</ezhtml>\r\n\r\n</page></body></article>','Bård Farstad','read',1,20001101152408,20001101152255,3,'true',20001101152255,'tech\nThis is a demo article. It will demontrate the power of eZTechRenderer used for generating articles.Here I demonstrate some simple tags\r\n\r\n this bold text \r\nthis italic text\r\nthis strike through text\r\n\r\n\r\n mail link with subject set to demo\r\n\r\n\r\n\r\nHere images\r\n\r\nAs you see images are generated on fly, so can request any size (small, medium, large) at time.\r\n\r\n This small image. Bla bla .\r\n\r\nbla .\r\n\r\nA large image:\r\n\r\n \r\n\r\n\r\n\r\nCoding tags\r\n\r\nHere som programming tags.\r\n\r\n\r\n// php code\r\nfunction foo()\r\n{\r\n  bar();\r\n}\r\n\r\n\r\nAnd cpp:\r\n\r\nclass foo\r\n{\r\n foo();\r\n void \r\n\r\n&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;\r\n Title\r\n &lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\nthis body\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n\r\n\r\n ');

#
# Table structure for table 'eZArticle_ArticleCategoryLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleCategoryLink;
CREATE TABLE eZArticle_ArticleCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleCategoryLink'
#

INSERT INTO eZArticle_ArticleCategoryLink VALUES (1,1,1);

#
# Table structure for table 'eZArticle_ArticleImageDefinition'
#
DROP TABLE IF EXISTS eZArticle_ArticleImageDefinition;
CREATE TABLE eZArticle_ArticleImageDefinition (
  ArticleID int(11) DEFAULT '0' NOT NULL,
  ThumbnailImageID int(11),
  PRIMARY KEY (ArticleID),
  UNIQUE ArticleID (ArticleID)
);

#
# Dumping data for table 'eZArticle_ArticleImageDefinition'
#

INSERT INTO eZArticle_ArticleImageDefinition VALUES (1,1);

#
# Table structure for table 'eZArticle_ArticleImageLink'
#
DROP TABLE IF EXISTS eZArticle_ArticleImageLink;
CREATE TABLE eZArticle_ArticleImageLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  ImageID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleImageLink'
#

INSERT INTO eZArticle_ArticleImageLink VALUES (1,1,1,20001101152355);

#
# Table structure for table 'eZArticle_Category'
#
DROP TABLE IF EXISTS eZArticle_Category;
CREATE TABLE eZArticle_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11) DEFAULT '0',
  ExcludeFromSearch enum('true','false') DEFAULT 'false',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_Category'
#

INSERT INTO eZArticle_Category VALUES (1,'News','',0,'false');

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
  CountryID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_Address'
#

INSERT INTO eZContact_Address VALUES (1,'Rødsåsen 39','',0,'Porsgrunn','3928',4);

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
# Dumping data for table 'eZContact_AddressType'
#


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
# Dumping data for table 'eZContact_Company'
#


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
# Dumping data for table 'eZContact_CompanyAddressDict'
#


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
# Dumping data for table 'eZContact_CompanyConsultDict'
#


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
# Dumping data for table 'eZContact_CompanyPhoneDict'
#


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
# Dumping data for table 'eZContact_CompanyType'
#


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
# Dumping data for table 'eZContact_Consult'
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
# Dumping data for table 'eZContact_Note'
#


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
# Dumping data for table 'eZContact_Person'
#


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
# Dumping data for table 'eZContact_PersonAddressDict'
#


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
# Dumping data for table 'eZContact_PersonConsultDict'
#


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
# Dumping data for table 'eZContact_PersonPhoneDict'
#


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
# Dumping data for table 'eZContact_PersonType'
#


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
# Dumping data for table 'eZContact_Phone'
#


#
# Table structure for table 'eZContact_PhoneType'
#
DROP TABLE IF EXISTS eZContact_PhoneType;
CREATE TABLE eZContact_PhoneType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_PhoneType'
#


#
# Table structure for table 'eZForum_Category'
#
DROP TABLE IF EXISTS eZForum_Category;
CREATE TABLE eZForum_Category (
  Name varchar(20),
  Description varchar(40),
  Private enum('Y','N') DEFAULT 'N',
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Category'
#

INSERT INTO eZForum_Category VALUES ('tets','test','N',1);
INSERT INTO eZForum_Category VALUES ('Kattegori','Handler om kattedyr','N',2);
INSERT INTO eZForum_Category VALUES ('ned i den','Bakoverlesing','N',3);

#
# Table structure for table 'eZForum_Forum'
#
DROP TABLE IF EXISTS eZForum_Forum;
CREATE TABLE eZForum_Forum (
  CategoryID int(11) DEFAULT '0' NOT NULL,
  Name varchar(20) DEFAULT '' NOT NULL,
  Description varchar(40),
  Moderated enum('Y','N') DEFAULT 'N',
  Private enum('Y','N') DEFAULT 'N',
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Forum'
#

INSERT INTO eZForum_Forum VALUES (2,'Huskatter','Mjau!','','',2);
INSERT INTO eZForum_Forum VALUES (2,'Løver','Brøhl!','','',3);
INSERT INTO eZForum_Forum VALUES (3,'321123','123312123312','','',5);
INSERT INTO eZForum_Forum VALUES (1,'test','test','','',6);

#
# Table structure for table 'eZForum_Message'
#
DROP TABLE IF EXISTS eZForum_Message;
CREATE TABLE eZForum_Message (
  ForumID int(11) DEFAULT '0' NOT NULL,
  Topic varchar(60),
  Body text,
  UserID int(11),
  Parent int(11),
  EmailNotice enum('N','Y') DEFAULT 'N',
  PostingTime timestamp(14),
  TreeID int(11),
  ThreadID int(11),
  Depth int(11),
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Message'
#

INSERT INTO eZForum_Message VALUES (0,'123','123',2,0,'Y',20001018084129,2,2,0,3);
INSERT INTO eZForum_Message VALUES (1,'tjobing','bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla \r\n\r\nbla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla ',25,0,'N',20001020150036,22,14,0,23);
INSERT INTO eZForum_Message VALUES (5,'Nå!','Slik, ja... Nå er det en melding her!',3,0,'N',20001020155358,23,15,0,24);
INSERT INTO eZForum_Message VALUES (5,'Agnes i senga?!','Her skulle vi hatt en interrobang...\r\n\r\n?!\r\n\r\n!?\r\n\r\n',3,0,'N',20001020155753,24,16,0,25);
INSERT INTO eZForum_Message VALUES (4,'hhhhhhhh','hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh',2,0,'Y',20001018152727,10,5,0,9);
INSERT INTO eZForum_Message VALUES (4,'SV: hhhhhhhh','>\r\nhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh',2,9,'N',20001018152727,9,5,1,10);
INSERT INTO eZForum_Message VALUES (1,'tjoho','312',2,0,'N',20001020143911,14,7,0,12);
INSERT INTO eZForum_Message VALUES (1,'SV: 321','>\r\n312',2,12,'N',20001019205128,13,7,1,13);
INSERT INTO eZForum_Message VALUES (2,'Katter?','Hvorfor denne diskusjonen?',3,0,'N',20001019205128,15,8,0,14);
INSERT INTO eZForum_Message VALUES (3,'Test!','Dette er en test!',3,0,'N',20001019205128,16,9,0,15);
INSERT INTO eZForum_Message VALUES (2,'Jadda...','Hmmm....\r\n\r\nJa...\r\n\r\nHvorfor ikke?',3,0,'N',20001019205128,19,12,0,19);
INSERT INTO eZForum_Message VALUES (1,'SV: SV: 321','bla\r\nbla\r\nbla\r\nbla\r\nbla\r\nbla\r\nbla\r\nbla\r\n',25,13,'N',20001019205128,12,7,2,20);
INSERT INTO eZForum_Message VALUES (3,'hmm, dette funker jo fint....','tjohohohohoh',2,0,'N',20001020143556,21,13,0,21);
INSERT INTO eZForum_Message VALUES (3,'SV: hmm, dette funker jo fint','^>tjohohohohoh\r\npiowjegoåiwejgoåuwehgouwehngojwuehngojwngwoejgnwegwe',2,21,'N',20001020122531,20,13,1,22);
INSERT INTO eZForum_Message VALUES (6,'first post','asdfasdf',25,0,'N',20001022133116,26,17,0,26);
INSERT INTO eZForum_Message VALUES (6,'SV: first post','>asdfasdf',25,26,'N',20001022133116,25,17,1,27);
INSERT INTO eZForum_Message VALUES (6,'asdf','asdfasdf',25,0,'N',20001022133144,27,18,0,28);

#
# Table structure for table 'eZImageCatalogue_Image'
#
DROP TABLE IF EXISTS eZImageCatalogue_Image;
CREATE TABLE eZImageCatalogue_Image (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Caption text,
  Description text,
  FileName varchar(100),
  OriginalFileName varchar(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_Image'
#

INSERT INTO eZImageCatalogue_Image VALUES (1,'','caption text','','phpnXJLgk.jpg','360_tail.jpg');
INSERT INTO eZImageCatalogue_Image VALUES (2,'','flower','','phpSzUU7U.jpg','DSCN1906.JPG');

#
# Table structure for table 'eZImageCatalogue_ImageVariation'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageVariation;
CREATE TABLE eZImageCatalogue_ImageVariation (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ImageID int(11),
  VariationGroupID int(11),
  ImagePath char(100),
  Width int(11),
  Height int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_ImageVariation'
#

INSERT INTO eZImageCatalogue_ImageVariation VALUES (1,1,1,'ezimagecatalogue/catalogue/variations/1-150x150.jpg',150,143);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (2,1,2,'ezimagecatalogue/catalogue/variations/1-100x100.jpg',100,95);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (3,1,3,'ezimagecatalogue/catalogue/variations/1-300x300.jpg',150,143);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (4,2,1,'ezimagecatalogue/catalogue/variations/2-150x150.jpg',150,113);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (5,2,4,'ezimagecatalogue/catalogue/variations/2-250x250.jpg',250,188);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (6,2,5,'ezimagecatalogue/catalogue/variations/2-35x35.jpg',35,26);

#
# Table structure for table 'eZImageCatalogue_ImageVariationGroup'
#
DROP TABLE IF EXISTS eZImageCatalogue_ImageVariationGroup;
CREATE TABLE eZImageCatalogue_ImageVariationGroup (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Width int(11),
  Height int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_ImageVariationGroup'
#

INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (1,150,150);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (2,100,100);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (3,300,300);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (4,250,250);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (5,35,35);

#
# Table structure for table 'eZLink_Hit'
#
DROP TABLE IF EXISTS eZLink_Hit;
CREATE TABLE eZLink_Hit (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Link int(11),
  Time timestamp(14),
  RemoteIP char(15),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_Hit'
#

INSERT INTO eZLink_Hit VALUES (1,1,20000810150113,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (2,2,20000810161435,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (3,1,20000811094423,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (4,3,20000811095619,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (5,5,20000811110304,'10.0.2.10');
INSERT INTO eZLink_Hit VALUES (6,5,20000814102821,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (7,5,20000814102825,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (8,2,20000814113955,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (9,2,20000822112957,'10.0.2.150');
INSERT INTO eZLink_Hit VALUES (10,2,20000914193747,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (11,2,20000914193818,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (12,2,20000914193843,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (13,2,20000914193845,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (14,5,20000914194141,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (15,5,20000914194143,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (16,4,20000914195254,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (17,4,20000914195257,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (18,2,20000914195305,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (19,2,20000914195306,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (20,3,20000918135443,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (21,3,20000918135447,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (22,2,20001003184707,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (23,8,20001017132315,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (24,2,20001018155751,'10.0.2.9');
INSERT INTO eZLink_Hit VALUES (25,2,20001018160238,'10.0.2.9');
INSERT INTO eZLink_Hit VALUES (26,8,20001018163054,'10.0.2.9');
INSERT INTO eZLink_Hit VALUES (27,8,20001021160910,'10.0.2.11');
INSERT INTO eZLink_Hit VALUES (28,8,20001021161059,'10.0.2.11');
INSERT INTO eZLink_Hit VALUES (29,2,20001021161110,'10.0.2.11');
INSERT INTO eZLink_Hit VALUES (30,12,20001021161313,'10.0.2.11');
INSERT INTO eZLink_Hit VALUES (31,8,20001022133533,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (32,2,20001023090003,'10.0.2.9');

#
# Table structure for table 'eZLink_Link'
#
DROP TABLE IF EXISTS eZLink_Link;
CREATE TABLE eZLink_Link (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Title varchar(100),
  Description text,
  LinkGroup int(11),
  KeyWords varchar(100),
  Modified timestamp(14),
  Accepted enum('Y','N'),
  Created datetime,
  Url varchar(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_Link'
#

INSERT INTO eZLink_Link VALUES (1,'PHPBuilder','PHP developer resources.',1,'php forum web',20000810143854,'Y','2000-08-10 14:38:54','www.phpbuilder.com');
INSERT INTO eZLink_Link VALUES (2,'The Apache Software Foundation','',4,'webserver free',20001009152433,'Y','2000-08-10 16:12:18','www.apache.org');
INSERT INTO eZLink_Link VALUES (3,'World Wide Web consortium','',5,'web standard html xml css xhtml dom html dhtml',20000811094214,'Y','2000-08-11 09:42:14','www.w3.org');
INSERT INTO eZLink_Link VALUES (4,'Pyton Language Website','Home page for Python, an\r\n interpreted, interactive, object-oriented, extensible\r\n programming language.  It provides an extraordinary combination\r\n of clarity and versatility, and is free and comprehensively\r\n ported.',9,'Python programming language object oriented web free source',20000811100249,'Y','2000-08-11 10:02:15','www.python.org/');
INSERT INTO eZLink_Link VALUES (5,'Perl Mongerss','',8,'perl',20001009152232,'Y','2000-08-11 10:05:58','www.perl.org/');
INSERT INTO eZLink_Link VALUES (6,'test','234',0,'ertert',20000816135642,'N','2000-08-16 13:56:42','ertert');
INSERT INTO eZLink_Link VALUES (7,'test','hjerj',5,'ewherher',20001010084356,'N','2000-10-10 08:43:56','wetweg');
INSERT INTO eZLink_Link VALUES (8,'Jakarta homepage','Jakarta homesite. ',4,'java apache applet',20001016121230,'Y','2000-10-11 12:37:29','jakarta.apache.org');
INSERT INTO eZLink_Link VALUES (9,'123','123',4,'31',20001011125341,'N','2000-10-11 12:53:41','123.no');
INSERT INTO eZLink_Link VALUES (10,'te','setset',0,'etset',20001017135436,'N','2000-10-17 13:54:36','tsets');
INSERT INTO eZLink_Link VALUES (11,'123','123',4,'123',20001019112610,'N','2000-10-19 11:26:10','13');
INSERT INTO eZLink_Link VALUES (12,'123','   123',5,'   123',20001019135147,'Y','2000-10-19 13:51:47','123');

#
# Table structure for table 'eZLink_LinkGroup'
#
DROP TABLE IF EXISTS eZLink_LinkGroup;
CREATE TABLE eZLink_LinkGroup (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11) DEFAULT '0',
  Title char(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_LinkGroup'
#

INSERT INTO eZLink_LinkGroup VALUES (1,0,'PHP');
INSERT INTO eZLink_LinkGroup VALUES (2,0,'Linux');
INSERT INTO eZLink_LinkGroup VALUES (3,0,'C++');
INSERT INTO eZLink_LinkGroup VALUES (4,0,'Apache');
INSERT INTO eZLink_LinkGroup VALUES (5,0,'HTML');
INSERT INTO eZLink_LinkGroup VALUES (6,0,'Java');
INSERT INTO eZLink_LinkGroup VALUES (7,0,'Javascript');
INSERT INTO eZLink_LinkGroup VALUES (8,0,'Perl');
INSERT INTO eZLink_LinkGroup VALUES (9,0,'Python');
INSERT INTO eZLink_LinkGroup VALUES (10,0,'test kategori');

#
# Table structure for table 'eZNews_Article'
#
DROP TABLE IF EXISTS eZNews_Article;
CREATE TABLE eZNews_Article (
  ID int(11) DEFAULT '0' NOT NULL,
  Meta longtext,
  Story longtext,
  LinkText varchar(255) DEFAULT '' NOT NULL,
  AuthorText varchar(255) DEFAULT '' NOT NULL,
  AcceptLinks enum('Y','N') DEFAULT 'N' NOT NULL,
  AcceptComments enum('Y','N') DEFAULT 'N' NOT NULL,
  MetaClass varchar(255),
  StoryClass varchar(255),
  PRIMARY KEY (ID),
  KEY AuthorText (AuthorText)
);

#
# Dumping data for table 'eZNews_Article'
#

INSERT INTO eZNews_Article VALUES (11,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (12,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (13,'','','','Thomas Hellstrøm','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (14,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (15,'','','','Thomas Hellstrøm','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (16,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (17,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (18,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (19,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (20,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (21,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (22,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (23,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (24,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (25,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (26,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (27,'Plain text','fdfsdfdsadfas','Public description','automatic','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (28,'','','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (29,'','<?xml version=\"1.0\"?>\n<ezflower>\n<name>fdsaf</name>\n<description>fasdfasdfasf</description>\n<price></price>\n</ezflower>\n','','Paul Kenneth Egell-Johnsen','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (30,'','','','Thomas Hellstrøm','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (31,'','<?xml version=\"1.0\"?>\n<ezflower>\n<name>53563</name>\n<description>63636</description>\n<price>4256</price>\n</ezflower>\n','','Thomas Hellstrøm','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (32,'Plain text','Dette er en test! eggweg','Public description','automatic','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (33,'','<?xml version=\"1.0\"?>\n<ezflower>\n<name>test</name>\n<description>testesetset</description>\n<price>setsetsetset</price>\n</ezflower>\n','','Bård Farstad','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (34,'','<?xml version=\"1.0\"?>\n<ezflower>\n<name>teset</name>\n<description>asdf asdf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf asfd dsaf sadf</description>\n<price>23\n42\n34</price>\n</ezflower>\n','','Bård Farstad','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (35,'','<?xml version=\"1.0\"?>\n<ezflower>\n<name>setset</name>\n<description>setsets se tse tset set se tsetse tse</description>\n<price>234 234 234 234234 2</price>\n</ezflower>\n','','Bård Farstad','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (36,'','<?xml version=\"1.0\"?>\n<ezflower>\n<name>asdfasdf</name>\n<description>asdfaasdfasdf</description>\n<price>asdfasdf</price>\n</ezflower>\n','','Bård Farstad','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (37,'','<?xml version=\"1.0\"?>\n<ezflower>\n<name>wetwe</name>\n<description>twetwet</description>\n<price>wetwetwetwetwet</price>\n</ezflower>\n','','Bård Farstad','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (38,'','<?xml version=\"1.0\"?>\n<ezflower>\n<name>wer</name>\n<description>werwer</description>\n<price>234234234</price>\n</ezflower>\n','','Bård Farstad','N','N',NULL,NULL);
INSERT INTO eZNews_Article VALUES (39,'','<?xml version=\"1.0\"?>\n<ezflower>\n<name>34534</name>\n<description>534 345 34 345 34</description>\n<price>3453 45 345 345</price>\n</ezflower>\n','','Bård Farstad','N','N',NULL,NULL);

#
# Table structure for table 'eZNews_Category'
#
DROP TABLE IF EXISTS eZNews_Category;
CREATE TABLE eZNews_Category (
  ID int(11) DEFAULT '0' NOT NULL,
  PublicDescriptionID int(11),
  PrivateDescriptionID int(11),
  AcceptSubcategories enum('Y','N') DEFAULT 'Y' NOT NULL,
  hasImage enum('Y','N') DEFAULT 'N' NOT NULL,
  ImageID int(11) DEFAULT '0' NOT NULL,
  OrderedBy enum('value','manual','date','author','popularity') DEFAULT 'value' NOT NULL,
  Direction enum('forward','reverse') DEFAULT 'forward' NOT NULL,
  PropagateUp enum('N','Y') DEFAULT 'Y' NOT NULL,
  PropagateNoItems int(11) DEFAULT '1' NOT NULL,
  PropagatedBy enum('value','manual','date','author','popularity') DEFAULT 'value' NOT NULL,
  PropagationDirection enum('forward','reverse') DEFAULT 'forward' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNews_Category'
#

INSERT INTO eZNews_Category VALUES (1,0,0,'Y','N',0,'value','forward','Y',1,'value','forward');
INSERT INTO eZNews_Category VALUES (2,0,0,'Y','N',0,'value','forward','Y',1,'value','forward');
INSERT INTO eZNews_Category VALUES (3,27,0,'Y','N',0,'value','forward','Y',1,'value','forward');
INSERT INTO eZNews_Category VALUES (4,0,0,'Y','N',0,'value','forward','Y',1,'value','forward');
INSERT INTO eZNews_Category VALUES (5,0,0,'Y','N',0,'value','forward','Y',1,'value','forward');
INSERT INTO eZNews_Category VALUES (6,0,0,'Y','N',0,'value','forward','Y',1,'value','forward');
INSERT INTO eZNews_Category VALUES (7,0,0,'Y','N',0,'value','forward','Y',1,'value','forward');
INSERT INTO eZNews_Category VALUES (8,32,0,'Y','N',0,'value','forward','Y',1,'value','forward');
INSERT INTO eZNews_Category VALUES (9,0,0,'Y','N',0,'value','forward','Y',1,'value','forward');
INSERT INTO eZNews_Category VALUES (10,0,0,'Y','N',0,'value','forward','Y',1,'value','forward');

#
# Table structure for table 'eZNews_ChangeTicket'
#
DROP TABLE IF EXISTS eZNews_ChangeTicket;
CREATE TABLE eZNews_ChangeTicket (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(255) DEFAULT '' NOT NULL,
  ChangeInfo int(11) DEFAULT '0' NOT NULL,
  ChangeTypeID int(11) DEFAULT '0' NOT NULL,
  ChangedBy int(11) DEFAULT '0' NOT NULL,
  ChangedAt timestamp(14),
  ChangeIP varchar(50),
  PRIMARY KEY (ID),
  KEY Name (Name),
  KEY ChangeInfo (ChangeInfo),
  KEY ChangedBy (ChangedBy),
  KEY ChangeTypeID (ChangeTypeID)
);

#
# Dumping data for table 'eZNews_ChangeTicket'
#

INSERT INTO eZNews_ChangeTicket VALUES (1,'0: Status changed from (0) to temporary(11)',0,11,6,20001016153959,'10.0.2.16/2197');
INSERT INTO eZNews_ChangeTicket VALUES (2,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016154000,'10.0.2.16/2197');
INSERT INTO eZNews_ChangeTicket VALUES (3,'11: Was stored',0,11,6,20001016154002,'10.0.2.16/2197');
INSERT INTO eZNews_ChangeTicket VALUES (4,'0: Status changed from (0) to temporary(11)',0,11,6,20001016154052,'10.0.2.16/2202');
INSERT INTO eZNews_ChangeTicket VALUES (5,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016154052,'10.0.2.16/2202');
INSERT INTO eZNews_ChangeTicket VALUES (6,'12: Was stored',0,11,6,20001016154052,'10.0.2.16/2202');
INSERT INTO eZNews_ChangeTicket VALUES (7,'0: Status changed from (0) to temporary(11)',0,11,3,20001016154103,'10.0.2.150/2753');
INSERT INTO eZNews_ChangeTicket VALUES (8,'0: Item Type changed from (0) to flowerarticle(9)',0,11,3,20001016154103,'10.0.2.150/2753');
INSERT INTO eZNews_ChangeTicket VALUES (9,'13: Was stored',0,11,3,20001016154103,'10.0.2.150/2753');
INSERT INTO eZNews_ChangeTicket VALUES (10,'0: Status changed from (0) to temporary(11)',0,11,6,20001016154110,'10.0.2.16/2204');
INSERT INTO eZNews_ChangeTicket VALUES (11,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016154110,'10.0.2.16/2204');
INSERT INTO eZNews_ChangeTicket VALUES (12,'14: Was stored',0,11,6,20001016154111,'10.0.2.16/2204');
INSERT INTO eZNews_ChangeTicket VALUES (13,'0: Status changed from (0) to temporary(11)',0,11,3,20001016154137,'10.0.2.150/2755');
INSERT INTO eZNews_ChangeTicket VALUES (14,'0: Item Type changed from (0) to flowerarticle(9)',0,11,3,20001016154138,'10.0.2.150/2755');
INSERT INTO eZNews_ChangeTicket VALUES (15,'15: Was stored',0,11,3,20001016154138,'10.0.2.150/2755');
INSERT INTO eZNews_ChangeTicket VALUES (16,'0: Status changed from (0) to temporary(11)',0,11,6,20001016154152,'10.0.2.16/2211');
INSERT INTO eZNews_ChangeTicket VALUES (17,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016154152,'10.0.2.16/2211');
INSERT INTO eZNews_ChangeTicket VALUES (18,'16: Was stored',0,11,6,20001016154153,'10.0.2.16/2211');
INSERT INTO eZNews_ChangeTicket VALUES (19,'15: Item was deleted',0,1,3,20001016154205,'10.0.2.150/2755');
INSERT INTO eZNews_ChangeTicket VALUES (20,'16: Name changed from  to no-name',0,11,6,20001016154219,'10.0.2.16/2216');
INSERT INTO eZNews_ChangeTicket VALUES (21,'16: Item was deleted',0,1,6,20001016154220,'10.0.2.16/2216');
INSERT INTO eZNews_ChangeTicket VALUES (22,'16: Was updated',0,1,6,20001016154220,'10.0.2.16/2216');
INSERT INTO eZNews_ChangeTicket VALUES (23,'0: Status changed from (0) to temporary(11)',0,11,6,20001016154234,'10.0.2.16/2217');
INSERT INTO eZNews_ChangeTicket VALUES (24,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016154235,'10.0.2.16/2217');
INSERT INTO eZNews_ChangeTicket VALUES (25,'17: Was stored',0,11,6,20001016154235,'10.0.2.16/2217');
INSERT INTO eZNews_ChangeTicket VALUES (26,'0: Status changed from (0) to temporary(11)',0,11,6,20001016154402,'10.0.2.16/2221');
INSERT INTO eZNews_ChangeTicket VALUES (27,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016154403,'10.0.2.16/2221');
INSERT INTO eZNews_ChangeTicket VALUES (28,'18: Was stored',0,11,6,20001016154403,'10.0.2.16/2221');
INSERT INTO eZNews_ChangeTicket VALUES (29,'0: Status changed from (0) to temporary(11)',0,11,6,20001016154558,'10.0.2.16/2222');
INSERT INTO eZNews_ChangeTicket VALUES (30,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016154559,'10.0.2.16/2222');
INSERT INTO eZNews_ChangeTicket VALUES (31,'19: Was stored',0,11,6,20001016154600,'10.0.2.16/2222');
INSERT INTO eZNews_ChangeTicket VALUES (32,'0: Status changed from (0) to temporary(11)',0,11,6,20001016154631,'10.0.2.16/2227');
INSERT INTO eZNews_ChangeTicket VALUES (33,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016154632,'10.0.2.16/2227');
INSERT INTO eZNews_ChangeTicket VALUES (34,'20: Was stored',0,11,6,20001016154632,'10.0.2.16/2227');
INSERT INTO eZNews_ChangeTicket VALUES (35,'0: Status changed from (0) to temporary(11)',0,11,6,20001016160153,'10.0.2.16/2312');
INSERT INTO eZNews_ChangeTicket VALUES (36,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016160153,'10.0.2.16/2312');
INSERT INTO eZNews_ChangeTicket VALUES (37,'21: Was stored',0,11,6,20001016160153,'10.0.2.16/2312');
INSERT INTO eZNews_ChangeTicket VALUES (38,'0: Status changed from (0) to temporary(11)',0,11,6,20001016160228,'10.0.2.16/2317');
INSERT INTO eZNews_ChangeTicket VALUES (39,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016160228,'10.0.2.16/2317');
INSERT INTO eZNews_ChangeTicket VALUES (40,'22: Was stored',0,11,6,20001016160229,'10.0.2.16/2317');
INSERT INTO eZNews_ChangeTicket VALUES (41,'0: Status changed from (0) to temporary(11)',0,11,6,20001016160308,'10.0.2.16/2322');
INSERT INTO eZNews_ChangeTicket VALUES (42,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016160309,'10.0.2.16/2322');
INSERT INTO eZNews_ChangeTicket VALUES (43,'23: Was stored',0,11,6,20001016160309,'10.0.2.16/2322');
INSERT INTO eZNews_ChangeTicket VALUES (44,'0: Status changed from (0) to temporary(11)',0,11,6,20001016161233,'10.0.2.16/2334');
INSERT INTO eZNews_ChangeTicket VALUES (45,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016161233,'10.0.2.16/2334');
INSERT INTO eZNews_ChangeTicket VALUES (46,'24: Was stored',0,11,6,20001016161233,'10.0.2.16/2334');
INSERT INTO eZNews_ChangeTicket VALUES (47,'24: Name changed from  to no-name',0,11,6,20001016162738,'10.0.2.16/2343');
INSERT INTO eZNews_ChangeTicket VALUES (48,'24: Item was deleted',0,1,6,20001016162739,'10.0.2.16/2343');
INSERT INTO eZNews_ChangeTicket VALUES (49,'24: Was updated',0,1,6,20001016162739,'10.0.2.16/2343');
INSERT INTO eZNews_ChangeTicket VALUES (50,'0: Status changed from (0) to temporary(11)',0,11,6,20001016162754,'10.0.2.16/2344');
INSERT INTO eZNews_ChangeTicket VALUES (51,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016162754,'10.0.2.16/2344');
INSERT INTO eZNews_ChangeTicket VALUES (52,'25: Was stored',0,11,6,20001016162755,'10.0.2.16/2344');
INSERT INTO eZNews_ChangeTicket VALUES (53,'25: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name></name>\n<description>eryterreet</description>\n<price></price>\n</ezflower>\n',0,11,6,20001016162810,'10.0.2.16/2345');
INSERT INTO eZNews_ChangeTicket VALUES (54,'25: Name changed from  to no-name',0,11,6,20001016162827,'10.0.2.16/2347');
INSERT INTO eZNews_ChangeTicket VALUES (55,'25: Item was deleted',0,1,6,20001016162828,'10.0.2.16/2347');
INSERT INTO eZNews_ChangeTicket VALUES (56,'25: Was updated',0,1,6,20001016162828,'10.0.2.16/2347');
INSERT INTO eZNews_ChangeTicket VALUES (57,'0: Status changed from (0) to temporary(11)',0,11,6,20001016163422,'10.0.2.16/2355');
INSERT INTO eZNews_ChangeTicket VALUES (58,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016163423,'10.0.2.16/2355');
INSERT INTO eZNews_ChangeTicket VALUES (59,'26: Was stored',0,11,6,20001016163423,'10.0.2.16/2355');
INSERT INTO eZNews_ChangeTicket VALUES (60,'26: Name changed from  to no-name',0,11,6,20001016163434,'10.0.2.16/2357');
INSERT INTO eZNews_ChangeTicket VALUES (61,'26: Item was deleted',0,1,6,20001016163434,'10.0.2.16/2357');
INSERT INTO eZNews_ChangeTicket VALUES (62,'26: Was updated',0,1,6,20001016163434,'10.0.2.16/2357');
INSERT INTO eZNews_ChangeTicket VALUES (63,'3: PublicDescriptionID changed from 0 (nothing) to 27 ( Public description for Blomster )',0,5,6,20001016163648,'10.0.2.16/2370');
INSERT INTO eZNews_ChangeTicket VALUES (64,'3: Was updated',0,5,6,20001016163648,'10.0.2.16/2370');
INSERT INTO eZNews_ChangeTicket VALUES (65,'0: Status changed from (0) to temporary(11)',0,11,6,20001016163706,'10.0.2.16/2372');
INSERT INTO eZNews_ChangeTicket VALUES (66,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016163706,'10.0.2.16/2372');
INSERT INTO eZNews_ChangeTicket VALUES (67,'28: Was stored',0,11,6,20001016163706,'10.0.2.16/2372');
INSERT INTO eZNews_ChangeTicket VALUES (68,'28: Name changed from  to no-name',0,11,6,20001016163718,'10.0.2.16/2373');
INSERT INTO eZNews_ChangeTicket VALUES (69,'28: Item was deleted',0,1,6,20001016163718,'10.0.2.16/2373');
INSERT INTO eZNews_ChangeTicket VALUES (70,'28: Was updated',0,1,6,20001016163718,'10.0.2.16/2373');
INSERT INTO eZNews_ChangeTicket VALUES (71,'0: Status changed from (0) to temporary(11)',0,11,6,20001016163734,'10.0.2.16/2374');
INSERT INTO eZNews_ChangeTicket VALUES (72,'0: Item Type changed from (0) to flowerarticle(9)',0,11,6,20001016163734,'10.0.2.16/2374');
INSERT INTO eZNews_ChangeTicket VALUES (73,'29: Was stored',0,11,6,20001016163735,'10.0.2.16/2374');
INSERT INTO eZNews_ChangeTicket VALUES (74,'29: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name>fdsaf</name>\n<description>fasdfasdfasf</description>\n<price></price>\n</ezflower>\n',0,11,6,20001016163756,'10.0.2.16/2375');
INSERT INTO eZNews_ChangeTicket VALUES (75,'29: Name changed from  to fdsaf',0,11,6,20001016163757,'10.0.2.16/2375');
INSERT INTO eZNews_ChangeTicket VALUES (76,'29: Front Image changed from () to Object(197)',0,11,6,20001016163800,'10.0.2.16/2375');
INSERT INTO eZNews_ChangeTicket VALUES (77,'29: Was updated',0,11,6,20001016163800,'10.0.2.16/2375');
INSERT INTO eZNews_ChangeTicket VALUES (78,'0: Status changed from (0) to temporary(11)',0,11,3,20001016164317,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (79,'0: Item Type changed from (0) to flowerarticle(9)',0,11,3,20001016164317,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (80,'30: Was stored',0,11,3,20001016164318,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (81,'30: Name changed from  to no-name',0,11,3,20001016164336,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (82,'30: Item was deleted',0,1,3,20001016164337,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (83,'30: Was updated',0,1,3,20001016164337,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (84,'0: Status changed from (0) to temporary(11)',0,11,3,20001016164345,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (85,'0: Item Type changed from (0) to flowerarticle(9)',0,11,3,20001016164345,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (86,'31: Was stored',0,11,3,20001016164345,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (87,'31: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name>53563</name>\n<description>63636</description>\n<price>4256</price>\n</ezflower>\n',0,11,3,20001016164358,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (88,'31: Name changed from  to 53563',0,11,3,20001016164358,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (89,'31: Was updated',0,11,3,20001016164359,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (90,'8: PublicDescriptionID changed from 0 (nothing) to 32 ( Public description for Begravelse )',0,5,3,20001016164419,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (91,'8: Was updated',0,5,3,20001016164419,'10.0.2.150/3167');
INSERT INTO eZNews_ChangeTicket VALUES (92,'0: Status changed from (0) to temporary(11)',0,11,25,20001017164058,'10.0.2.3/60492');
INSERT INTO eZNews_ChangeTicket VALUES (93,'0: Item Type changed from (0) to flowerarticle(9)',0,11,25,20001017164058,'10.0.2.3/60492');
INSERT INTO eZNews_ChangeTicket VALUES (94,'33: Was stored',0,11,25,20001017164059,'10.0.2.3/60492');
INSERT INTO eZNews_ChangeTicket VALUES (95,'33: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name>test</name>\n<description>testesetset</description>\n<price>setsetsetset</price>\n</ezflower>\n',0,11,25,20001017164133,'10.0.2.3/60498');
INSERT INTO eZNews_ChangeTicket VALUES (96,'33: Name changed from  to test',0,11,25,20001017164133,'10.0.2.3/60498');
INSERT INTO eZNews_ChangeTicket VALUES (97,'33: Was updated',0,11,25,20001017164133,'10.0.2.3/60498');
INSERT INTO eZNews_ChangeTicket VALUES (98,'33: Status changed from temporary(11) to publish(5)',0,5,25,20001017164136,'10.0.2.3/60498');
INSERT INTO eZNews_ChangeTicket VALUES (99,'33: Was updated',0,5,25,20001017164137,'10.0.2.3/60498');
INSERT INTO eZNews_ChangeTicket VALUES (100,'33: Name changed from test to no-name',0,5,25,20001017165153,'10.0.2.3/60544');
INSERT INTO eZNews_ChangeTicket VALUES (101,'33: Item was deleted',0,1,25,20001017165153,'10.0.2.3/60544');
INSERT INTO eZNews_ChangeTicket VALUES (102,'33: Was updated',0,1,25,20001017165154,'10.0.2.3/60544');
INSERT INTO eZNews_ChangeTicket VALUES (103,'0: Status changed from (0) to temporary(11)',0,11,25,20001017165504,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (104,'0: Item Type changed from (0) to flowerarticle(9)',0,11,25,20001017165504,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (105,'34: Was stored',0,11,25,20001017165505,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (106,'34: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name>teset</name>\n<description>setsetse set set set set set</description>\n<price>setset</price>\n</ezflower>\n',0,11,25,20001017165519,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (107,'34: Name changed from  to teset',0,11,25,20001017165520,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (108,'34: Was updated',0,11,25,20001017165520,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (109,'34: Status changed from temporary(11) to publish(5)',0,5,25,20001017165523,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (110,'34: Was updated',0,5,25,20001017165523,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (111,'0: Status changed from (0) to temporary(11)',0,11,25,20001017165529,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (112,'0: Item Type changed from (0) to flowerarticle(9)',0,11,25,20001017165530,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (113,'35: Was stored',0,11,25,20001017165530,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (114,'35: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name>setset</name>\n<description>setsets se tse tset set se tsetse tse</description>\n<price>234 234 234 234234 2</price>\n</ezflower>\n',0,11,25,20001017165544,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (115,'35: Name changed from  to setset',0,11,25,20001017165544,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (116,'35: Was updated',0,11,25,20001017165544,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (117,'35: Status changed from temporary(11) to publish(5)',0,5,25,20001017165547,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (118,'35: Was updated',0,5,25,20001017165547,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (119,'0: Status changed from (0) to temporary(11)',0,11,25,20001017165555,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (120,'0: Item Type changed from (0) to flowerarticle(9)',0,11,25,20001017165555,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (121,'36: Was stored',0,11,25,20001017165556,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (122,'36: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name>asdfasdf</name>\n<description>asdfaasdfasdf</description>\n<price>asdfasdf</price>\n</ezflower>\n',0,11,25,20001017165608,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (123,'36: Name changed from  to asdfasdf',0,11,25,20001017165608,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (124,'36: Was updated',0,11,25,20001017165608,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (125,'36: Status changed from temporary(11) to publish(5)',0,5,25,20001017165612,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (126,'36: Was updated',0,5,25,20001017165612,'10.0.2.3/60649');
INSERT INTO eZNews_ChangeTicket VALUES (127,'0: Status changed from (0) to temporary(11)',0,11,25,20001017165658,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (128,'0: Item Type changed from (0) to flowerarticle(9)',0,11,25,20001017165658,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (129,'37: Was stored',0,11,25,20001017165658,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (130,'37: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name>wetwe</name>\n<description>twetwet</description>\n<price>wetwetwetwetwet</price>\n</ezflower>\n',0,11,25,20001017165710,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (131,'37: Name changed from  to wetwe',0,11,25,20001017165710,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (132,'37: Was updated',0,11,25,20001017165710,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (133,'37: Status changed from temporary(11) to publish(5)',0,5,25,20001017165713,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (134,'37: Was updated',0,5,25,20001017165713,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (135,'0: Status changed from (0) to temporary(11)',0,11,25,20001017165726,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (136,'0: Item Type changed from (0) to flowerarticle(9)',0,11,25,20001017165726,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (137,'38: Was stored',0,11,25,20001017165727,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (138,'38: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name>wer</name>\n<description>werwer</description>\n<price>234234234</price>\n</ezflower>\n',0,11,25,20001017165737,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (139,'38: Name changed from  to wer',0,11,25,20001017165737,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (140,'38: Was updated',0,11,25,20001017165738,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (141,'38: Status changed from temporary(11) to publish(5)',0,5,25,20001017165742,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (142,'38: Was updated',0,5,25,20001017165742,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (143,'0: Status changed from (0) to temporary(11)',0,11,25,20001017165752,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (144,'0: Item Type changed from (0) to flowerarticle(9)',0,11,25,20001017165752,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (145,'39: Was stored',0,11,25,20001017165752,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (146,'39: Story has changed from  to <?xml version=\"1.0\"?>\n<ezflower>\n<name>34534</name>\n<description>534 345 34 345 34</description>\n<price>3453 45 345 345</price>\n</ezflower>\n',0,11,25,20001017165805,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (147,'39: Name changed from  to 34534',0,11,25,20001017165805,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (148,'39: Was updated',0,11,25,20001017165805,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (149,'39: Status changed from temporary(11) to publish(5)',0,5,25,20001017165809,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (150,'39: Was updated',0,5,25,20001017165809,'10.0.2.3/60652');
INSERT INTO eZNews_ChangeTicket VALUES (151,'34: Story has changed from <?xml version=\"1.0\"?>\n<ezflower>\n<name>teset</name>\n<description>setsetse set set set set set</description>\n<price>setset</price>\n</ezflower>\n to <?xml version=\"1.0\"?>\n<ezflower>\n<name>teset</name>\n<description>asdf asdf asfd ds',0,5,26,20001017172332,'10.0.2.3/32874');
INSERT INTO eZNews_ChangeTicket VALUES (152,'34: Was updated',0,5,26,20001017172333,'10.0.2.3/32874');
INSERT INTO eZNews_ChangeTicket VALUES (153,'34: Name changed from teset to no-name',0,5,26,20001018103336,'10.0.2.3/33591');
INSERT INTO eZNews_ChangeTicket VALUES (154,'34: Item was deleted',0,1,26,20001018103336,'10.0.2.3/33591');
INSERT INTO eZNews_ChangeTicket VALUES (155,'34: Was updated',0,1,26,20001018103336,'10.0.2.3/33591');

#
# Table structure for table 'eZNews_ChangeType'
#
DROP TABLE IF EXISTS eZNews_ChangeType;
CREATE TABLE eZNews_ChangeType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(255) DEFAULT '' NOT NULL,
  Description varchar(255) DEFAULT '' NOT NULL,
  eZArguments int(2) DEFAULT '0' NOT NULL,
  eZSelect text NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE Name (Name)
);

#
# Dumping data for table 'eZNews_ChangeType'
#

INSERT INTO eZNews_ChangeType VALUES (1,'delete','The item has been deleted',0,'');
INSERT INTO eZNews_ChangeType VALUES (2,'create','The item has been create',0,'');
INSERT INTO eZNews_ChangeType VALUES (3,'draft','The item has been drafted',0,'');
INSERT INTO eZNews_ChangeType VALUES (4,'other','We don\'t know how to categorize this change',0,'');
INSERT INTO eZNews_ChangeType VALUES (5,'publish','The item has been publish',0,'');
INSERT INTO eZNews_ChangeType VALUES (6,'refuse','The item has been refused',0,'');
INSERT INTO eZNews_ChangeType VALUES (7,'retract','The item has been retracted',0,'');
INSERT INTO eZNews_ChangeType VALUES (8,'translate','The item has been translated',0,'');
INSERT INTO eZNews_ChangeType VALUES (9,'update','The item has been updated',0,'');
INSERT INTO eZNews_ChangeType VALUES (10,'copy','The item has been copied',0,'');
INSERT INTO eZNews_ChangeType VALUES (11,'temporary','The item is a temporary item',0,'');
INSERT INTO eZNews_ChangeType VALUES (12,'administrate','The item is an administrative item',0,'');

#
# Table structure for table 'eZNews_Hiearchy'
#
DROP TABLE IF EXISTS eZNews_Hiearchy;
CREATE TABLE eZNews_Hiearchy (
  ItemID int(11) DEFAULT '0' NOT NULL,
  ParentID int(11) DEFAULT '0' NOT NULL,
  isCanonical enum('Y','N') DEFAULT 'N' NOT NULL,
  PRIMARY KEY (ItemID,ParentID)
);

#
# Dumping data for table 'eZNews_Hiearchy'
#

INSERT INTO eZNews_Hiearchy VALUES (1,0,'Y');
INSERT INTO eZNews_Hiearchy VALUES (2,1,'Y');
INSERT INTO eZNews_Hiearchy VALUES (3,2,'Y');
INSERT INTO eZNews_Hiearchy VALUES (4,2,'Y');
INSERT INTO eZNews_Hiearchy VALUES (5,2,'Y');
INSERT INTO eZNews_Hiearchy VALUES (6,2,'Y');
INSERT INTO eZNews_Hiearchy VALUES (7,2,'Y');
INSERT INTO eZNews_Hiearchy VALUES (8,2,'Y');
INSERT INTO eZNews_Hiearchy VALUES (9,2,'Y');
INSERT INTO eZNews_Hiearchy VALUES (10,2,'Y');
INSERT INTO eZNews_Hiearchy VALUES (11,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (12,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (13,9,'Y');
INSERT INTO eZNews_Hiearchy VALUES (14,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (15,8,'Y');
INSERT INTO eZNews_Hiearchy VALUES (17,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (18,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (19,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (20,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (21,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (22,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (23,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (27,3,'N');
INSERT INTO eZNews_Hiearchy VALUES (29,3,'Y');
INSERT INTO eZNews_Hiearchy VALUES (31,8,'Y');
INSERT INTO eZNews_Hiearchy VALUES (32,8,'N');
INSERT INTO eZNews_Hiearchy VALUES (35,8,'Y');
INSERT INTO eZNews_Hiearchy VALUES (36,8,'Y');
INSERT INTO eZNews_Hiearchy VALUES (37,8,'Y');
INSERT INTO eZNews_Hiearchy VALUES (38,8,'Y');
INSERT INTO eZNews_Hiearchy VALUES (39,8,'Y');

#
# Table structure for table 'eZNews_Item'
#
DROP TABLE IF EXISTS eZNews_Item;
CREATE TABLE eZNews_Item (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ItemTypeID int(11) DEFAULT '0' NOT NULL,
  Name varchar(255) DEFAULT '' NOT NULL,
  CreatedAt timestamp(14),
  CreatedBy int(11) DEFAULT '1' NOT NULL,
  CreationIP varchar(50),
  Views int(11) DEFAULT '0' NOT NULL,
  Status int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID),
  KEY ItemTypeID (ItemTypeID),
  KEY Name (Name)
);

#
# Dumping data for table 'eZNews_Item'
#

INSERT INTO eZNews_Item VALUES (1,4,'Root',20001016173946,1,'local',0,2);
INSERT INTO eZNews_Item VALUES (2,8,'Heistad Hagesenter',20001016173946,1,'local',0,5);
INSERT INTO eZNews_Item VALUES (3,8,'Blomster',20001016173946,1,'10.0.2.16/2370',0,5);
INSERT INTO eZNews_Item VALUES (4,8,'Hagesenter',20001016173946,1,'local',0,5);
INSERT INTO eZNews_Item VALUES (5,8,'Hageartikler',20001016173946,1,'local',0,5);
INSERT INTO eZNews_Item VALUES (6,8,'Buketter',20001016173946,1,'local',0,5);
INSERT INTO eZNews_Item VALUES (7,8,'Planter',20001016173946,1,'local',0,5);
INSERT INTO eZNews_Item VALUES (8,8,'Begravelse',20001016173946,1,'10.0.2.150/3167',0,5);
INSERT INTO eZNews_Item VALUES (9,8,'Euro3Plast',20001016173946,1,'local',0,5);
INSERT INTO eZNews_Item VALUES (10,8,'Hundehus',20001016173946,1,'local',0,5);
INSERT INTO eZNews_Item VALUES (11,9,'',20001016153958,6,'10.0.2.16/2197',0,11);
INSERT INTO eZNews_Item VALUES (12,9,'',20001016154051,6,'10.0.2.16/2202',0,11);
INSERT INTO eZNews_Item VALUES (13,9,'',20001016154102,3,'10.0.2.150/2753',0,11);
INSERT INTO eZNews_Item VALUES (14,9,'',20001016154109,6,'10.0.2.16/2204',0,11);
INSERT INTO eZNews_Item VALUES (15,9,'',20001016154137,3,'10.0.2.150/2755',0,11);
INSERT INTO eZNews_Item VALUES (16,9,'no-name',20001016154151,6,'10.0.2.16/2216',0,1);
INSERT INTO eZNews_Item VALUES (17,9,'',20001016154232,6,'10.0.2.16/2217',0,11);
INSERT INTO eZNews_Item VALUES (18,9,'',20001016154400,6,'10.0.2.16/2221',0,11);
INSERT INTO eZNews_Item VALUES (19,9,'',20001016154556,6,'10.0.2.16/2222',0,11);
INSERT INTO eZNews_Item VALUES (20,9,'',20001016154628,6,'10.0.2.16/2227',0,11);
INSERT INTO eZNews_Item VALUES (21,9,'',20001016160151,6,'10.0.2.16/2312',0,11);
INSERT INTO eZNews_Item VALUES (22,9,'',20001016160226,6,'10.0.2.16/2317',0,11);
INSERT INTO eZNews_Item VALUES (23,9,'',20001016160307,6,'10.0.2.16/2322',0,11);
INSERT INTO eZNews_Item VALUES (24,9,'no-name',20001016161231,6,'10.0.2.16/2343',0,1);
INSERT INTO eZNews_Item VALUES (25,9,'no-name',20001016162752,6,'10.0.2.16/2347',0,1);
INSERT INTO eZNews_Item VALUES (26,9,'no-name',20001016163421,6,'10.0.2.16/2357',0,1);
INSERT INTO eZNews_Item VALUES (27,5,'Public description for Blomster',20001016163646,6,'10.0.2.16/2370',0,0);
INSERT INTO eZNews_Item VALUES (28,9,'no-name',20001016163704,6,'10.0.2.16/2373',0,1);
INSERT INTO eZNews_Item VALUES (29,9,'fdsaf',20001016163732,6,'10.0.2.16/2375',0,11);
INSERT INTO eZNews_Item VALUES (30,9,'no-name',20001016164316,3,'10.0.2.150/3167',0,1);
INSERT INTO eZNews_Item VALUES (31,9,'53563',20001016164344,3,'10.0.2.150/3167',0,11);
INSERT INTO eZNews_Item VALUES (32,5,'Public description for Begravelse',20001016164417,3,'10.0.2.150/3167',0,0);
INSERT INTO eZNews_Item VALUES (33,9,'no-name',20001017164057,25,'10.0.2.3/60544',0,1);
INSERT INTO eZNews_Item VALUES (34,9,'no-name',20001017165503,25,'10.0.2.3/33591',0,1);
INSERT INTO eZNews_Item VALUES (35,9,'setset',20001017165529,25,'10.0.2.3/60649',0,5);
INSERT INTO eZNews_Item VALUES (36,9,'asdfasdf',20001017165554,25,'10.0.2.3/60649',0,5);
INSERT INTO eZNews_Item VALUES (37,9,'wetwe',20001017165657,25,'10.0.2.3/60652',0,5);
INSERT INTO eZNews_Item VALUES (38,9,'wer',20001017165725,25,'10.0.2.3/60652',0,5);
INSERT INTO eZNews_Item VALUES (39,9,'34534',20001017165751,25,'10.0.2.3/60652',0,5);

#
# Table structure for table 'eZNews_ItemFile'
#
DROP TABLE IF EXISTS eZNews_ItemFile;
CREATE TABLE eZNews_ItemFile (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ItemID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ItemID,FileID),
  KEY ID (ID)
);

#
# Dumping data for table 'eZNews_ItemFile'
#


#
# Table structure for table 'eZNews_ItemImage'
#
DROP TABLE IF EXISTS eZNews_ItemImage;
CREATE TABLE eZNews_ItemImage (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ItemID int(11) DEFAULT '0' NOT NULL,
  ImageID int(11) DEFAULT '0' NOT NULL,
  isFrontImage enum('Y','N') DEFAULT 'N' NOT NULL,
  ImageWidth int(11) DEFAULT '0',
  ImageHeight int(11) DEFAULT '0',
  ThumbImageWidth int(11) DEFAULT '0',
  ThumbImageHeight int(11) DEFAULT '0',
  PRIMARY KEY (ItemID,ImageID),
  KEY ID (ID)
);

#
# Dumping data for table 'eZNews_ItemImage'
#

INSERT INTO eZNews_ItemImage VALUES (1,29,197,'Y',0,0,0,0);

#
# Table structure for table 'eZNews_ItemLog'
#
DROP TABLE IF EXISTS eZNews_ItemLog;
CREATE TABLE eZNews_ItemLog (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ItemID int(11) DEFAULT '0' NOT NULL,
  ChangeTicketID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ItemID,ChangeTicketID),
  KEY ID (ID)
);

#
# Dumping data for table 'eZNews_ItemLog'
#

INSERT INTO eZNews_ItemLog VALUES (1,11,1);
INSERT INTO eZNews_ItemLog VALUES (2,11,2);
INSERT INTO eZNews_ItemLog VALUES (3,11,3);
INSERT INTO eZNews_ItemLog VALUES (4,12,4);
INSERT INTO eZNews_ItemLog VALUES (5,12,5);
INSERT INTO eZNews_ItemLog VALUES (6,12,6);
INSERT INTO eZNews_ItemLog VALUES (7,13,7);
INSERT INTO eZNews_ItemLog VALUES (8,13,8);
INSERT INTO eZNews_ItemLog VALUES (9,13,9);
INSERT INTO eZNews_ItemLog VALUES (10,14,10);
INSERT INTO eZNews_ItemLog VALUES (11,14,11);
INSERT INTO eZNews_ItemLog VALUES (12,14,12);
INSERT INTO eZNews_ItemLog VALUES (13,15,13);
INSERT INTO eZNews_ItemLog VALUES (14,15,14);
INSERT INTO eZNews_ItemLog VALUES (15,15,15);
INSERT INTO eZNews_ItemLog VALUES (18,16,22);
INSERT INTO eZNews_ItemLog VALUES (17,16,21);
INSERT INTO eZNews_ItemLog VALUES (16,16,20);
INSERT INTO eZNews_ItemLog VALUES (19,17,23);
INSERT INTO eZNews_ItemLog VALUES (20,17,24);
INSERT INTO eZNews_ItemLog VALUES (21,17,25);
INSERT INTO eZNews_ItemLog VALUES (22,18,26);
INSERT INTO eZNews_ItemLog VALUES (23,18,27);
INSERT INTO eZNews_ItemLog VALUES (24,18,28);
INSERT INTO eZNews_ItemLog VALUES (25,19,29);
INSERT INTO eZNews_ItemLog VALUES (26,19,30);
INSERT INTO eZNews_ItemLog VALUES (27,19,31);
INSERT INTO eZNews_ItemLog VALUES (28,20,32);
INSERT INTO eZNews_ItemLog VALUES (29,20,33);
INSERT INTO eZNews_ItemLog VALUES (30,20,34);
INSERT INTO eZNews_ItemLog VALUES (31,21,35);
INSERT INTO eZNews_ItemLog VALUES (32,21,36);
INSERT INTO eZNews_ItemLog VALUES (33,21,37);
INSERT INTO eZNews_ItemLog VALUES (34,22,38);
INSERT INTO eZNews_ItemLog VALUES (35,22,39);
INSERT INTO eZNews_ItemLog VALUES (36,22,40);
INSERT INTO eZNews_ItemLog VALUES (37,23,41);
INSERT INTO eZNews_ItemLog VALUES (38,23,42);
INSERT INTO eZNews_ItemLog VALUES (39,23,43);
INSERT INTO eZNews_ItemLog VALUES (42,24,49);
INSERT INTO eZNews_ItemLog VALUES (41,24,48);
INSERT INTO eZNews_ItemLog VALUES (40,24,47);
INSERT INTO eZNews_ItemLog VALUES (45,25,56);
INSERT INTO eZNews_ItemLog VALUES (44,25,55);
INSERT INTO eZNews_ItemLog VALUES (43,25,54);
INSERT INTO eZNews_ItemLog VALUES (48,26,62);
INSERT INTO eZNews_ItemLog VALUES (47,26,61);
INSERT INTO eZNews_ItemLog VALUES (46,26,60);
INSERT INTO eZNews_ItemLog VALUES (49,3,63);
INSERT INTO eZNews_ItemLog VALUES (50,3,64);
INSERT INTO eZNews_ItemLog VALUES (53,28,70);
INSERT INTO eZNews_ItemLog VALUES (52,28,69);
INSERT INTO eZNews_ItemLog VALUES (51,28,68);
INSERT INTO eZNews_ItemLog VALUES (56,29,76);
INSERT INTO eZNews_ItemLog VALUES (55,29,75);
INSERT INTO eZNews_ItemLog VALUES (54,29,74);
INSERT INTO eZNews_ItemLog VALUES (57,29,77);
INSERT INTO eZNews_ItemLog VALUES (60,30,83);
INSERT INTO eZNews_ItemLog VALUES (59,30,82);
INSERT INTO eZNews_ItemLog VALUES (58,30,81);
INSERT INTO eZNews_ItemLog VALUES (63,31,89);
INSERT INTO eZNews_ItemLog VALUES (62,31,88);
INSERT INTO eZNews_ItemLog VALUES (61,31,87);
INSERT INTO eZNews_ItemLog VALUES (64,8,90);
INSERT INTO eZNews_ItemLog VALUES (65,8,91);
INSERT INTO eZNews_ItemLog VALUES (67,33,101);
INSERT INTO eZNews_ItemLog VALUES (66,33,100);
INSERT INTO eZNews_ItemLog VALUES (68,33,102);
INSERT INTO eZNews_ItemLog VALUES (81,34,153);
INSERT INTO eZNews_ItemLog VALUES (82,34,154);
INSERT INTO eZNews_ItemLog VALUES (71,35,117);
INSERT INTO eZNews_ItemLog VALUES (72,35,118);
INSERT INTO eZNews_ItemLog VALUES (73,36,125);
INSERT INTO eZNews_ItemLog VALUES (74,36,126);
INSERT INTO eZNews_ItemLog VALUES (75,37,133);
INSERT INTO eZNews_ItemLog VALUES (76,37,134);
INSERT INTO eZNews_ItemLog VALUES (77,38,141);
INSERT INTO eZNews_ItemLog VALUES (78,38,142);
INSERT INTO eZNews_ItemLog VALUES (79,39,149);
INSERT INTO eZNews_ItemLog VALUES (80,39,150);
INSERT INTO eZNews_ItemLog VALUES (83,34,155);

#
# Table structure for table 'eZNews_ItemPosition'
#
DROP TABLE IF EXISTS eZNews_ItemPosition;
CREATE TABLE eZNews_ItemPosition (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  ItemID int(11) DEFAULT '0' NOT NULL,
  Position int(11) DEFAULT '0' NOT NULL,
  Value int(11) DEFAULT '1' NOT NULL,
  DevaluationDateA timestamp(14),
  DevaluationDateB timestamp(14),
  DevaluationDateC timestamp(14),
  PRIMARY KEY (ID),
  UNIQUE CategoryID (CategoryID,ItemID),
  UNIQUE CategoryID_2 (CategoryID,Position)
);

#
# Dumping data for table 'eZNews_ItemPosition'
#


#
# Table structure for table 'eZNews_ItemType'
#
DROP TABLE IF EXISTS eZNews_ItemType;
CREATE TABLE eZNews_ItemType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ParentID int(11) DEFAULT '0' NOT NULL,
  Name varchar(255) DEFAULT '' NOT NULL,
  eZClass varchar(255) DEFAULT '' NOT NULL,
  eZTable varchar(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID),
  KEY ParentID (ParentID),
  UNIQUE Name (Name),
  KEY eZClass (eZClass),
  KEY eZTable (eZTable)
);

#
# Dumping data for table 'eZNews_ItemType'
#

INSERT INTO eZNews_ItemType VALUES (1,0,'undefined','','');
INSERT INTO eZNews_ItemType VALUES (2,0,'news','','');
INSERT INTO eZNews_ItemType VALUES (3,0,'flower','','');
INSERT INTO eZNews_ItemType VALUES (4,2,'category','eZNewsCategory','eZNews_Category');
INSERT INTO eZNews_ItemType VALUES (5,2,'article','eZNewsArticle','eZNews_Article');
INSERT INTO eZNews_ItemType VALUES (6,4,'product','eZNewsArticleProduct','');
INSERT INTO eZNews_ItemType VALUES (7,4,'nitf','eZNewsArticleNITF','eZNews_ArticleNITF');
INSERT INTO eZNews_ItemType VALUES (8,3,'flowercategory','eZNewsFlowerCategory','eZNews_Category');
INSERT INTO eZNews_ItemType VALUES (9,3,'flowerarticle','eZNewsFlowerArticle','eZNews_Article');
INSERT INTO eZNews_ItemType VALUES (11,0,'faq','','');
INSERT INTO eZNews_ItemType VALUES (12,11,'question','eZfaqquestion','eZfaq_question');
INSERT INTO eZNews_ItemType VALUES (13,11,'answer','eZfaqanswer','eZfaq_answer');
INSERT INTO eZNews_ItemType VALUES (14,0,'diary','','');
INSERT INTO eZNews_ItemType VALUES (15,14,'entry','eZdiaryentry','eZdiary_entry');

#
# Table structure for table 'eZPoll_MainPoll'
#
DROP TABLE IF EXISTS eZPoll_MainPoll;
CREATE TABLE eZPoll_MainPoll (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PollID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZPoll_MainPoll'
#

INSERT INTO eZPoll_MainPoll VALUES (1,14);

#
# Table structure for table 'eZPoll_Poll'
#
DROP TABLE IF EXISTS eZPoll_Poll;
CREATE TABLE eZPoll_Poll (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  Percent enum('true','false'),
  Number enum('true','false'),
  Anonymous enum('true','false'),
  IsEnabled enum('true','false') DEFAULT 'false',
  IsClosed enum('true','false') DEFAULT 'false',
  ShowResult enum('true','false') DEFAULT 'false',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZPoll_Poll'
#

INSERT INTO eZPoll_Poll VALUES (7,'Beste OS','Hvilket OS syntes du er best?',NULL,NULL,NULL,'true','false','true');
INSERT INTO eZPoll_Poll VALUES (8,'Favoritt editor','Hvilken editor syntes du er den beste?',NULL,NULL,NULL,'false','true','true');
INSERT INTO eZPoll_Poll VALUES (9,'Testpoll','Test!!!',NULL,NULL,NULL,'true','true','false');
INSERT INTO eZPoll_Poll VALUES (15,'test poll','213123123',NULL,NULL,NULL,'false','false','false');
INSERT INTO eZPoll_Poll VALUES (13,'tesst','Ingen beskrivelse.',NULL,NULL,NULL,'false','false','false');
INSERT INTO eZPoll_Poll VALUES (14,'Hva liker du best?','Ingen beskrivelse.',NULL,NULL,NULL,'true','false','true');

#
# Table structure for table 'eZPoll_PollChoice'
#
DROP TABLE IF EXISTS eZPoll_PollChoice;
CREATE TABLE eZPoll_PollChoice (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PollID int(11),
  Name char(100),
  Offset int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZPoll_PollChoice'
#

INSERT INTO eZPoll_PollChoice VALUES (20,7,'Windows 98',0);
INSERT INTO eZPoll_PollChoice VALUES (19,7,'Windows 95',0);
INSERT INTO eZPoll_PollChoice VALUES (18,7,'Dos',0);
INSERT INTO eZPoll_PollChoice VALUES (26,8,'Joe',0);
INSERT INTO eZPoll_PollChoice VALUES (27,8,'Emacs',0);
INSERT INTO eZPoll_PollChoice VALUES (25,7,'BSD',0);
INSERT INTO eZPoll_PollChoice VALUES (28,8,'VI / VIM',0);
INSERT INTO eZPoll_PollChoice VALUES (31,8,'Nedit',0);
INSERT INTO eZPoll_PollChoice VALUES (30,8,'Pico',0);
INSERT INTO eZPoll_PollChoice VALUES (29,8,'Edit',0);
INSERT INTO eZPoll_PollChoice VALUES (17,7,'RedHat Linux',0);
INSERT INTO eZPoll_PollChoice VALUES (24,7,'IRIX',0);
INSERT INTO eZPoll_PollChoice VALUES (23,7,'Solaris',0);
INSERT INTO eZPoll_PollChoice VALUES (22,7,'BeOS',0);
INSERT INTO eZPoll_PollChoice VALUES (21,7,'Amiga OS',666);
INSERT INTO eZPoll_PollChoice VALUES (32,8,'Nano',0);
INSERT INTO eZPoll_PollChoice VALUES (33,9,'Testalternativ1',0);
INSERT INTO eZPoll_PollChoice VALUES (34,9,'Testalternativ2',0);
INSERT INTO eZPoll_PollChoice VALUES (38,14,'Lingvistikk',0);
INSERT INTO eZPoll_PollChoice VALUES (37,14,'Geologi',0);
INSERT INTO eZPoll_PollChoice VALUES (36,14,'Geografi',0);
INSERT INTO eZPoll_PollChoice VALUES (35,14,'Filosofi',0);
INSERT INTO eZPoll_PollChoice VALUES (39,15,'321',100);
INSERT INTO eZPoll_PollChoice VALUES (40,15,'321123321',2147483647);

#
# Table structure for table 'eZPoll_Vote'
#
DROP TABLE IF EXISTS eZPoll_Vote;
CREATE TABLE eZPoll_Vote (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PollID int(11),
  ChoiceID int(11),
  VotingIP char(20),
  UserID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZPoll_Vote'
#

INSERT INTO eZPoll_Vote VALUES (1,7,18,'10.0.2.9',1);
INSERT INTO eZPoll_Vote VALUES (2,7,20,'10.0.2.9',3);
INSERT INTO eZPoll_Vote VALUES (3,8,27,'10.0.2.9',3);
INSERT INTO eZPoll_Vote VALUES (4,8,0,'10.0.2.9',1);
INSERT INTO eZPoll_Vote VALUES (5,7,18,'10.0.2.9',2);
INSERT INTO eZPoll_Vote VALUES (6,7,18,'10.0.2.3',25);
INSERT INTO eZPoll_Vote VALUES (7,14,37,'10.0.2.3',25);
INSERT INTO eZPoll_Vote VALUES (8,14,35,'10.0.2.9',2);

#
# Table structure for table 'eZSession_Session'
#
DROP TABLE IF EXISTS eZSession_Session;
CREATE TABLE eZSession_Session (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Hash char(33),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZSession_Session'
#

INSERT INTO eZSession_Session VALUES (1,'570cf3b1469f51a801eae563a271d808');
INSERT INTO eZSession_Session VALUES (2,'b06203dc4c3b53b4d3532ee42f8b3566');

#
# Table structure for table 'eZSession_SessionVariable'
#
DROP TABLE IF EXISTS eZSession_SessionVariable;
CREATE TABLE eZSession_SessionVariable (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  SessionID int(11),
  Name char(25),
  Value char(50),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZSession_SessionVariable'
#

INSERT INTO eZSession_SessionVariable VALUES (1,1,'AuthenticatedUser','');
INSERT INTO eZSession_SessionVariable VALUES (2,2,'AuthenticatedUser','1');

#
# Table structure for table 'eZTrade_Cart'
#
DROP TABLE IF EXISTS eZTrade_Cart;
CREATE TABLE eZTrade_Cart (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  SessionID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Cart'
#

INSERT INTO eZTrade_Cart VALUES (1,176);
INSERT INTO eZTrade_Cart VALUES (2,1);

#
# Table structure for table 'eZTrade_CartItem'
#
DROP TABLE IF EXISTS eZTrade_CartItem;
CREATE TABLE eZTrade_CartItem (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  Count int(11),
  CartID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_CartItem'
#

INSERT INTO eZTrade_CartItem VALUES (1,1,1,2);

#
# Table structure for table 'eZTrade_CartOptionValue'
#
DROP TABLE IF EXISTS eZTrade_CartOptionValue;
CREATE TABLE eZTrade_CartOptionValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CartItemID int(11),
  OptionID int(11),
  OptionValueID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_CartOptionValue'
#


#
# Table structure for table 'eZTrade_Category'
#
DROP TABLE IF EXISTS eZTrade_Category;
CREATE TABLE eZTrade_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11),
  Description text,
  Name varchar(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Category'
#

INSERT INTO eZTrade_Category VALUES (1,0,'','Products');

#
# Table structure for table 'eZTrade_CategoryOptionLink'
#
DROP TABLE IF EXISTS eZTrade_CategoryOptionLink;
CREATE TABLE eZTrade_CategoryOptionLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  OptionID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_CategoryOptionLink'
#


#
# Table structure for table 'eZTrade_Option'
#
DROP TABLE IF EXISTS eZTrade_Option;
CREATE TABLE eZTrade_Option (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Option'
#


#
# Table structure for table 'eZTrade_OptionValue'
#
DROP TABLE IF EXISTS eZTrade_OptionValue;
CREATE TABLE eZTrade_OptionValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(100),
  OptionID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_OptionValue'
#


#
# Table structure for table 'eZTrade_Order'
#
DROP TABLE IF EXISTS eZTrade_Order;
CREATE TABLE eZTrade_Order (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11),
  ShippingCharge float(10,2),
  PaymentMethod text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Order'
#

INSERT INTO eZTrade_Order VALUES (2,2,42,50.00,'1');

#
# Table structure for table 'eZTrade_OrderItem'
#
DROP TABLE IF EXISTS eZTrade_OrderItem;
CREATE TABLE eZTrade_OrderItem (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  OrderID int(11) DEFAULT '0' NOT NULL,
  Count int(11),
  Price float(10,2),
  ProductID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_OrderItem'
#

INSERT INTO eZTrade_OrderItem VALUES (3,2,1,42.00,1);

#
# Table structure for table 'eZTrade_OrderOptionValue'
#
DROP TABLE IF EXISTS eZTrade_OrderOptionValue;
CREATE TABLE eZTrade_OrderOptionValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  OrderItemID int(11),
  OptionName char(25),
  ValueName char(25),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_OrderOptionValue'
#


#
# Table structure for table 'eZTrade_OrderStatus'
#
DROP TABLE IF EXISTS eZTrade_OrderStatus;
CREATE TABLE eZTrade_OrderStatus (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  StatusID int(11) DEFAULT '0' NOT NULL,
  Altered timestamp(14),
  AdminID int(11),
  OrderID int(11) DEFAULT '0' NOT NULL,
  Comment text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_OrderStatus'
#

INSERT INTO eZTrade_OrderStatus VALUES (2,1,20001101191244,0,2,'');

#
# Table structure for table 'eZTrade_OrderStatusType'
#
DROP TABLE IF EXISTS eZTrade_OrderStatusType;
CREATE TABLE eZTrade_OrderStatusType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(25) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE Name (Name)
);

#
# Dumping data for table 'eZTrade_OrderStatusType'
#

INSERT INTO eZTrade_OrderStatusType VALUES (1,'intl-initial');
INSERT INTO eZTrade_OrderStatusType VALUES (2,'intl-sendt');
INSERT INTO eZTrade_OrderStatusType VALUES (3,'intl-payed');
INSERT INTO eZTrade_OrderStatusType VALUES (4,'intl-undefined');

#
# Table structure for table 'eZTrade_Product'
#
DROP TABLE IF EXISTS eZTrade_Product;
CREATE TABLE eZTrade_Product (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Brief text,
  Description text,
  Keywords varchar(100),
  Price float(10,2),
  ShowPrice enum('true','false'),
  ShowProduct enum('true','false'),
  Discontinued enum('true','false'),
  InheritOptions enum('true','false'),
  ProductNumber varchar(100),
  ExternalLink varchar(200),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Product'
#

INSERT INTO eZTrade_Product VALUES (1,'Flower','This is just a demo product... ','Here are the description of the product.','nice flower',42.00,'true','true','false','false','FLW-100','ez.no');

#
# Table structure for table 'eZTrade_ProductCategoryLink'
#
DROP TABLE IF EXISTS eZTrade_ProductCategoryLink;
CREATE TABLE eZTrade_ProductCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  ProductID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductCategoryLink'
#

INSERT INTO eZTrade_ProductCategoryLink VALUES (1,1,1);

#
# Table structure for table 'eZTrade_ProductImageDefinition'
#
DROP TABLE IF EXISTS eZTrade_ProductImageDefinition;
CREATE TABLE eZTrade_ProductImageDefinition (
  ProductID int(11) DEFAULT '0' NOT NULL,
  ThumbnailImageID int(11),
  MainImageID int(11),
  PRIMARY KEY (ProductID)
);

#
# Dumping data for table 'eZTrade_ProductImageDefinition'
#

INSERT INTO eZTrade_ProductImageDefinition VALUES (1,2,2);

#
# Table structure for table 'eZTrade_ProductImageLink'
#
DROP TABLE IF EXISTS eZTrade_ProductImageLink;
CREATE TABLE eZTrade_ProductImageLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  ImageID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductImageLink'
#

INSERT INTO eZTrade_ProductImageLink VALUES (1,1,2);

#
# Table structure for table 'eZTrade_ProductOptionLink'
#
DROP TABLE IF EXISTS eZTrade_ProductOptionLink;
CREATE TABLE eZTrade_ProductOptionLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  OptionID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductOptionLink'
#


#
# Table structure for table 'eZTrade_WishList'
#
DROP TABLE IF EXISTS eZTrade_WishList;
CREATE TABLE eZTrade_WishList (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_WishList'
#

INSERT INTO eZTrade_WishList VALUES (1,0);
INSERT INTO eZTrade_WishList VALUES (2,0);
INSERT INTO eZTrade_WishList VALUES (3,0);
INSERT INTO eZTrade_WishList VALUES (4,0);
INSERT INTO eZTrade_WishList VALUES (5,2);
INSERT INTO eZTrade_WishList VALUES (6,0);
INSERT INTO eZTrade_WishList VALUES (7,1);

#
# Table structure for table 'eZTrade_WishListItem'
#
DROP TABLE IF EXISTS eZTrade_WishListItem;
CREATE TABLE eZTrade_WishListItem (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  Count int(11),
  WishListID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_WishListItem'
#

INSERT INTO eZTrade_WishListItem VALUES (1,1,1,1);
INSERT INTO eZTrade_WishListItem VALUES (2,1,1,3);
INSERT INTO eZTrade_WishListItem VALUES (3,1,1,5);

#
# Table structure for table 'eZTrade_WishListOptionValue'
#
DROP TABLE IF EXISTS eZTrade_WishListOptionValue;
CREATE TABLE eZTrade_WishListOptionValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  WishListItemID int(11),
  OptionID int(11),
  OptionValueID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_WishListOptionValue'
#


#
# Table structure for table 'eZUser_Group'
#
DROP TABLE IF EXISTS eZUser_Group;
CREATE TABLE eZUser_Group (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_Group'
#

INSERT INTO eZUser_Group VALUES (2,'Anonyme brukere','Brukere som har opprettet seg selv, eks shopping brukere.');
INSERT INTO eZUser_Group VALUES (1,'Administrasjon','Alle rettigheter');

#
# Table structure for table 'eZUser_GroupPermissionLink'
#
DROP TABLE IF EXISTS eZUser_GroupPermissionLink;
CREATE TABLE eZUser_GroupPermissionLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  GroupID int(11),
  PermissionID int(11),
  IsEnabled enum('true','false'),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_GroupPermissionLink'
#

INSERT INTO eZUser_GroupPermissionLink VALUES (1,1,1,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (2,1,2,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (3,1,3,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (4,1,4,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (5,1,5,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (6,1,6,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (7,1,8,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (8,2,1,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (9,2,2,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (10,2,3,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (11,2,4,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (12,2,5,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (13,2,6,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (14,2,8,'false');
INSERT INTO eZUser_GroupPermissionLink VALUES (21,1,15,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (20,1,14,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (19,1,13,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (18,1,12,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (17,1,11,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (16,1,10,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (15,1,9,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (22,1,16,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (23,1,17,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (24,1,18,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (25,1,19,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (26,1,20,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (27,1,21,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (28,1,22,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (29,1,23,'true');

#
# Table structure for table 'eZUser_Module'
#
DROP TABLE IF EXISTS eZUser_Module;
CREATE TABLE eZUser_Module (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(100) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE Name (Name)
);

#
# Dumping data for table 'eZUser_Module'
#

INSERT INTO eZUser_Module VALUES (1,'eZTrade');
INSERT INTO eZUser_Module VALUES (2,'eZPoll');
INSERT INTO eZUser_Module VALUES (3,'eZUser');
INSERT INTO eZUser_Module VALUES (4,'eZTodo');
INSERT INTO eZUser_Module VALUES (5,'eZNews');
INSERT INTO eZUser_Module VALUES (6,'eZContact');
INSERT INTO eZUser_Module VALUES (7,'eZForum');
INSERT INTO eZUser_Module VALUES (8,'eZLink');

#
# Table structure for table 'eZUser_Permission'
#
DROP TABLE IF EXISTS eZUser_Permission;
CREATE TABLE eZUser_Permission (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ModuleID int(11),
  Name char(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_Permission'
#

INSERT INTO eZUser_Permission VALUES (1,3,'UserAdd');
INSERT INTO eZUser_Permission VALUES (2,3,'UserDelete');
INSERT INTO eZUser_Permission VALUES (3,3,'UserModify');
INSERT INTO eZUser_Permission VALUES (4,3,'GroupDelete');
INSERT INTO eZUser_Permission VALUES (5,3,'GroupAdd');
INSERT INTO eZUser_Permission VALUES (6,3,'GroupModify');
INSERT INTO eZUser_Permission VALUES (11,8,'LinkGroupModify');
INSERT INTO eZUser_Permission VALUES (8,3,'AdminLogin');
INSERT INTO eZUser_Permission VALUES (10,8,'LinkGroupAdd');
INSERT INTO eZUser_Permission VALUES (9,8,'LinkGroupDelete');
INSERT INTO eZUser_Permission VALUES (12,8,'LinkModify');
INSERT INTO eZUser_Permission VALUES (13,8,'LinkAdd');
INSERT INTO eZUser_Permission VALUES (14,8,'LinkDelete');
INSERT INTO eZUser_Permission VALUES (15,7,'CategoryAdd');
INSERT INTO eZUser_Permission VALUES (16,7,'CategoryModify');
INSERT INTO eZUser_Permission VALUES (17,7,'CategoryDelete');
INSERT INTO eZUser_Permission VALUES (18,7,'ForumDelete');
INSERT INTO eZUser_Permission VALUES (19,7,'ForumAdd');
INSERT INTO eZUser_Permission VALUES (20,7,'ForumModify');
INSERT INTO eZUser_Permission VALUES (21,7,'MessageModify');
INSERT INTO eZUser_Permission VALUES (22,7,'MessageAdd');
INSERT INTO eZUser_Permission VALUES (23,7,'MessageDelete');

#
# Table structure for table 'eZUser_User'
#
DROP TABLE IF EXISTS eZUser_User;
CREATE TABLE eZUser_User (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Login char(50) DEFAULT '' NOT NULL,
  Password char(50) DEFAULT '' NOT NULL,
  Email char(50),
  FirstName char(50),
  LastName char(50),
  InfoSubscription enum('true','false') DEFAULT 'false',
  PRIMARY KEY (ID),
  UNIQUE Login (Login)
);

#
# Dumping data for table 'eZUser_User'
#

INSERT INTO eZUser_User VALUES (1,'admin','3d14f82a5508f09f','ce@ez.no','Admin','User','false');
INSERT INTO eZUser_User VALUES (2,'bf','709de31a71b8c5fd','bf@ez.no','Bård','Farstad','');

#
# Table structure for table 'eZUser_UserAddressLink'
#
DROP TABLE IF EXISTS eZUser_UserAddressLink;
CREATE TABLE eZUser_UserAddressLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_UserAddressLink'
#

INSERT INTO eZUser_UserAddressLink VALUES (1,2,1);

#
# Table structure for table 'eZUser_UserGroupLink'
#
DROP TABLE IF EXISTS eZUser_UserGroupLink;
CREATE TABLE eZUser_UserGroupLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  GroupID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_UserGroupLink'
#

INSERT INTO eZUser_UserGroupLink VALUES (1,1,1);
INSERT INTO eZUser_UserGroupLink VALUES (2,2,2);

