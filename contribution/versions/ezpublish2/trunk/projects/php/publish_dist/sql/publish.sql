# MySQL dump 7.1
#
# Host: localhost    Database: publish
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'eZAd_Ad'
#
CREATE TABLE eZAd_Ad (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  ImageID int(11),
  ViewStartDate timestamp(14),
  ViewStopDate timestamp(14),
  ViewRule enum('Period','Click') DEFAULT 'Click',
  URL varchar(200),
  Description text,
  IsActive enum('true','false'),
  ViewPrice float(10,2),
  ClickPrice float(10,2),
  HTMLBanner text NOT NULL,
  UseHTML int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_Ad'
#


#
# Table structure for table 'eZAd_AdCategoryLink'
#
CREATE TABLE eZAd_AdCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  AdID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_AdCategoryLink'
#


#
# Table structure for table 'eZAd_Category'
#
CREATE TABLE eZAd_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  ParentID int(11),
  ExcludeFromSearch enum('true','false') DEFAULT 'false',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_Category'
#


#
# Table structure for table 'eZAd_Click'
#
CREATE TABLE eZAd_Click (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  AdID int(11),
  PageViewID int(11),
  ClickPrice float(10,2),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_Click'
#


#
# Table structure for table 'eZAd_View'
#
CREATE TABLE eZAd_View (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  AdID int(11),
  Date date,
  ViewCount int(11) DEFAULT '0' NOT NULL,
  ViewPrice int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAd_View'
#


#
# Table structure for table 'eZAddress_Address'
#
CREATE TABLE eZAddress_Address (
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
# Dumping data for table 'eZAddress_Address'
#

INSERT INTO eZAddress_Address VALUES (1,'Adminstreet1','Adminstreet2',0,'Noplace','42',0);
INSERT INTO eZAddress_Address VALUES (4,'3q24324324','',1,'sfdasdf','234',0);
INSERT INTO eZAddress_Address VALUES (3,'abc','',1,'Skien','123',0);
INSERT INTO eZAddress_Address VALUES (5,'zzzzzzzzzzzzzzzzzzzzzz','',1,'Oslo','123',0);

#
# Table structure for table 'eZAddress_AddressDefinition'
#
CREATE TABLE eZAddress_AddressDefinition (
  UserID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (UserID,AddressID)
);

#
# Dumping data for table 'eZAddress_AddressDefinition'
#


#
# Table structure for table 'eZAddress_AddressType'
#
CREATE TABLE eZAddress_AddressType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_AddressType'
#

INSERT INTO eZAddress_AddressType VALUES (1,'Post adresse',1,0);

#
# Table structure for table 'eZAddress_Country'
#
CREATE TABLE eZAddress_Country (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ISO char(2),
  Name char(100),
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_Country'
#

INSERT INTO eZAddress_Country VALUES (2,'AF','Afghanistan',0);
INSERT INTO eZAddress_Country VALUES (3,'AL','Albania',0);
INSERT INTO eZAddress_Country VALUES (4,'DZ','Algeria',0);
INSERT INTO eZAddress_Country VALUES (5,'AS','American Samoa',0);
INSERT INTO eZAddress_Country VALUES (6,'AD','Andorra',0);
INSERT INTO eZAddress_Country VALUES (7,'AO','Angola',0);
INSERT INTO eZAddress_Country VALUES (8,'AI','Anguilla',0);
INSERT INTO eZAddress_Country VALUES (9,'AQ','Antarctica',0);
INSERT INTO eZAddress_Country VALUES (10,'AG','Antigua and Barbuda',0);
INSERT INTO eZAddress_Country VALUES (11,'AR','Argentina',0);
INSERT INTO eZAddress_Country VALUES (12,'AM','Armenia',0);
INSERT INTO eZAddress_Country VALUES (13,'AW','Aruba',0);
INSERT INTO eZAddress_Country VALUES (14,'AU','Australia',0);
INSERT INTO eZAddress_Country VALUES (15,'AT','Austria',0);
INSERT INTO eZAddress_Country VALUES (16,'AZ','Azerbaijan',0);
INSERT INTO eZAddress_Country VALUES (17,'BS','Bahamas',0);
INSERT INTO eZAddress_Country VALUES (18,'BH','Bahrain',0);
INSERT INTO eZAddress_Country VALUES (19,'BD','Bangladesh',0);
INSERT INTO eZAddress_Country VALUES (20,'BB','Barbados',0);
INSERT INTO eZAddress_Country VALUES (21,'BY','Belarus',0);
INSERT INTO eZAddress_Country VALUES (22,'BE','Belgium',0);
INSERT INTO eZAddress_Country VALUES (23,'BZ','Belize',0);
INSERT INTO eZAddress_Country VALUES (24,'BJ','Benin',0);
INSERT INTO eZAddress_Country VALUES (25,'BM','Bermuda',0);
INSERT INTO eZAddress_Country VALUES (26,'BT','Bhutan',0);
INSERT INTO eZAddress_Country VALUES (27,'BO','Bolivia',0);
INSERT INTO eZAddress_Country VALUES (28,'BA','Bosnia and Herzegovina',0);
INSERT INTO eZAddress_Country VALUES (29,'BW','Botswana',0);
INSERT INTO eZAddress_Country VALUES (30,'BV','Bouvet Island',0);
INSERT INTO eZAddress_Country VALUES (31,'BR','Brazil',0);
INSERT INTO eZAddress_Country VALUES (32,'IO','British Indian Ocean Territory',0);
INSERT INTO eZAddress_Country VALUES (33,'BN','Brunei Darussalam',0);
INSERT INTO eZAddress_Country VALUES (34,'BG','Bulgaria',0);
INSERT INTO eZAddress_Country VALUES (35,'BF','Burkina Faso',0);
INSERT INTO eZAddress_Country VALUES (36,'BI','Burundi',0);
INSERT INTO eZAddress_Country VALUES (37,'KH','Cambodia',0);
INSERT INTO eZAddress_Country VALUES (38,'CM','Cameroon',0);
INSERT INTO eZAddress_Country VALUES (39,'CA','Canada',0);
INSERT INTO eZAddress_Country VALUES (40,'CV','Cape Verde',0);
INSERT INTO eZAddress_Country VALUES (41,'KY','Cayman Islands',0);
INSERT INTO eZAddress_Country VALUES (42,'CF','Central African Republic',0);
INSERT INTO eZAddress_Country VALUES (43,'TD','Chad',0);
INSERT INTO eZAddress_Country VALUES (44,'CL','Chile',0);
INSERT INTO eZAddress_Country VALUES (45,'CN','China',0);
INSERT INTO eZAddress_Country VALUES (46,'CX','Christmas Island',0);
INSERT INTO eZAddress_Country VALUES (47,'CC','Cocos (Keeling) Islands',0);
INSERT INTO eZAddress_Country VALUES (48,'CO','Colombia',0);
INSERT INTO eZAddress_Country VALUES (49,'KM','Comoros',0);
INSERT INTO eZAddress_Country VALUES (50,'CG','Congo',0);
INSERT INTO eZAddress_Country VALUES (51,'CK','Cook Islands',0);
INSERT INTO eZAddress_Country VALUES (52,'CR','Costa Rica',0);
INSERT INTO eZAddress_Country VALUES (53,'CI','Cote d\'Ivoire',0);
INSERT INTO eZAddress_Country VALUES (54,'HR','Croatia',0);
INSERT INTO eZAddress_Country VALUES (55,'CU','Cuba',0);
INSERT INTO eZAddress_Country VALUES (56,'CY','Cyprus',0);
INSERT INTO eZAddress_Country VALUES (57,'CZ','Czech Republic',0);
INSERT INTO eZAddress_Country VALUES (58,'DK','Denmark',0);
INSERT INTO eZAddress_Country VALUES (59,'DJ','Djibouti',0);
INSERT INTO eZAddress_Country VALUES (60,'DM','Dominica',0);
INSERT INTO eZAddress_Country VALUES (61,'DO','Dominican Republic',0);
INSERT INTO eZAddress_Country VALUES (62,'TP','East Timor',0);
INSERT INTO eZAddress_Country VALUES (63,'EC','Ecuador',0);
INSERT INTO eZAddress_Country VALUES (64,'EG','Egypt',0);
INSERT INTO eZAddress_Country VALUES (65,'SV','El Salvador',0);
INSERT INTO eZAddress_Country VALUES (66,'GQ','Equatorial Guinea',0);
INSERT INTO eZAddress_Country VALUES (67,'ER','Eritrea',0);
INSERT INTO eZAddress_Country VALUES (68,'EE','Estonia',0);
INSERT INTO eZAddress_Country VALUES (69,'ET','Ethiopia',0);
INSERT INTO eZAddress_Country VALUES (70,'FK','Falkland Islands (Malvinas)',0);
INSERT INTO eZAddress_Country VALUES (71,'FO','Faroe Islands',0);
INSERT INTO eZAddress_Country VALUES (72,'FJ','Fiji',0);
INSERT INTO eZAddress_Country VALUES (73,'FI','Finland',0);
INSERT INTO eZAddress_Country VALUES (74,'FR','France',0);
INSERT INTO eZAddress_Country VALUES (75,'FX','France, Metropolitan',0);
INSERT INTO eZAddress_Country VALUES (76,'GF','French Guiana',0);
INSERT INTO eZAddress_Country VALUES (77,'PF','French Polynesia',0);
INSERT INTO eZAddress_Country VALUES (78,'TF','French Southern Territories',0);
INSERT INTO eZAddress_Country VALUES (79,'GA','Gabon',0);
INSERT INTO eZAddress_Country VALUES (80,'GM','Gambia',0);
INSERT INTO eZAddress_Country VALUES (81,'GE','Georgia',0);
INSERT INTO eZAddress_Country VALUES (82,'DE','Germany',0);
INSERT INTO eZAddress_Country VALUES (83,'GH','Ghana',0);
INSERT INTO eZAddress_Country VALUES (84,'GI','Gibraltar',0);
INSERT INTO eZAddress_Country VALUES (85,'GR','Greece',0);
INSERT INTO eZAddress_Country VALUES (86,'GL','Greenland',0);
INSERT INTO eZAddress_Country VALUES (87,'GD','Grenada',0);
INSERT INTO eZAddress_Country VALUES (88,'GP','Guadeloupe',0);
INSERT INTO eZAddress_Country VALUES (89,'GU','Guam',0);
INSERT INTO eZAddress_Country VALUES (90,'GT','Guatemala',0);
INSERT INTO eZAddress_Country VALUES (91,'GN','Guinea',0);
INSERT INTO eZAddress_Country VALUES (92,'GW','Guinea-Bissau',0);
INSERT INTO eZAddress_Country VALUES (93,'GY','Guyana',0);
INSERT INTO eZAddress_Country VALUES (94,'HT','Haiti',0);
INSERT INTO eZAddress_Country VALUES (95,'HM','Heard Island and McDonald Islands',0);
INSERT INTO eZAddress_Country VALUES (96,'HN','Honduras',0);
INSERT INTO eZAddress_Country VALUES (97,'HK','Hong Kong',0);
INSERT INTO eZAddress_Country VALUES (98,'HU','Hungary',0);
INSERT INTO eZAddress_Country VALUES (99,'IS','Iceland',0);
INSERT INTO eZAddress_Country VALUES (100,'IN','India',0);
INSERT INTO eZAddress_Country VALUES (101,'ID','Indonesia',0);
INSERT INTO eZAddress_Country VALUES (102,'IR','Iran (Islamic Republic of)',0);
INSERT INTO eZAddress_Country VALUES (103,'IQ','Iraq',0);
INSERT INTO eZAddress_Country VALUES (104,'IE','Ireland',0);
INSERT INTO eZAddress_Country VALUES (105,'IL','Israel',0);
INSERT INTO eZAddress_Country VALUES (106,'IT','Italy',0);
INSERT INTO eZAddress_Country VALUES (107,'JM','Jamaica',0);
INSERT INTO eZAddress_Country VALUES (108,'JP','Japan',0);
INSERT INTO eZAddress_Country VALUES (109,'JO','Jordan',0);
INSERT INTO eZAddress_Country VALUES (110,'KZ','Kazakhstan',0);
INSERT INTO eZAddress_Country VALUES (111,'KE','Kenya',0);
INSERT INTO eZAddress_Country VALUES (112,'KI','Kiribati',0);
INSERT INTO eZAddress_Country VALUES (113,'KP','Korea, Democratic People\'s Republic of',0);
INSERT INTO eZAddress_Country VALUES (114,'KR','Korea, Republic of',0);
INSERT INTO eZAddress_Country VALUES (115,'KW','Kuwait',0);
INSERT INTO eZAddress_Country VALUES (116,'KG','Kyrgyzstan',0);
INSERT INTO eZAddress_Country VALUES (117,'LA','Lao People\'s Democratic Republic',0);
INSERT INTO eZAddress_Country VALUES (118,'LT','Latin America',0);
INSERT INTO eZAddress_Country VALUES (119,'LV','Latvia',0);
INSERT INTO eZAddress_Country VALUES (120,'LB','Lebanon',0);
INSERT INTO eZAddress_Country VALUES (121,'LS','Lesotho',0);
INSERT INTO eZAddress_Country VALUES (122,'LR','Liberia',0);
INSERT INTO eZAddress_Country VALUES (123,'LY','Libyan Arab Jamahiriya',0);
INSERT INTO eZAddress_Country VALUES (124,'LI','Liechtenstein',0);
INSERT INTO eZAddress_Country VALUES (125,'LX','Lithuania',0);
INSERT INTO eZAddress_Country VALUES (126,'LU','Luxembourg',0);
INSERT INTO eZAddress_Country VALUES (127,'MO','Macau',0);
INSERT INTO eZAddress_Country VALUES (128,'MK','Macedonia',0);
INSERT INTO eZAddress_Country VALUES (129,'MG','Madagascar',0);
INSERT INTO eZAddress_Country VALUES (130,'MW','Malawi',0);
INSERT INTO eZAddress_Country VALUES (131,'MY','Malaysia',0);
INSERT INTO eZAddress_Country VALUES (132,'MV','Maldives',0);
INSERT INTO eZAddress_Country VALUES (133,'ML','Mali',0);
INSERT INTO eZAddress_Country VALUES (134,'MT','Malta',0);
INSERT INTO eZAddress_Country VALUES (135,'MH','Marshall Islands',0);
INSERT INTO eZAddress_Country VALUES (136,'MQ','Martinique',0);
INSERT INTO eZAddress_Country VALUES (137,'MR','Mauritania',0);
INSERT INTO eZAddress_Country VALUES (138,'MU','Mauritius',0);
INSERT INTO eZAddress_Country VALUES (139,'YT','Mayotte',0);
INSERT INTO eZAddress_Country VALUES (140,'MX','Mexico',0);
INSERT INTO eZAddress_Country VALUES (141,'FM','Micronesia (Federated States of)',0);
INSERT INTO eZAddress_Country VALUES (142,'MD','Moldova, Republic of',0);
INSERT INTO eZAddress_Country VALUES (143,'MC','Monaco',0);
INSERT INTO eZAddress_Country VALUES (144,'MN','Mongolia',0);
INSERT INTO eZAddress_Country VALUES (145,'MS','Montserrat',0);
INSERT INTO eZAddress_Country VALUES (146,'MA','Morocco',0);
INSERT INTO eZAddress_Country VALUES (147,'MZ','Mozambique',0);
INSERT INTO eZAddress_Country VALUES (148,'MM','Myanmar',0);
INSERT INTO eZAddress_Country VALUES (149,'NA','Namibia',0);
INSERT INTO eZAddress_Country VALUES (150,'NR','Nauru',0);
INSERT INTO eZAddress_Country VALUES (151,'NP','Nepal',0);
INSERT INTO eZAddress_Country VALUES (152,'NL','Netherlands',0);
INSERT INTO eZAddress_Country VALUES (153,'AN','Netherlands Antilles',0);
INSERT INTO eZAddress_Country VALUES (154,'NC','New Caledonia',0);
INSERT INTO eZAddress_Country VALUES (155,'NZ','New Zealand',0);
INSERT INTO eZAddress_Country VALUES (156,'NI','Nicaragua',0);
INSERT INTO eZAddress_Country VALUES (157,'NE','Niger',0);
INSERT INTO eZAddress_Country VALUES (158,'NG','Nigeria',0);
INSERT INTO eZAddress_Country VALUES (159,'NU','Niue',0);
INSERT INTO eZAddress_Country VALUES (160,'NF','Norfolk Island',0);
INSERT INTO eZAddress_Country VALUES (161,'MP','Northern Mariana Islands',0);
INSERT INTO eZAddress_Country VALUES (162,'NO','Norway',0);
INSERT INTO eZAddress_Country VALUES (163,'OM','Oman',0);
INSERT INTO eZAddress_Country VALUES (164,'PK','Pakistan',0);
INSERT INTO eZAddress_Country VALUES (165,'PW','Palau',0);
INSERT INTO eZAddress_Country VALUES (166,'PA','Panama',0);
INSERT INTO eZAddress_Country VALUES (167,'PG','Papua New Guinea',0);
INSERT INTO eZAddress_Country VALUES (168,'PY','Paraguay',0);
INSERT INTO eZAddress_Country VALUES (169,'PE','Peru',0);
INSERT INTO eZAddress_Country VALUES (170,'PH','Philippines',0);
INSERT INTO eZAddress_Country VALUES (171,'PN','Pitcairn',0);
INSERT INTO eZAddress_Country VALUES (172,'PL','Poland',0);
INSERT INTO eZAddress_Country VALUES (173,'PT','Portugal',0);
INSERT INTO eZAddress_Country VALUES (174,'PR','Puerto Rico',0);
INSERT INTO eZAddress_Country VALUES (175,'QA','Qatar',0);
INSERT INTO eZAddress_Country VALUES (176,'RE','Reunion',0);
INSERT INTO eZAddress_Country VALUES (177,'RO','Romania',0);
INSERT INTO eZAddress_Country VALUES (178,'RU','Russian Federation',0);
INSERT INTO eZAddress_Country VALUES (179,'RW','Rwanda',0);
INSERT INTO eZAddress_Country VALUES (180,'SH','Saint Helena',0);
INSERT INTO eZAddress_Country VALUES (181,'KN','Saint Kitts and Nevis',0);
INSERT INTO eZAddress_Country VALUES (182,'LC','Saint Lucia',0);
INSERT INTO eZAddress_Country VALUES (183,'PM','Saint Pierre and Miquelon',0);
INSERT INTO eZAddress_Country VALUES (184,'VC','Saint Vincent and the Grenadines',0);
INSERT INTO eZAddress_Country VALUES (185,'WS','Samoa',0);
INSERT INTO eZAddress_Country VALUES (186,'SM','San Marino',0);
INSERT INTO eZAddress_Country VALUES (187,'ST','Sao Tome and Principe',0);
INSERT INTO eZAddress_Country VALUES (188,'SA','Saudi Arabia',0);
INSERT INTO eZAddress_Country VALUES (189,'SN','Senegal',0);
INSERT INTO eZAddress_Country VALUES (190,'SC','Seychelles',0);
INSERT INTO eZAddress_Country VALUES (191,'SL','Sierra Leone',0);
INSERT INTO eZAddress_Country VALUES (192,'SG','Singapore',0);
INSERT INTO eZAddress_Country VALUES (193,'SK','Slovakia',0);
INSERT INTO eZAddress_Country VALUES (194,'SI','Slovenia',0);
INSERT INTO eZAddress_Country VALUES (195,'SB','Solomon Islands',0);
INSERT INTO eZAddress_Country VALUES (196,'SO','Somalia',0);
INSERT INTO eZAddress_Country VALUES (197,'ZA','South Africa',0);
INSERT INTO eZAddress_Country VALUES (198,'GS','South Georgia and the South Sandwich Island',0);
INSERT INTO eZAddress_Country VALUES (199,'ES','Spain',0);
INSERT INTO eZAddress_Country VALUES (200,'LK','Sri Lanka',0);
INSERT INTO eZAddress_Country VALUES (201,'SD','Sudan',0);
INSERT INTO eZAddress_Country VALUES (202,'SR','Suriname',0);
INSERT INTO eZAddress_Country VALUES (203,'SJ','Svalbard and Jan Mayen Islands',0);
INSERT INTO eZAddress_Country VALUES (204,'SZ','Swaziland',0);
INSERT INTO eZAddress_Country VALUES (205,'SE','Sweden',0);
INSERT INTO eZAddress_Country VALUES (206,'CH','Switzerland',0);
INSERT INTO eZAddress_Country VALUES (207,'SY','Syrian Arab Republic',0);
INSERT INTO eZAddress_Country VALUES (208,'TW','Taiwan, Republic of China',0);
INSERT INTO eZAddress_Country VALUES (209,'TJ','Tajikistan',0);
INSERT INTO eZAddress_Country VALUES (210,'TZ','Tanzania, United Republic of',0);
INSERT INTO eZAddress_Country VALUES (211,'TH','Thailand',0);
INSERT INTO eZAddress_Country VALUES (212,'TG','Togo',0);
INSERT INTO eZAddress_Country VALUES (213,'TK','Tokelau',0);
INSERT INTO eZAddress_Country VALUES (214,'TO','Tonga',0);
INSERT INTO eZAddress_Country VALUES (215,'TT','Trinidad and Tobago',0);
INSERT INTO eZAddress_Country VALUES (216,'TN','Tunisia',0);
INSERT INTO eZAddress_Country VALUES (217,'TR','Turkey',0);
INSERT INTO eZAddress_Country VALUES (218,'TM','Turkmenistan',0);
INSERT INTO eZAddress_Country VALUES (219,'TC','Turks and Caicos Islands',0);
INSERT INTO eZAddress_Country VALUES (220,'TV','Tuvalu',0);
INSERT INTO eZAddress_Country VALUES (221,'UG','Uganda',0);
INSERT INTO eZAddress_Country VALUES (222,'UA','Ukraine',0);
INSERT INTO eZAddress_Country VALUES (223,'AE','United Arab Emirates',0);
INSERT INTO eZAddress_Country VALUES (224,'GB','United Kingdom',0);
INSERT INTO eZAddress_Country VALUES (225,'UM','United States Minor Outlying Islands',0);
INSERT INTO eZAddress_Country VALUES (226,'UY','Uruguay',0);
INSERT INTO eZAddress_Country VALUES (227,'UZ','Uzbekistan',0);
INSERT INTO eZAddress_Country VALUES (228,'VU','Vanuatu',0);
INSERT INTO eZAddress_Country VALUES (229,'VA','Vatican City State (Holy See)',0);
INSERT INTO eZAddress_Country VALUES (230,'VE','Venezuela',0);
INSERT INTO eZAddress_Country VALUES (231,'VN','Viet Nam',0);
INSERT INTO eZAddress_Country VALUES (232,'VG','Virgin Islands (British)',0);
INSERT INTO eZAddress_Country VALUES (233,'VI','Virgin Islands (U.S.)',0);
INSERT INTO eZAddress_Country VALUES (234,'WF','Wallis and Futuna Islands',0);
INSERT INTO eZAddress_Country VALUES (235,'EH','Western Sahara',0);
INSERT INTO eZAddress_Country VALUES (236,'YE','Yemen',0);
INSERT INTO eZAddress_Country VALUES (237,'YU','Yugoslavia',0);
INSERT INTO eZAddress_Country VALUES (238,'ZR','Zaire',0);
INSERT INTO eZAddress_Country VALUES (239,'ZM','Zambia',0);
INSERT INTO eZAddress_Country VALUES (240,'US','United States of America',0);

#
# Table structure for table 'eZAddress_Online'
#
CREATE TABLE eZAddress_Online (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  URL char(255),
  OnlineTypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_Online'
#

INSERT INTO eZAddress_Online VALUES (3,'eadasfdasdf@sdfaasf',1);
INSERT INTO eZAddress_Online VALUES (2,'jb@ez.no',1);
INSERT INTO eZAddress_Online VALUES (4,'a@b.e',1);

#
# Table structure for table 'eZAddress_OnlineType'
#
CREATE TABLE eZAddress_OnlineType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  URLPrefix varchar(30) DEFAULT '' NOT NULL,
  PrefixLink int(1) DEFAULT '0' NOT NULL,
  PrefixVisual int(1) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_OnlineType'
#

INSERT INTO eZAddress_OnlineType VALUES (1,'Email',1,'mailto:',1,0,0);

#
# Table structure for table 'eZAddress_Phone'
#
CREATE TABLE eZAddress_Phone (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Number varchar(22),
  PhoneTypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_Phone'
#

INSERT INTO eZAddress_Phone VALUES (3,'32324324',1);
INSERT INTO eZAddress_Phone VALUES (2,'456',1);
INSERT INTO eZAddress_Phone VALUES (4,'234234234',1);

#
# Table structure for table 'eZAddress_PhoneType'
#
CREATE TABLE eZAddress_PhoneType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_PhoneType'
#

INSERT INTO eZAddress_PhoneType VALUES (1,'Telefon',1,0);

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

INSERT INTO eZArticle_Article VALUES (1,'Demo article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This article will show the tags you can use in eZ publish.</intro><body><page><header>Standard tags</header>\r\n\r\nThis is <bold>bold</bold> text.\r\nThis is <strike>strike</strike> text.\r\nThis is <underline>underline</underline> text.\r\n\r\n<pre>\r\nPre defined text\r\n  indented\r\n    as \r\n      written.\r\n</pre>\r\n<bullet>\r\nItem one\r\nItem two\r\nItem three\r\n</bullet>\r\n\r\n<header>Image tags</header>\r\n\r\n<image id=\"1\" align=\"left\" size=\"medium\" /> Fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text.\r\n\r\n<image id=\"2\" align=\"center\" size=\"medium\" />\r\n\r\nImages on a row\r\n\r\n<image id=\"1\" align=\"float\" size=\"small\" /> <image id=\"2\" align=\"float\" size=\"small\" /> <image id=\"1\" align=\"float\" size=\"small\" /> <image id=\"2\" align=\"float\" size=\"small\" /></page></body></article>','admin user','See demo',27,20010126110422,20010126100350,1,'true',20010126100350,'tech\nThis article will show the tags you can use in eZ publish.Standard tags\r\n\r\nThis is bold text.\r\nThis strike underline text.\r\n\r\n\r\nPre defined text\r\n  indented\r\n as \r\n written.\r\n\r\n\r\nItem one\r\nItem two\r\nItem three\r\n\r\n\r\nImage tags\r\n\r\n Fill text fill text.\r\n\r\n\r\n\r\nImages on a row\r\n\r\n ');
INSERT INTO eZArticle_Article VALUES (5,'What is New in 2.0?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This is a major new release of eZ publish, we\'ve added lots of new information.</intro><body><page><bullet>Merged eZ trade with eZ publish\r\nAdded about module\r\nAdded ad module\r\nAdded address module\r\nAdded bug tracking module\r\nAdded calendar module\r\nAdded contact module\r\nAdded newsfeed module\r\nAdded statistics module\r\nAdded todo module\r\nAdded cookie-less sessions \r\nAdded absolute positioning of products and articles\r\nAdded choosable sort mode on article categories\r\nAdded choosable sort mode on product categories\r\nAdded previous/next paging of article lists (admin &amp; user )\r\nAdded previous/next paging of product lists (admin &amp; user )\r\nAdded RSS headlines\r\nAdded forum moderation with assignment of moderator\r\nAdded notification when articles are published\r\nAdded file uploads on articles.\r\nAdded dynamically updating of menues with static pages.\r\nAdded file upload to eZ article\r\nAdded word wrap of message replies in eZ forum. Nicer looking replies.\r\nAdded new tags in articles (bullet lists/includes of php files)\r\nAdded preferred layout for users\r\nMade the menus in the admin module expandable/collapsable as well as moveable. This is remembered by the preferences for each user. We\'ve also changed the design to a more sleek version.\r\nLanguage updates\r\nRemoved java script which were a problem for lynx users.\r\nRemoved strip tags from messages in eZ forum\r\nSpeeded up many features among them database connections, localisation, rendering of articles, templates and HTML.\r\nFixed bugs</bullet>\r\n\r\n\r\nRead on to learn how to use some of the new features.\r\n</page><page>\r\n<header>RSS Headlines</header>\r\nYou can access the RSS Headlines of eZ publish from the URL \"/article/rssheadlines\".\r\n\r\nIn \"site.ini\" you can configure some of its options; read more in the \"eZ article Admin\'s Guide\" and \"eZ publish Customisation Guide\".\r\n\r\n<header>About</header>\r\nIf you write in the URL \"/about\" you\'ll be presented with an about box for eZ publish.\r\n\r\n<header>User Preferences</header>\r\nWe\'ve added preference functionality. If you take a look into the left hand column of this site you\'ll find some links which are called \"intranet\", \"portal site\" and \"E-commerce\". Those links take you to different designs for eZ publish (only two links will be shown at any time).\r\n\r\nAn example of its usage might be to give users the option of reading your site with different amounts of graphics, or different text sizes.\r\n\r\n<header>Cookie-less Sessions</header>\r\nWe\'ve added cookie-less sessions.\r\n\r\n<header>Moderated Forums</header>\r\nWe\'ve added the much requested moderation functionality to forums. Now you can assign a moderator to each and every forum.\r\n\r\nUsage for this function might, in addition to plain old moderation, is to protect forums so that you can use them as an FAQ.</page></body></article>','admin user','Read the changelog...',27,20010126112508,20010126102640,2,'true',20010126102640,'tech\nThis is a major new release of eZ publish, we\'ve added lots information.Merged trade with publish\r\nAdded about module\r\nAdded ad address bug tracking calendar contact newsfeed statistics todo cookie-less sessions \r\nAdded absolute positioning products and articles\r\nAdded choosable sort mode on article categories\r\nAdded product previous/next paging lists (admin &amp; user )\r\nAdded RSS headlines\r\nAdded forum moderation assignment moderator\r\nAdded notification when articles are published\r\nAdded file uploads articles.\r\nAdded dynamically updating menues static pages.\r\nAdded upload to article\r\nAdded word wrap message replies in forum. Nicer looking replies.\r\nAdded tags (bullet lists/includes php files)\r\nAdded preferred layout for users\r\nMade the menus admin module expandable/collapsable as well moveable. This remembered by preferences each user. We\'ve also changed design more sleek version.\r\nLanguage updates\r\nRemoved java script which were problem lynx users.\r\nRemoved strip from messages forum\r\nSpeeded up many features among them database connections, localisation, rendering articles, templates HTML.\r\nFixed bugs\r\n\r\n\r\nRead learn how use some features.\r\n\r\nRSS Headlines\r\nYou can access Headlines publish URL \"/article/rssheadlines\".\r\n\r\nIn \"site.ini\" you configure its options; read \"eZ Admin\'s Guide\" Customisation Guide\".\r\n\r\nAbout\r\nIf write \"/about\" you\'ll be presented an box publish.\r\n\r\nUser Preferences\r\nWe\'ve preference functionality. If take look into left hand column this site find links called \"intranet\", \"portal site\" \"E-commerce\". Those different designs (only two will shown at any time).\r\n\r\nAn example usage might give users option reading your amounts graphics, or text sizes.\r\n\r\nCookie-less Sessions\r\nWe\'ve sessions.\r\n\r\nModerated Forums\r\nWe\'ve much requested functionality forums. Now assign moderator every forum.\r\n\r\nUsage function might, addition plain old moderation, protect forums so that FAQ. ');
INSERT INTO eZArticle_Article VALUES (4,'How does static pages work?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>Static pages are articles entered into the normal article system, but which belong to a category which has specific settings.</intro><body><page>All categories can have that special setting, which is called \"Exclude from search\". Not only does this disallow normal search functions, but those articles will not be shown in normal archive listings, nor latest article listings or the rss headlines.\r\n\r\nYou will neither see the name of the author of a static page; it is anonymous to the reader.\r\n\r\n<header>Intended Usage</header>\r\n\r\nThe usage of such pages is intended to create copyright notices, address info and other static information; thus the name.\r\n\r\nThat doesn\'t exclude any or all other methods you would like to use, but this is how we use it.\r\n\r\n<header>Tricks of the Trade</header>\r\n\r\nA category listing for static pages might be used on the front page; when you add a new page it will be added to the menu.\r\n\r\nBy changing the category sort method to \"Absolute positioning\" you can order the rendering of the menu to suit your desires.\r\n\r\nYou could also create several static page groups, and use those to good effect to distinguish information.\r\n\r\nFor all other intents and purposes articles written as static pages are the same as normal articles.</page></body></article>','admin user','',27,20010126102509,20010126101612,1,'true',20010126101612,'tech\nStatic pages are articles entered into the normal article system, but which belong to a category has specific settings.All categories can have that special setting, is called \"Exclude from search\". Not only does this disallow search functions, those will not be shown in archive listings, nor latest listings or rss headlines.\r\n\r\nYou neither see name of author static page; it anonymous reader.\r\n\r\nIntended Usage\r\n\r\nThe usage such intended create copyright notices, address info and other information; thus name.\r\n\r\nThat doesn\'t exclude any all methods you would like use, how we use it.\r\n\r\nTricks Trade\r\n\r\nA listing for might used on front when add new page added menu.\r\n\r\nBy changing sort method \"Absolute positioning\" order rendering menu suit your desires.\r\n\r\nYou could also several groups, good effect distinguish information.\r\n\r\nFor intents purposes written as same articles. ');
INSERT INTO eZArticle_Article VALUES (8,'eZ Trade','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ Trade has had a couple of face lifts as well.</intro><body><page><header>Category Sorting</header>\r\nYou can set the sorting methods of both article and trade categories.\r\n\r\nAmong other sorting methods we\'ve added absolute positioning. This feature might be good for presenting a front page of your site where certain items appear at specific places.\r\n\r\nBy \"cross posting\" news and products to both their main category and the category with absolute positioning you can have items appear on the front page at a certain position within the time limit you want.\r\n\r\n<header>Types</header>\r\nYou can define a product type, where you set what kind of information that type requires. Then when creating a product you can set the type of the product and enter the required data.\r\n\r\nLooks great and can be used for comparision of features.\r\n\r\nCombine this with options for your products to create really compelling product pages.\r\n</page></body></article>','admin user','',27,20010126120506,20010126112654,1,'true',20010126112654,'tech\neZ Trade has had a couple of face lifts as well.Category Sorting\r\nYou can set the sorting methods both article and trade categories.\r\n\r\nAmong other we\'ve added absolute positioning. This feature might be good for presenting front page your site where certain items appear at specific places.\r\n\r\nBy \"cross posting\" news products to their main category with positioning you have on position within time limit want.\r\n\r\nTypes\r\nYou define product type, what kind information that type requires. Then when creating enter required data.\r\n\r\nLooks great used comparision features.\r\n\r\nCombine this options create really compelling pages.\r\n ');
INSERT INTO eZArticle_Article VALUES (6,'eZ Newsfeed','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ newsfeed is a new module from 2.0. It fetches RSS headlines from other sites.</intro><body><page>The news feed is a module which fetches headlines from RSS enabled sites, pluss a couple of speciality sites.\r\n\r\nFetching RSS headlines is easy, just point eZ publish to the URL you want, and it will fetch the info into a queue. You can then select which items you want to publish from that queue.\r\n\r\nIt is possible to create your own fetch methods which fetches headlines from other sites. PHP programming required.</page></body></article>','admin user','',27,20010126112345,20010126111844,1,'true',20010126111844,'tech\neZ newsfeed is a new module from 2.0. It fetches RSS headlines other sites.The news feed which enabled sites, pluss couple of speciality sites.\r\n\r\nFetching easy, just point eZ publish to the URL you want, and it will fetch info into queue. You can then select items want that queue.\r\n\r\nIt possible create your own methods sites. PHP programming required. ');
INSERT INTO eZArticle_Article VALUES (7,'eZ Article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>Some additions were made to eZ article, the main points are presented here.</intro><body><page><header>Article Comments</header>\r\nWhen readers comment on an article eZ publish will now send an e-mail to the user who published the article.\r\n\r\n<header>File Attachments</header>\r\nAs you can see from this article it is now possible to add files to an article; thus you can use the article for distributing files.\r\n\r\n<header>Category Sorting</header>\r\nYou can set the sorting methods of article categories.\r\n\r\nAmong other sorting methods we\'ve added absolute positioning. This feature might be good for presenting a front page of your site where certain items appear at specific places\r\n\r\n<header>Include Generated Content</header>\r\neZ Article now accepts a tag called module it takes a second argument, a file name, sans extension. The extension is assumed to be .php.\r\n\r\nThe article will parse and include that file from \"ezarticle/modules\". Thus you can create much fancier lay outs than what you\'d normally get from the standard renderer.</page></body></article>','admin user','',27,20010126125446,20010126112242,1,'true',20010126112242,'tech\nSome additions were made to eZ article, the main points are presented here.Article Comments\r\nWhen readers comment on an article publish will now send e-mail user who published article.\r\n\r\nFile Attachments\r\nAs you can see from this it is possible add files article; thus use for distributing files.\r\n\r\nCategory Sorting\r\nYou set sorting methods of categories.\r\n\r\nAmong other we\'ve added absolute positioning. This feature might be good presenting a front page your site where certain items appear at specific places\r\n\r\nInclude Generated Content\r\neZ Article accepts tag called module takes second argument, file name, sans extension. The extension assumed .php.\r\n\r\nThe parse and include that \"ezarticle/modules\". Thus create much fancier lay outs than what you\'d normally get standard renderer. ');
INSERT INTO eZArticle_Article VALUES (9,'What can eZ publish Do?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ publish is a web based application suite. It delivers functionality ranging from publishing of news, web logs and diaries, through web shop functionality like shopping carts and wishlists and forums to intranet functions like contact handling and bug reporting.\r\n\r\nThe software uses caching and other optimization techniques to speed up page serving. It handles users, user preferences and user tracking through a user database and both cookie-based and non-cookie sessions.\r\n\r\nIt supports statistics for page views, links followed and banner ads, both images and HTML with presentation logic.\r\n\r\nThe package lends itself easily to customization, from changing the look and feel by changing templates, localizing the languages and other internationalization issues to adding new functionality.\r\n\r\nThe target audience for eZ publish are e-commerce, ASP (Application Service Providers), BSP (Business Service Providers), news publishing, intranets, bug reporting, content management, discussion boards, FAQ and knowledge handling, file and image management, group ware, calendaring, polls, todo lists, appointments as well as personal web sites.\r\n</intro><body><page>eZ publish is a web based application suite which delivers the following functionality:\r\n\r\n<bullet>Advertising with statistics\r\nArticle publication and management\r\nBug handling and reporting\r\nCalendar functionality for creating appointments and events\r\nContact handling for keeping track of people and businesses\r\nFile manager for keeping track of uploaded files\r\nModerated forums for discussions\r\nImage manager for keeping track of uploaded images\r\nLink manager which is used to categorize links\r\nNews feed importing, fetch news and headlines from other sites and incorporate them in your own(1)\r\nPoll module for creating user polls.\r\nSession module for keeping track of users and their preferences\r\nStatistics module for information about page views and visitors\r\nTo-do module for assigning tasks to people\r\nTrade module which is an online shop, with shopping cart and wishlist\r\nUser management for registering users, giving access to different groups to different parts of the site</bullet>\r\n\r\nThe software does not believe in limits(2):\r\n\r\n<bullet>No limits on categories and items in categories\r\nArticles, products and links might belong to several different categories\r\nNo limits on people associated with a company, or the number of people and companies registered totally\r\nNo limits of addresses, phone numbers and other contact points for people and businesses\r\nNo limits on users, the groups they might belong to and number of user groups</bullet>\r\n      \r\nBased on PHP, the leading programming language module for the Apache web server software, eZ publish draws on the speed from this renown software. The backend database is MySQL which is fast and reliable, proven on thousands of Internet sites.\r\n\r\nFurther speed enhancements are made in the eZ publish by using caching of data and reduced connections to the database.\r\n\r\nAll the default templates delivered with eZ publish are tested on a diverse mix of browsers, Opera, Internet Explorer, Netscape, Konqueror and Lynx, thus enabling all users to gain access to your site.\r\n\r\nSo called cookie-less sessions are supported, a method used to enable user recognition even for those who objects to use cookies, no-one will feel left out or overseen.\r\n\r\neZ publish keeps track of the statistics of your site. How many visitors, from where, what do they buy and what are they looking at.\r\n\r\nThe package has been translated to several languages, you can even translate it yourself through the eZ Babel software we\'ve developed for this purpose specifically.\r\n\r\nChanging the design of your site is easy because of separation of content and design. You don\'t have to know anything about PHP or coding, just something about HTML.\r\n\r\nFor those proficient in programming PHP the source code is available, it can be used as a basis for adding new modules and functionality tailored to your specific needs.\r\n\r\nThe target audience for eZ publish are e-commerce, ASP (Application Service Providers), news publishing, intranets, bug reporting, content management, discussion boards, FAQ and knowledge handling, file and image management, group ware, calendaring, appointments.\r\n\r\n     \r\n(1) We do not encourage copyright infringements with this feature. Our default templates will not pass these news items as the site\'s own. \r\n\r\nAsk permission from copyright holder before publishing other site\'s news on your site.\r\n\r\n(2) There are limits, of course, since the system is based on other software, and because it will run on systems with different sizes of hard disks and ram, as well as processor speed.\r\n</page></body></article>','admin user','',27,20010126121313,20010126115247,1,'true',20010126115247,'tech\neZ publish is a web based application suite. It delivers functionality ranging from publishing of news, logs and diaries, through shop like shopping carts wishlists forums to intranet functions contact handling bug reporting.\r\n\r\nThe software uses caching other optimization techniques speed up page serving. handles users, user preferences tracking database both cookie-based non-cookie sessions.\r\n\r\nIt supports statistics for views, links followed banner ads, images HTML with presentation logic.\r\n\r\nThe package lends itself easily customization, changing the look feel by templates, localizing languages internationalization issues adding new functionality.\r\n\r\nThe target audience eZ are e-commerce, ASP (Application Service Providers), BSP (Business news publishing, intranets, reporting, content management, discussion boards, FAQ knowledge handling, file image group ware, calendaring, polls, todo lists, appointments as well personal sites.\r\neZ suite which following functionality:\r\n\r\nAdvertising statistics\r\nArticle publication management\r\nBug reporting\r\nCalendar creating events\r\nContact keeping track people businesses\r\nFile manager uploaded files\r\nModerated discussions\r\nImage images\r\nLink used categorize links\r\nNews feed importing, fetch headlines sites incorporate them in your own(1)\r\nPoll module polls.\r\nSession users their preferences\r\nStatistics information about views visitors\r\nTo-do assigning tasks people\r\nTrade an online shop, cart wishlist\r\nUser management registering giving access different groups parts site\r\n\r\nThe does not believe limits(2):\r\n\r\nNo limits on categories items categories\r\nArticles, products might belong several categories\r\nNo associated company, or number companies registered totally\r\nNo addresses, phone numbers points businesses\r\nNo they groups\r\n  \r\nBased PHP, leading programming language Apache server software, draws this renown software. The backend MySQL fast reliable, proven thousands Internet sites.\r\n\r\nFurther enhancements made using data reduced connections database.\r\n\r\nAll default templates delivered tested diverse mix browsers, Opera, Explorer, Netscape, Konqueror Lynx, thus enabling all gain site.\r\n\r\nSo called cookie-less sessions supported, method enable recognition even those who objects use cookies, no-one will left out overseen.\r\n\r\neZ keeps site. How many visitors, where, what do buy looking at.\r\n\r\nThe has been translated languages, you can translate it yourself Babel we\'ve developed purpose specifically.\r\n\r\nChanging design site easy because separation design. You don\'t have know anything PHP coding, just something HTML.\r\n\r\nFor proficient source code available, be basis modules tailored specific needs.\r\n\r\nThe appointments.\r\n\r\n \r\n(1) We encourage copyright infringements feature. Our pass these site\'s own. \r\n\r\nAsk permission holder before site.\r\n\r\n(2) There limits, course, since system run systems sizes hard disks ram, processor speed.\r\n ');

#
# Table structure for table 'eZArticle_ArticleCategoryDefinition'
#
CREATE TABLE eZArticle_ArticleCategoryDefinition (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleCategoryDefinition'
#

INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (6,1,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (12,5,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (10,6,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (4,4,2);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (17,7,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (15,8,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (16,9,1);

#
# Table structure for table 'eZArticle_ArticleCategoryLink'
#
CREATE TABLE eZArticle_ArticleCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  Placement int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleCategoryLink'
#

INSERT INTO eZArticle_ArticleCategoryLink VALUES (6,1,1,15);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (14,5,1,10);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (7,1,4,7);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (4,4,2,4);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (8,1,3,8);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (12,6,1,18);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (19,7,1,20);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (18,9,1,13);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (17,8,1,19);

#
# Table structure for table 'eZArticle_ArticleFileLink'
#
CREATE TABLE eZArticle_ArticleFileLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleFileLink'
#

INSERT INTO eZArticle_ArticleFileLink VALUES (1,5,1,20010126103230);

#
# Table structure for table 'eZArticle_ArticleForumLink'
#
CREATE TABLE eZArticle_ArticleForumLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ArticleID int(11) DEFAULT '0' NOT NULL,
  ForumID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_ArticleForumLink'
#

INSERT INTO eZArticle_ArticleForumLink VALUES (1,1,2);
INSERT INTO eZArticle_ArticleForumLink VALUES (2,5,3);
INSERT INTO eZArticle_ArticleForumLink VALUES (3,9,4);
INSERT INTO eZArticle_ArticleForumLink VALUES (4,6,5);
INSERT INTO eZArticle_ArticleForumLink VALUES (5,7,6);

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

INSERT INTO eZArticle_ArticleImageDefinition VALUES (4,20);
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

INSERT INTO eZArticle_ArticleImageLink VALUES (1,1,1,20010126100427);
INSERT INTO eZArticle_ArticleImageLink VALUES (2,1,2,20010126100445);

#
# Table structure for table 'eZArticle_Category'
#
CREATE TABLE eZArticle_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11) DEFAULT '0',
  ExcludeFromSearch enum('true','false') DEFAULT 'false',
  SortMode int(11) DEFAULT '1' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZArticle_Category'
#

INSERT INTO eZArticle_Category VALUES (1,'News','',0,'false',4);
INSERT INTO eZArticle_Category VALUES (2,'Static pages','',0,'true',4);
INSERT INTO eZArticle_Category VALUES (3,'Category three','',0,'false',3);
INSERT INTO eZArticle_Category VALUES (4,'Category four','',0,'false',3);

#
# Table structure for table 'eZBug_Bug'
#
CREATE TABLE eZBug_Bug (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  UserID int(11) DEFAULT '0' NOT NULL,
  Created timestamp(14),
  IsHandled enum('true','false') DEFAULT 'false' NOT NULL,
  PriorityID int(11) DEFAULT '0' NOT NULL,
  StatusID int(11) DEFAULT '0' NOT NULL,
  IsClosed enum('true','false') DEFAULT 'false',
  UserEmail varchar(100) DEFAULT '',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Bug'
#

INSERT INTO eZBug_Bug VALUES (1,'Help!','It dosent work!',33,20010125202931,'false',0,0,'','');

#
# Table structure for table 'eZBug_BugCategoryLink'
#
CREATE TABLE eZBug_BugCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  BugID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_BugCategoryLink'
#

INSERT INTO eZBug_BugCategoryLink VALUES (1,2,1);

#
# Table structure for table 'eZBug_BugModuleLink'
#
CREATE TABLE eZBug_BugModuleLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ModuleID int(11),
  BugID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_BugModuleLink'
#

INSERT INTO eZBug_BugModuleLink VALUES (1,1,1);

#
# Table structure for table 'eZBug_Category'
#
CREATE TABLE eZBug_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Category'
#

INSERT INTO eZBug_Category VALUES (1,'GUI','');
INSERT INTO eZBug_Category VALUES (2,'Feature request','');

#
# Table structure for table 'eZBug_Log'
#
CREATE TABLE eZBug_Log (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  BugID int(11) DEFAULT '0' NOT NULL,
  UserID int(11) DEFAULT '0' NOT NULL,
  Description text,
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Log'
#


#
# Table structure for table 'eZBug_Module'
#
CREATE TABLE eZBug_Module (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ParentID int(11),
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Module'
#

INSERT INTO eZBug_Module VALUES (1,0,'My program','');

#
# Table structure for table 'eZBug_Priority'
#
CREATE TABLE eZBug_Priority (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(150) DEFAULT '' NOT NULL,
  Value int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Priority'
#

INSERT INTO eZBug_Priority VALUES (1,'High',NULL);
INSERT INTO eZBug_Priority VALUES (2,'Middels',NULL);
INSERT INTO eZBug_Priority VALUES (3,'Low',NULL);

#
# Table structure for table 'eZBug_Status'
#
CREATE TABLE eZBug_Status (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(150) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZBug_Status'
#

INSERT INTO eZBug_Status VALUES (1,'Fixed');

#
# Table structure for table 'eZCalendar_Appointment'
#
CREATE TABLE eZCalendar_Appointment (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  Date timestamp(14),
  Duration time,
  AppointmentTypeID int(11) DEFAULT '0' NOT NULL,
  EMailNotice int(11) DEFAULT '0',
  IsPrivate int(11),
  Name varchar(200),
  Description text,
  Priority int(11) DEFAULT '1' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZCalendar_Appointment'
#


#
# Table structure for table 'eZCalendar_AppointmentType'
#
CREATE TABLE eZCalendar_AppointmentType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ParentID int(11) DEFAULT '0' NOT NULL,
  Description text,
  Name varchar(200),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZCalendar_AppointmentType'
#


#
# Table structure for table 'eZContact_Company'
#
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
CREATE TABLE eZContact_CompanyPhoneDict (
  CompanyID int(11) DEFAULT '0' NOT NULL,
  PhoneID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (CompanyID,PhoneID)
);

#
# Dumping data for table 'eZContact_CompanyPhoneDict'
#


#
# Table structure for table 'eZContact_CompanyProjectDict'
#
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


#
# Table structure for table 'eZFileManager_File'
#
CREATE TABLE eZFileManager_File (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(200),
  Description char(200),
  FileName char(200),
  OriginalFileName char(200),
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZFileManager_File'
#

INSERT INTO eZFileManager_File VALUES (1,'CHANGELOG','The complete change log.','phpUuO7Ms','CHANGELOG',0,0,0);

#
# Table structure for table 'eZFileManager_FileFolderLink'
#
CREATE TABLE eZFileManager_FileFolderLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  FolderID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZFileManager_FileFolderLink'
#


#
# Table structure for table 'eZFileManager_FilePageViewLink'
#
CREATE TABLE eZFileManager_FilePageViewLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  PageViewID int(11) DEFAULT '0' NOT NULL,
  FileID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZFileManager_FilePageViewLink'
#

INSERT INTO eZFileManager_FilePageViewLink VALUES (1,121,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (2,123,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (3,216,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (4,217,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (5,219,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (6,221,1);
INSERT INTO eZFileManager_FilePageViewLink VALUES (7,223,1);

#
# Table structure for table 'eZFileManager_Folder'
#
CREATE TABLE eZFileManager_Folder (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11) DEFAULT '0' NOT NULL,
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZFileManager_Folder'
#


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
  Name varchar(20) DEFAULT '' NOT NULL,
  Description varchar(40),
  Private enum('Y','N') DEFAULT 'N',
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ModeratorID int(11) DEFAULT '0' NOT NULL,
  IsModerated int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Forum'
#

INSERT INTO eZForum_Forum VALUES ('Discussion','Discuss everything here','',1,27,0);
INSERT INTO eZForum_Forum VALUES ('Demo article','','',2,0,0);
INSERT INTO eZForum_Forum VALUES ('What is New?','','',3,0,0);
INSERT INTO eZForum_Forum VALUES ('What can eZ publish','','',4,0,0);
INSERT INTO eZForum_Forum VALUES ('eZ Newsfeed','','',5,0,0);
INSERT INTO eZForum_Forum VALUES ('eZ Article','','',6,0,0);

#
# Table structure for table 'eZForum_ForumCategoryLink'
#
CREATE TABLE eZForum_ForumCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ForumID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_ForumCategoryLink'
#

INSERT INTO eZForum_ForumCategoryLink VALUES (3,1,1);

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
  IsApproved int(11) DEFAULT '1' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZForum_Message'
#

INSERT INTO eZForum_Message VALUES (1,'First post!','This is the first post!',27,0,'N',20010122104742,1,0,0,1,1);
INSERT INTO eZForum_Message VALUES (1,'SV: First post!','> This is the first post!\r\nThis is the reply!',27,1,'N',20010122104747,0,0,1,2,1);

#
# Table structure for table 'eZImageCatalogue_Category'
#
CREATE TABLE eZImageCatalogue_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(100),
  Description text,
  ParentID int(11),
  UserID int(11),
  WritePermission int(11) DEFAULT '1',
  ReadPermission int(11) DEFAULT '1',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_Category'
#

INSERT INTO eZImageCatalogue_Category VALUES (1,'Images',' ',0,27,2,3);

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
  ReadPermission int(11) DEFAULT '1',
  WritePermission int(11) DEFAULT '1',
  UserID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_Image'
#

INSERT INTO eZImageCatalogue_Image VALUES (1,'','','','phpRtGOCL.jpg','DSCN1728.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (2,'','','','phpM0uJe4.jpg','DSCN1722.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (3,'','','','phpZzyrod.jpg','DSCN1760.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (4,'','','','php6o0PjV.jpg','DSCN1884.JPG',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (5,'A picture','A picture','A picture','phpXlv43s.jpg','DSCN1354.JPG',3,2,27);
INSERT INTO eZImageCatalogue_Image VALUES (6,'A picture','A picture','A picture','php7DBg1K.jpg','DSCN1728.JPG',3,2,27);
INSERT INTO eZImageCatalogue_Image VALUES (7,'Flower','Flower','A flower','phptpTEuZ.jpg','DSCN1722.JPG',3,1,27);

#
# Table structure for table 'eZImageCatalogue_ImageCategoryLink'
#
CREATE TABLE eZImageCatalogue_ImageCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  ImageID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZImageCatalogue_ImageCategoryLink'
#

INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (1,2,5);
INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (2,2,6);
INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (3,2,7);

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

INSERT INTO eZImageCatalogue_ImageVariation VALUES (1,1,1,'ezimagecatalogue/catalogue/variations/1-150x150.jpg',150,113);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (2,2,1,'ezimagecatalogue/catalogue/variations/2-150x150.jpg',150,113);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (3,1,2,'ezimagecatalogue/catalogue/variations/1-200x200.jpg',200,150);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (4,2,2,'ezimagecatalogue/catalogue/variations/2-200x200.jpg',200,150);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (5,1,3,'ezimagecatalogue/catalogue/variations/1-100x100.jpg',100,75);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (6,2,3,'ezimagecatalogue/catalogue/variations/2-100x100.jpg',100,75);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (7,3,1,'ezimagecatalogue/catalogue/variations/3-150x150.jpg',150,113);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (8,3,3,'ezimagecatalogue/catalogue/variations/3-100x100.jpg',100,75);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (9,3,4,'ezimagecatalogue/catalogue/variations/3-300x300.jpg',300,225);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (10,3,5,'ezimagecatalogue/catalogue/variations/3-35x35.jpg',35,26);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (11,3,6,'ezimagecatalogue/catalogue/variations/3-400x500.jpg',400,300);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (12,3,7,'ezimagecatalogue/catalogue/variations/3-240x200.jpg',240,180);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (13,3,8,'ezimagecatalogue/catalogue/variations/3-250x250.jpg',250,188);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (14,4,1,'ezimagecatalogue/catalogue/variations/4-150x150.jpg',150,113);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (15,4,3,'ezimagecatalogue/catalogue/variations/4-100x100.jpg',100,75);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (16,4,4,'ezimagecatalogue/catalogue/variations/4-300x300.jpg',300,225);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (17,4,6,'ezimagecatalogue/catalogue/variations/4-400x500.jpg',400,300);

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
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (2,200,200);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (3,100,100);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (4,300,300);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (5,35,35);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (6,400,500);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (7,240,200);
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (8,250,250);

#
# Table structure for table 'eZLink_Category'
#
CREATE TABLE eZLink_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11) DEFAULT '0',
  Name char(100),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_Category'
#

INSERT INTO eZLink_Category VALUES (1,0,'Cool links');
INSERT INTO eZLink_Category VALUES (2,0,'Not so cool links');

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

INSERT INTO eZLink_Link VALUES (1,'eZ systems as','Test!',1,'Test!',20010125160958,'Y','2001-01-25 16:09:58','ez.no');

#
# Table structure for table 'eZLink_LinkCategoryDefinition'
#
CREATE TABLE eZLink_LinkCategoryDefinition (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  LinkID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_LinkCategoryDefinition'
#

INSERT INTO eZLink_LinkCategoryDefinition VALUES (1,1,1);

#
# Table structure for table 'eZLink_LinkCategoryLink'
#
CREATE TABLE eZLink_LinkCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  LinkID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZLink_LinkCategoryLink'
#

INSERT INTO eZLink_LinkCategoryLink VALUES (1,1,1);

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

#
# Table structure for table 'eZNewsFeed_Category'
#
CREATE TABLE eZNewsFeed_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150) DEFAULT '' NOT NULL,
  Description text,
  ParentID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNewsFeed_Category'
#

INSERT INTO eZNewsFeed_Category VALUES (1,'News from freshmeat','',0);

#
# Table structure for table 'eZNewsFeed_News'
#
CREATE TABLE eZNewsFeed_News (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  IsPublished enum('true','false') DEFAULT 'false',
  PublishingDate timestamp(14),
  OriginalPublishingDate timestamp(14),
  Name varchar(150) DEFAULT '' NOT NULL,
  Intro text,
  KeyWords varchar(200),
  URL varchar(200),
  Origin varchar(150),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNewsFeed_News'
#


#
# Table structure for table 'eZNewsFeed_NewsCategoryLink'
#
CREATE TABLE eZNewsFeed_NewsCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  NewsID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNewsFeed_NewsCategoryLink'
#


#
# Table structure for table 'eZNewsFeed_SourceSite'
#
CREATE TABLE eZNewsFeed_SourceSite (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  URL char(250),
  Login char(30),
  Password char(30),
  CategoryID int(11) DEFAULT '0' NOT NULL,
  Name char(100),
  Decoder char(50),
  IsActive enum('true','false') DEFAULT 'false',
  AutoPublish int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZNewsFeed_SourceSite'
#

INSERT INTO eZNewsFeed_SourceSite VALUES (1,'http://freshmeat.net/backend/fm.rdf','','',1,'Freshmeat','','false',0);

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

INSERT INTO eZPoll_Vote VALUES (1,1,1,'10.0.2.13',0);

#
# Table structure for table 'eZSession_Preferences'
#
CREATE TABLE eZSession_Preferences (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  Name char(50),
  Value char(255),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZSession_Preferences'
#

INSERT INTO eZSession_Preferences VALUES (1,27,'EnabledModules','eZArticle;eZContact;eZTrade;eZForum;eZLink;eZPoll;eZAd;eZUser;eZStats;eZAddress;eZNewsFeed;eZTodo;eZBug');
INSERT INTO eZSession_Preferences VALUES (2,27,'eztrade_status','open');
INSERT INTO eZSession_Preferences VALUES (3,27,'ezcontact_status','open');

#
# Table structure for table 'eZSession_Session'
#
CREATE TABLE eZSession_Session (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Hash char(33),
  Created timestamp(14),
  LastAccessed timestamp(14),
  SecondLastAccessed timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZSession_Session'
#

INSERT INTO eZSession_Session VALUES (1,'6421a09e001abb1f8b2ce67936e71946',20010125212521,20010126091432,20010126091432);
INSERT INTO eZSession_Session VALUES (2,'a71c804c0fc4f97085f14265b16cb948',20010125212546,20010125212546,20010125212546);
INSERT INTO eZSession_Session VALUES (3,'189486b95adc3dc0eeb00963a02abe8c',20010125212616,20010125215303,20010125215303);
INSERT INTO eZSession_Session VALUES (4,'a8afdf0e4ae4d4237d7b3003bb6e49d8',20010125213648,20010126110614,20010126110614);
INSERT INTO eZSession_Session VALUES (5,'dbd76a026749edc5c8f7fe426922813e',20010126101231,20010126125534,20010126125533);
INSERT INTO eZSession_Session VALUES (6,'a2255621f559bae654368459f873eab7',20010126101313,20010126125510,20010126125510);
INSERT INTO eZSession_Session VALUES (7,'98c8e91477713c0cb45cf92e55e22436',20010126104942,20010126104942,20010126104942);
INSERT INTO eZSession_Session VALUES (8,'f4ff581928f3a90040a10c398bb9045b',20010126105005,20010126125207,20010126125207);
INSERT INTO eZSession_Session VALUES (9,'8291a062bd118b73d9efed6afb9a2ee9',20010126105706,20010126105715,20010126105715);
INSERT INTO eZSession_Session VALUES (10,'c51749d645a8ac656c3f4717abbcd6e0',20010126122734,20010126135408,20010126135408);
INSERT INTO eZSession_Session VALUES (11,'fa6dc7ba4cb590e75dbec36cee02fbad',20010126125223,20010126130804,20010126130804);
INSERT INTO eZSession_Session VALUES (12,'61d9ae1492d1804e263e767b459c9ce6',20010126134455,20010126135639,20010126135639);
INSERT INTO eZSession_Session VALUES (13,'0669603fe7004cc0d4923908f612ff16',20010126134532,20010126134532,20010126134532);
INSERT INTO eZSession_Session VALUES (14,'b7a7aedb0bb04194266f60ff3f9ba3fa',20010126135355,20010126140739,20010126140739);

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

INSERT INTO eZSession_SessionVariable VALUES (1,1,'SessionIP','10.0.2.9');
INSERT INTO eZSession_SessionVariable VALUES (2,2,'SessionIP','10.0.2.9');
INSERT INTO eZSession_SessionVariable VALUES (3,2,'AuthenticatedUser','27');
INSERT INTO eZSession_SessionVariable VALUES (4,3,'SessionIP','10.0.2.16');
INSERT INTO eZSession_SessionVariable VALUES (5,3,'AuthenticatedUser','27');
INSERT INTO eZSession_SessionVariable VALUES (6,4,'SessionIP','10.0.2.3');
INSERT INTO eZSession_SessionVariable VALUES (7,4,'SiteDesign','intranet');
INSERT INTO eZSession_SessionVariable VALUES (8,4,'AuthenticatedUser','27');
INSERT INTO eZSession_SessionVariable VALUES (9,4,'Bla','ikkeno');
INSERT INTO eZSession_SessionVariable VALUES (10,4,'ShowOtherCalenderUsers','27');
INSERT INTO eZSession_SessionVariable VALUES (11,4,'Year','2001');
INSERT INTO eZSession_SessionVariable VALUES (12,4,'Month','1');
INSERT INTO eZSession_SessionVariable VALUES (13,1,'SiteDesign','intranet');
INSERT INTO eZSession_SessionVariable VALUES (14,1,'Bla','ikkeno');
INSERT INTO eZSession_SessionVariable VALUES (15,5,'SessionIP','10.0.2.16');
INSERT INTO eZSession_SessionVariable VALUES (16,6,'SessionIP','10.0.2.16');
INSERT INTO eZSession_SessionVariable VALUES (17,6,'AuthenticatedUser','27');
INSERT INTO eZSession_SessionVariable VALUES (18,7,'SessionIP','10.0.2.2');
INSERT INTO eZSession_SessionVariable VALUES (19,5,'SiteDesign','intranet');
INSERT INTO eZSession_SessionVariable VALUES (20,8,'SessionIP','10.0.2.2');
INSERT INTO eZSession_SessionVariable VALUES (21,8,'AuthenticatedUser','27');
INSERT INTO eZSession_SessionVariable VALUES (22,9,'SessionIP','10.0.2.9');
INSERT INTO eZSession_SessionVariable VALUES (23,9,'SiteDesign','standard');
INSERT INTO eZSession_SessionVariable VALUES (24,5,'Bla','ikkeno');
INSERT INTO eZSession_SessionVariable VALUES (25,10,'SessionIP','10.0.2.3');
INSERT INTO eZSession_SessionVariable VALUES (26,10,'AuthenticatedUser','27');
INSERT INTO eZSession_SessionVariable VALUES (27,11,'SessionIP','10.0.2.9');
INSERT INTO eZSession_SessionVariable VALUES (28,11,'ShowOtherCalenderUsers','');
INSERT INTO eZSession_SessionVariable VALUES (29,11,'Year','2001');
INSERT INTO eZSession_SessionVariable VALUES (30,11,'Month','01');
INSERT INTO eZSession_SessionVariable VALUES (31,11,'Day','26');
INSERT INTO eZSession_SessionVariable VALUES (32,11,'SiteDesign','trade');
INSERT INTO eZSession_SessionVariable VALUES (33,11,'Bla','ikkeno');
INSERT INTO eZSession_SessionVariable VALUES (34,10,'SiteDesign','trade');
INSERT INTO eZSession_SessionVariable VALUES (35,10,'Bla','ikkeno');
INSERT INTO eZSession_SessionVariable VALUES (36,10,'ShowOtherCalenderUsers','27');
INSERT INTO eZSession_SessionVariable VALUES (37,10,'Year','2001');
INSERT INTO eZSession_SessionVariable VALUES (38,10,'Month','1');
INSERT INTO eZSession_SessionVariable VALUES (39,12,'SessionIP','10.0.2.9');
INSERT INTO eZSession_SessionVariable VALUES (40,13,'SessionIP','10.0.2.9');
INSERT INTO eZSession_SessionVariable VALUES (41,13,'AuthenticatedUser','27');
INSERT INTO eZSession_SessionVariable VALUES (42,12,'AuthenticatedUser','27');
INSERT INTO eZSession_SessionVariable VALUES (43,10,'Day','26');
INSERT INTO eZSession_SessionVariable VALUES (44,14,'SessionIP','10.0.2.16');
INSERT INTO eZSession_SessionVariable VALUES (45,14,'AuthenticatedUser','27');

#
# Table structure for table 'eZStats_BrowserType'
#
CREATE TABLE eZStats_BrowserType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  BrowserType char(250) DEFAULT '' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_BrowserType'
#


#
# Table structure for table 'eZStats_PageView'
#
CREATE TABLE eZStats_PageView (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  BrowserTypeID int(11) DEFAULT '0' NOT NULL,
  RemoteHostID int(11) DEFAULT '0' NOT NULL,
  RefererURLID int(11) DEFAULT '0' NOT NULL,
  Date timestamp(14),
  RequestPageID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_PageView'
#


#
# Table structure for table 'eZStats_RefererURL'
#
CREATE TABLE eZStats_RefererURL (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Domain char(100),
  URI char(200),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_RefererURL'
#


#
# Table structure for table 'eZStats_RemoteHost'
#
CREATE TABLE eZStats_RemoteHost (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  IP char(15),
  HostName char(150),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_RemoteHost'
#


#
# Table structure for table 'eZStats_RequestPage'
#
CREATE TABLE eZStats_RequestPage (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  URI char(250),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZStats_RequestPage'
#


#
# Table structure for table 'eZTodo_Category'
#
CREATE TABLE eZTodo_Category (
  Description text,
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(30),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTodo_Category'
#

INSERT INTO eZTodo_Category VALUES (NULL,1,'Bugfix');
INSERT INTO eZTodo_Category VALUES (NULL,2,'Programming');

#
# Table structure for table 'eZTodo_Priority'
#
CREATE TABLE eZTodo_Priority (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(30),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTodo_Priority'
#

INSERT INTO eZTodo_Priority VALUES (1,'Low');
INSERT INTO eZTodo_Priority VALUES (2,'Medium');
INSERT INTO eZTodo_Priority VALUES (3,'High');

#
# Table structure for table 'eZTodo_Todo'
#
CREATE TABLE eZTodo_Todo (
  Category int(11),
  Priority int(11),
  Permission enum('Public','Private') DEFAULT 'Private',
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  OwnerID int(11),
  Name varchar(30),
  Date timestamp(14),
  Due timestamp(14),
  Description text,
  Status int(11) DEFAULT '0',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTodo_Todo'
#

INSERT INTO eZTodo_Todo VALUES (2,1,'Private',1,27,27,'This is a test Todo',20010116142211,00000000000000,'Please add this feature.',1);

#
# Table structure for table 'eZTrade_Attribute'
#
CREATE TABLE eZTrade_Attribute (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  TypeID int(11),
  Name char(150),
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Attribute'
#

INSERT INTO eZTrade_Attribute VALUES (1,1,'Size',20010126130441);
INSERT INTO eZTrade_Attribute VALUES (2,1,'Color',20010126130449);
INSERT INTO eZTrade_Attribute VALUES (3,1,'Age',20010126130455);
INSERT INTO eZTrade_Attribute VALUES (4,1,'Gender',20010126130459);

#
# Table structure for table 'eZTrade_AttributeValue'
#
CREATE TABLE eZTrade_AttributeValue (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  AttributeID int(11),
  Value char(200),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_AttributeValue'
#

INSERT INTO eZTrade_AttributeValue VALUES (1,2,1,'Big');
INSERT INTO eZTrade_AttributeValue VALUES (2,2,2,'Red');
INSERT INTO eZTrade_AttributeValue VALUES (3,2,3,'12 yrs');
INSERT INTO eZTrade_AttributeValue VALUES (4,2,4,'Female');

#
# Table structure for table 'eZTrade_Cart'
#
CREATE TABLE eZTrade_Cart (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  SessionID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Cart'
#

INSERT INTO eZTrade_Cart VALUES (3,4);
INSERT INTO eZTrade_Cart VALUES (2,1);
INSERT INTO eZTrade_Cart VALUES (4,5);
INSERT INTO eZTrade_Cart VALUES (5,11);
INSERT INTO eZTrade_Cart VALUES (6,10);

#
# Table structure for table 'eZTrade_CartItem'
#
CREATE TABLE eZTrade_CartItem (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  Count int(11),
  CartID int(11),
  WishListItemID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_CartItem'
#


#
# Table structure for table 'eZTrade_CartOptionValue'
#
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
CREATE TABLE eZTrade_Category (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Parent int(11),
  Description text,
  Name varchar(100),
  ImageID int(11),
  SortMode int(11) DEFAULT '1' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Category'
#

INSERT INTO eZTrade_Category VALUES (1,0,'','Products',NULL,1);

#
# Table structure for table 'eZTrade_CategoryOptionLink'
#
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
CREATE TABLE eZTrade_Order (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  ShippingCharge float(10,2),
  PaymentMethod text,
  ShippingAddressID int(11),
  BillingAddressID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Order'
#

INSERT INTO eZTrade_Order VALUES (1,27,50.00,'1',1,1);

#
# Table structure for table 'eZTrade_OrderItem'
#
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

INSERT INTO eZTrade_OrderItem VALUES (1,1,1,142.00,1);

#
# Table structure for table 'eZTrade_OrderOptionValue'
#
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

INSERT INTO eZTrade_OrderStatus VALUES (1,1,20010126102943,0,1,'');

#
# Table structure for table 'eZTrade_OrderStatusType'
#
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
  IsHotDeal enum('true','false') DEFAULT 'false',
  Published timestamp(14),
  Altered timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Product'
#

INSERT INTO eZTrade_Product VALUES (1,'Cat','Test product','Please buy this product','',142.00,'true','true','false',NULL,'','','true',20010126102820,00000000000000);
INSERT INTO eZTrade_Product VALUES (2,'Flower','This is a flower','Description','',42.00,'true','true','false',NULL,'','www.ez.no','true',20010126130741,00000000000000);

#
# Table structure for table 'eZTrade_ProductCategoryDefinition'
#
CREATE TABLE eZTrade_ProductCategoryDefinition (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11) DEFAULT '0' NOT NULL,
  CategoryID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductCategoryDefinition'
#

INSERT INTO eZTrade_ProductCategoryDefinition VALUES (1,1,1);
INSERT INTO eZTrade_ProductCategoryDefinition VALUES (2,2,1);

#
# Table structure for table 'eZTrade_ProductCategoryLink'
#
CREATE TABLE eZTrade_ProductCategoryLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  CategoryID int(11),
  ProductID int(11),
  Placement int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductCategoryLink'
#

INSERT INTO eZTrade_ProductCategoryLink VALUES (1,1,1,0);
INSERT INTO eZTrade_ProductCategoryLink VALUES (2,1,2,0);

#
# Table structure for table 'eZTrade_ProductImageDefinition'
#
CREATE TABLE eZTrade_ProductImageDefinition (
  ProductID int(11) DEFAULT '0' NOT NULL,
  ThumbnailImageID int(11),
  MainImageID int(11),
  PRIMARY KEY (ProductID)
);

#
# Dumping data for table 'eZTrade_ProductImageDefinition'
#

INSERT INTO eZTrade_ProductImageDefinition VALUES (1,3,3);
INSERT INTO eZTrade_ProductImageDefinition VALUES (2,4,4);

#
# Table structure for table 'eZTrade_ProductImageLink'
#
CREATE TABLE eZTrade_ProductImageLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  ImageID int(11),
  Created timestamp(14),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductImageLink'
#

INSERT INTO eZTrade_ProductImageLink VALUES (1,1,3,20010126102759);
INSERT INTO eZTrade_ProductImageLink VALUES (2,2,4,20010126130705);

#
# Table structure for table 'eZTrade_ProductOptionLink'
#
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
# Table structure for table 'eZTrade_ProductTypeLink'
#
CREATE TABLE eZTrade_ProductTypeLink (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  TypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_ProductTypeLink'
#

INSERT INTO eZTrade_ProductTypeLink VALUES (1,2,1);

#
# Table structure for table 'eZTrade_Type'
#
CREATE TABLE eZTrade_Type (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(150),
  Description text,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_Type'
#

INSERT INTO eZTrade_Type VALUES (1,'Flower','');

#
# Table structure for table 'eZTrade_WishList'
#
CREATE TABLE eZTrade_WishList (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  IsPublic int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_WishList'
#

INSERT INTO eZTrade_WishList VALUES (1,27,0);

#
# Table structure for table 'eZTrade_WishListItem'
#
CREATE TABLE eZTrade_WishListItem (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ProductID int(11),
  Count int(11),
  WishListID int(11),
  IsBought int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTrade_WishListItem'
#

INSERT INTO eZTrade_WishListItem VALUES (1,1,1,1,0);

#
# Table structure for table 'eZTrade_WishListOptionValue'
#
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
  SessionTimeout int(11) DEFAULT '60',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_Group'
#

INSERT INTO eZUser_Group VALUES (2,'Anonymous','Users that register themself on the user page, eg forum users.',60);
INSERT INTO eZUser_Group VALUES (1,'Administrators','All rights',7200);

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
INSERT INTO eZUser_GroupPermissionLink VALUES (30,1,24,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (31,1,25,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (32,1,26,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (33,1,27,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (34,1,28,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (35,1,29,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (36,1,30,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (37,1,31,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (38,1,32,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (39,1,33,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (40,1,34,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (74,1,35,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (75,1,36,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (76,1,37,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (77,1,38,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (78,1,39,'true');
INSERT INTO eZUser_GroupPermissionLink VALUES (79,1,40,'true');

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
INSERT INTO eZUser_Module VALUES (9,'eZFileManager');
INSERT INTO eZUser_Module VALUES (10,'eZImageCatalogue');

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
INSERT INTO eZUser_Permission VALUES (24,6,'PersonAdd');
INSERT INTO eZUser_Permission VALUES (25,6,'CompanyAdd');
INSERT INTO eZUser_Permission VALUES (26,6,'TypeAdd');
INSERT INTO eZUser_Permission VALUES (27,6,'PersonDelete');
INSERT INTO eZUser_Permission VALUES (28,6,'CompanyDelete');
INSERT INTO eZUser_Permission VALUES (29,6,'TypeDelete');
INSERT INTO eZUser_Permission VALUES (30,6,'PersonModify');
INSERT INTO eZUser_Permission VALUES (31,6,'CompanyModify');
INSERT INTO eZUser_Permission VALUES (32,6,'TypeModify');
INSERT INTO eZUser_Permission VALUES (33,6,'PersonView');
INSERT INTO eZUser_Permission VALUES (34,6,'PersonList');
INSERT INTO eZUser_Permission VALUES (35,3,'UserLogin');
INSERT INTO eZUser_Permission VALUES (36,9,'WriteToRoot');
INSERT INTO eZUser_Permission VALUES (37,9,'WritePermission');
INSERT INTO eZUser_Permission VALUES (38,10,'WritePermission');
INSERT INTO eZUser_Permission VALUES (39,10,'WriteToRoot');

#
# Table structure for table 'eZUser_User'
#
CREATE TABLE eZUser_User (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Login varchar(50) DEFAULT '' NOT NULL,
  Password varchar(50) DEFAULT '' NOT NULL,
  Email varchar(50),
  FirstName varchar(50),
  LastName varchar(50),
  InfoSubscription enum('true','false') DEFAULT 'false',
  Signature text NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE Login (Login)
);

#
# Dumping data for table 'eZUser_User'
#

INSERT INTO eZUser_User VALUES (27,'admin','0c947f956f7aa781','bf@ez.no','admin','user','false','');

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

INSERT INTO eZUser_UserAddressLink VALUES (1,27,1);

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

INSERT INTO eZUser_UserGroupLink VALUES (52,27,1);

