# MySQL dump 7.1
#
# Host: localhost    Database: publish
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'eZArticle_Article'
#
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

INSERT INTO eZArticle_Article VALUES (1,'Demo article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This is a demo article. It will demontrate the power of the eZTechRenderer used for generating articles.</intro><body><page><header>Here I will demonstrate some simple tags</header>\r\n\r\n<bold> this is bold text</bold> \r\n<italic>this is italic text</italic>\r\n<strike>this is strike through text</strike>\r\n\r\n<link href=\"ez.no\" text=\"this is a link\" />\r\n<mail to=\"bf@ez.no\" subject=\"demo\" text=\"mail me\" /> a mail link with subject set to demo\r\n\r\n</page><page>\r\n\r\n<header>Here I will demonstrate images</header>\r\n\r\nAs you see the images are generated on the fly, so you can request any size (small, medium, large) at any time.\r\n\r\n<image id=\"1\" align=\"left\" size=\"small\" /> This is a small image. Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla .\r\n\r\nbla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla .\r\n\r\n<header>A large image:</header>\r\n\r\n<image id=\"1\" align=\"center\" size=\"large\" /> \r\n\r\n</page><page>\r\n\r\n<header>Coding tags</header>\r\n\r\nHere I will demonstrate som programming tags.\r\n\r\n<php>\r\n// this is php code\r\nfunction foo()\r\n{\r\n  bar();\r\n}\r\n</php>\r\n\r\nAnd some cpp:\r\n<cpp>\r\nclass foo\r\n{\r\n  foo();\r\n  void bar();\r\n}\r\n</cpp>\r\n\r\nAnd \r\n<ezhtml>\r\n&lt;html&gt;\r\n&lt;head&gt;\r\n  &lt;title&gt;\r\n  Title\r\n  &lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\nthis is the body\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n</ezhtml>\r\n\r\n</page></body></article>','Bård Farstad','read',27,20001101123424,20001101122842,3,'true',20001101122842,'tech\nThis is a demo article. It will demontrate the power of eZTechRenderer used for generating articles.Here I demonstrate some simple tags\r\n\r\n this bold text \r\nthis italic text\r\nthis strike through text\r\n\r\n\r\n mail link with subject set to demo\r\n\r\n\r\n\r\nHere images\r\n\r\nAs you see images are generated on fly, so can request any size (small, medium, large) at time.\r\n\r\n This small image. Bla bla .\r\n\r\nbla .\r\n\r\nA large image:\r\n\r\n \r\n\r\n\r\n\r\nCoding tags\r\n\r\nHere som programming tags.\r\n\r\n\r\n// php code\r\nfunction foo()\r\n{\r\n  bar();\r\n}\r\n\r\n\r\nAnd cpp:\r\n\r\nclass foo\r\n{\r\n foo();\r\n void \r\n\r\n&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;\r\n Title\r\n &lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\nthis body\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n\r\n\r\n ');

#
# Table structure for table 'eZArticle_ArticleCategoryLink'
#
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

INSERT INTO eZArticle_ArticleImageLink VALUES (1,1,1,20001101122932);

#
# Table structure for table 'eZArticle_Category'
#
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

INSERT INTO eZArticle_Category VALUES (1,'News','Here are some news, fresh from the press.',0,'false');

