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

INSERT INTO eZUser_Group VALUES (2,'Anonymous','Anonymous users who have created themselves, customers.');
INSERT INTO eZUser_Group VALUES (1,'Administration','All rights');

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
INSERT INTO eZUser_Module VALUES (3,'eZUser');
INSERT INTO eZUser_Module VALUES (5,'eZNews');

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

INSERT INTO eZUser_User VALUES (1,'admin','3d14f82a5508f09f','your@address.tld','Admin','User','false');

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