#
# Table structure for table 'eZContact_Address'
#
CREATE TABLE eZContact_Address (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Street1 char(50),
  Street2 char(50),
  AddressType int(11),
  Place char(50),
  Zip char(10),
  CompanyID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZContact_Address'
#


#
# Table structure for table 'eZContact_Country'
#
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
# Table structure for table 'eZForum_Category'
#
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

INSERT INTO eZForum_Category VALUES ('Talk center','General talk','N',1);

#
# Table structure for table 'eZForum_Forum'
#
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

INSERT INTO eZForum_Forum VALUES (1,'Discussion','Discuss everything here','','',1);
INSERT INTO eZForum_Forum VALUES (1,'Special talk','Talk about something else here','','',2);

#
# Table structure for table 'eZForum_Message'
#
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

INSERT INTO eZForum_Message VALUES (1,'First post!','This is the first post!',27,0,'N',20001101195844,1,0,0,1);
INSERT INTO eZForum_Message VALUES (1,'SV: First post!','> This is the first post!\r\nThis is the reply!',27,1,'N',20001101195844,0,0,1,2);
INSERT INTO eZForum_Message VALUES (2,'First post!','YES!',27,0,'N',20001101200642,3,1,0,3);
INSERT INTO eZForum_Message VALUES (2,'RE: First post!','> YES!\r\nhmm',27,3,'N',20001101200642,2,1,1,4);

#
# Table structure for table 'eZImageCatalogue_Image'
#
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

INSERT INTO eZImageCatalogue_Image VALUES (1,'','caption text','','php0meQsj.jpg','360_rose.jpg');

#
# Table structure for table 'eZImageCatalogue_ImageVariation'
#
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

INSERT INTO eZImageCatalogue_ImageVariation VALUES (1,1,1,'ezimagecatalogue/catalogue/variations/1-150x150.jpg',150,83);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (2,1,2,'ezimagecatalogue/catalogue/variations/1-100x100.jpg',100,56);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (3,1,3,'ezimagecatalogue/catalogue/variations/1-300x300.jpg',270,150);

#
# Table structure for table 'eZImageCatalogue_ImageVariationGroup'
#
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

#
# Table structure for table 'eZLink_Hit'
#
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

INSERT INTO eZLink_Hit VALUES (1,1,20001101194225,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (2,1,20001101194230,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (3,1,20001101194347,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (4,1,20001101194442,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (5,1,20001101201636,'10.0.2.3');

#
# Table structure for table 'eZLink_Link'
#
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

INSERT INTO eZLink_Link VALUES (1,'eZ systems as','Linux and open source solutions made easy.',1,'linux open source',20001101115659,'Y','2000-11-01 11:56:59','ez.no');

#
# Table structure for table 'eZLink_LinkGroup'
#
CREATE TABLE eZLink_LinkGroup (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11) DEFAULT '0',
  Title char(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_LinkGroup'
#

INSERT INTO eZLink_LinkGroup VALUES (1,0,'Cool links');
INSERT INTO eZLink_LinkGroup VALUES (2,0,'Not so cool links');

#
# Table structure for table 'eZPoll_MainPoll'
#
CREATE TABLE eZPoll_MainPoll (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PollID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZPoll_MainPoll'
#

INSERT INTO eZPoll_MainPoll VALUES (1,1);

#
# Table structure for table 'eZPoll_Poll'
#
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

INSERT INTO eZPoll_Poll VALUES (1,'First poll','this is a demo poll',NULL,NULL,'true','true','false','true');

#
# Table structure for table 'eZPoll_PollChoice'
#
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

INSERT INTO eZPoll_PollChoice VALUES (2,1,'Bar',0);
INSERT INTO eZPoll_PollChoice VALUES (1,1,'FOo',0);

#
# Table structure for table 'eZPoll_Vote'
#
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


#
# Table structure for table 'eZSession_Session'
#
CREATE TABLE eZSession_Session (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Hash char(33),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZSession_Session'
#

#
# Table structure for table 'eZSession_SessionVariable'
#
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

#
# Table structure for table 'eZUser_Forgot'
#
CREATE TABLE eZUser_Forgot (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  Hash char(33),
  Time timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_Forgot'
#


#
# Table structure for table 'eZUser_Group'
#
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

INSERT INTO eZUser_User VALUES (27,'admin','0c947f956f7aa781','admin@nospam.com','admin','user','false');

#
# Table structure for table 'eZUser_UserAddressLink'
#
CREATE TABLE eZUser_UserAddressLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_UserAddressLink'
#


#
# Table structure for table 'eZUser_UserGroupLink'
#
CREATE TABLE eZUser_UserGroupLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  GroupID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_UserGroupLink'
#

INSERT INTO eZUser_UserGroupLink VALUES (42,27,1);
INSERT INTO eZUser_UserGroupLink VALUES (39,28,2);
INSERT INTO eZUser_UserGroupLink VALUES (41,13,1);

