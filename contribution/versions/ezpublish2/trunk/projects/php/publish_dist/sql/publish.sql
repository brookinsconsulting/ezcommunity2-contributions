# MySQL dump 8.12
#
# Host: localhost    Database: trade
#--------------------------------------------------------
# Server version	3.23.32

#
# Table structure for table 'eZAd_Ad'
#

DROP TABLE IF EXISTS eZAd_Ad;
CREATE TABLE eZAd_Ad (
  ID int(11) NOT NULL auto_increment,
  Name varchar(150) default NULL,
  ImageID int(11) default NULL,
  ViewStartDate timestamp(14) NOT NULL,
  ViewStopDate timestamp(14) NOT NULL,
  ViewRule enum('Period','Click') default 'Click',
  URL varchar(200) default NULL,
  Description text,
  IsActive enum('true','false') default NULL,
  ViewPrice float(10,2) default NULL,
  ClickPrice float(10,2) default NULL,
  HTMLBanner text NOT NULL,
  UseHTML int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAd_Ad'
#

INSERT INTO eZAd_Ad VALUES (1,'',8,00000000000000,00000000000000,'','http://ez.no','eZ systems','true',1.00,1.00,'',0);
INSERT INTO eZAd_Ad VALUES (2,'eZ publish',9,00000000000000,00000000000000,'','http://developer.ez.no','eZ publish banner','true',1.00,1.00,'',0);
INSERT INTO eZAd_Ad VALUES (3,'eZ publish anim',10,00000000000000,00000000000000,'','http://developer.ez.no','eZ publish animated banner','true',1.00,1.00,'',0);

#
# Table structure for table 'eZAd_AdCategoryLink'
#

DROP TABLE IF EXISTS eZAd_AdCategoryLink;
CREATE TABLE eZAd_AdCategoryLink (
  ID int(11) NOT NULL auto_increment,
  CategoryID int(11) default NULL,
  AdID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAd_AdCategoryLink'
#

INSERT INTO eZAd_AdCategoryLink VALUES (1,1,1);
INSERT INTO eZAd_AdCategoryLink VALUES (2,1,2);
INSERT INTO eZAd_AdCategoryLink VALUES (3,1,3);

#
# Table structure for table 'eZAd_Category'
#

DROP TABLE IF EXISTS eZAd_Category;
CREATE TABLE eZAd_Category (
  ID int(11) NOT NULL auto_increment,
  Name varchar(150) default NULL,
  Description text,
  ParentID int(11) default NULL,
  ExcludeFromSearch enum('true','false') default 'false',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAd_Category'
#

INSERT INTO eZAd_Category VALUES (1,'ez ads','Banner ads for eZ systems',0,'false');

#
# Table structure for table 'eZAd_Click'
#

DROP TABLE IF EXISTS eZAd_Click;
CREATE TABLE eZAd_Click (
  ID int(11) NOT NULL auto_increment,
  AdID int(11) default NULL,
  PageViewID int(11) default NULL,
  ClickPrice float(10,2) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAd_Click'
#


#
# Table structure for table 'eZAd_View'
#

DROP TABLE IF EXISTS eZAd_View;
CREATE TABLE eZAd_View (
  ID int(11) NOT NULL auto_increment,
  AdID int(11) default NULL,
  Date date default NULL,
  ViewCount int(11) NOT NULL default '0',
  ViewPrice int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAd_View'
#

INSERT INTO eZAd_View VALUES (1,1,'2001-02-13',83,83);
INSERT INTO eZAd_View VALUES (2,2,'2001-02-13',83,83);
INSERT INTO eZAd_View VALUES (3,3,'2001-02-13',83,83);

#
# Table structure for table 'eZAddress_Address'
#

DROP TABLE IF EXISTS eZAddress_Address;
CREATE TABLE eZAddress_Address (
  ID int(11) NOT NULL auto_increment,
  Street1 char(50) default NULL,
  Street2 char(50) default NULL,
  AddressTypeID int(11) default NULL,
  Place char(50) default NULL,
  Zip char(10) default NULL,
  CountryID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAddress_Address'
#

INSERT INTO eZAddress_Address VALUES (1,'Adminstreet1','Adminstreet2',0,'Noplace','42',0);

#
# Table structure for table 'eZAddress_AddressDefinition'
#

DROP TABLE IF EXISTS eZAddress_AddressDefinition;
CREATE TABLE eZAddress_AddressDefinition (
  UserID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  PRIMARY KEY (UserID,AddressID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAddress_AddressDefinition'
#


#
# Table structure for table 'eZAddress_AddressType'
#

DROP TABLE IF EXISTS eZAddress_AddressType;
CREATE TABLE eZAddress_AddressType (
  ID int(11) NOT NULL auto_increment,
  Name char(50) default NULL,
  ListOrder int(11) NOT NULL default '0',
  Removed int(1) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAddress_AddressType'
#

INSERT INTO eZAddress_AddressType VALUES (1,'Post address',1,0);

#
# Table structure for table 'eZAddress_Country'
#

DROP TABLE IF EXISTS eZAddress_Country;
CREATE TABLE eZAddress_Country (
  ID int(11) NOT NULL auto_increment,
  ISO char(2) default NULL,
  Name char(100) default NULL,
  Removed int(1) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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

DROP TABLE IF EXISTS eZAddress_Online;
CREATE TABLE eZAddress_Online (
  ID int(11) NOT NULL auto_increment,
  URL char(255) default NULL,
  OnlineTypeID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAddress_Online'
#


#
# Table structure for table 'eZAddress_OnlineType'
#

DROP TABLE IF EXISTS eZAddress_OnlineType;
CREATE TABLE eZAddress_OnlineType (
  ID int(11) NOT NULL auto_increment,
  Name varchar(50) default NULL,
  ListOrder int(11) NOT NULL default '0',
  URLPrefix varchar(30) NOT NULL default '',
  PrefixLink int(1) NOT NULL default '0',
  PrefixVisual int(1) NOT NULL default '0',
  Removed int(1) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAddress_OnlineType'
#

INSERT INTO eZAddress_OnlineType VALUES (1,'Email',1,'mailto:',1,0,0);

#
# Table structure for table 'eZAddress_Phone'
#

DROP TABLE IF EXISTS eZAddress_Phone;
CREATE TABLE eZAddress_Phone (
  ID int(11) NOT NULL auto_increment,
  Number varchar(22) default NULL,
  PhoneTypeID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAddress_Phone'
#


#
# Table structure for table 'eZAddress_PhoneType'
#

DROP TABLE IF EXISTS eZAddress_PhoneType;
CREATE TABLE eZAddress_PhoneType (
  ID int(11) NOT NULL auto_increment,
  Name char(50) default NULL,
  ListOrder int(11) NOT NULL default '0',
  Removed int(1) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZAddress_PhoneType'
#

INSERT INTO eZAddress_PhoneType VALUES (1,'Phone',1,0);

#
# Table structure for table 'eZArticle_Article'
#

DROP TABLE IF EXISTS eZArticle_Article;
CREATE TABLE eZArticle_Article (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Contents text,
  AuthorText varchar(100) default NULL,
  LinkText varchar(50) default NULL,
  AuthorID int(11) NOT NULL default '0',
  Modified timestamp(14) NOT NULL,
  Created timestamp(14) NOT NULL,
  PageCount int(11) default NULL,
  IsPublished enum('true','false') default 'false',
  Published timestamp(14) NOT NULL,
  Keywords text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_Article'
#

INSERT INTO eZArticle_Article VALUES (1,'Demo article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This article will show the tags you can use in eZ publish.</intro><body><page><header>Standard tags</header>\r\n\r\nThis is <bold>bold</bold> text.\r\nThis is <strike>strike</strike> text.\r\nThis is <underline>underline</underline> text.\r\n\r\n<pre>\r\nPre defined text\r\n  indented\r\n    as \r\n      written.\r\n</pre>\r\n<bullet>\r\nItem one\r\nItem two\r\nItem three\r\n</bullet>\r\n\r\n<header>Image tags</header>\r\n\r\n<image id=\"1\" align=\"left\" size=\"medium\" /> Fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text fill text.\r\n\r\n<image id=\"2\" align=\"center\" size=\"medium\" />\r\n\r\nImages on a row\r\n\r\n<image id=\"1\" align=\"float\" size=\"small\" /> <image id=\"2\" align=\"float\" size=\"small\" /> <image id=\"1\" align=\"float\" size=\"small\" /> <image id=\"2\" align=\"float\" size=\"small\" /></page></body></article>','admin user','See demo',27,20010213161330,20010126100350,1,'true',20010126100350,'tech\nThis article will show the tags you can use in eZ publish.Standard tags\r\n\r\nThis is bold text.\r\nThis strike underline text.\r\n\r\n\r\nPre defined text\r\n  indented\r\n as \r\n written.\r\n\r\n\r\nItem one\r\nItem two\r\nItem three\r\n\r\n\r\nImage tags\r\n\r\n Fill text fill text.\r\n\r\n\r\n\r\nImages on a row\r\n\r\n ');
INSERT INTO eZArticle_Article VALUES (5,'What is New in 2.0?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>This is a major new release of eZ publish, we\'ve added lots of new information.</intro><body><page><bullet>Merged eZ trade with eZ publish\r\nAdded about module\r\nAdded ad module\r\nAdded address module\r\nAdded bug tracking module\r\nAdded calendar module\r\nAdded contact module\r\nAdded newsfeed module\r\nAdded statistics module\r\nAdded todo module\r\nAdded cookie-less sessions \r\nAdded absolute positioning of products and articles\r\nAdded choosable sort mode on article categories\r\nAdded choosable sort mode on product categories\r\nAdded previous/next paging of article lists (admin &amp; user )\r\nAdded previous/next paging of product lists (admin &amp; user )\r\nAdded RSS headlines\r\nAdded forum moderation with assignment of moderator\r\nAdded notification when articles are published\r\nAdded file uploads on articles.\r\nAdded dynamically updating of menues with static pages.\r\nAdded file upload to eZ article\r\nAdded word wrap of message replies in eZ forum. Nicer looking replies.\r\nAdded new tags in articles (bullet lists/includes of php files)\r\nAdded preferred layout for users\r\nMade the menus in the admin module expandable/collapsable as well as moveable. This is remembered by the preferences for each user. We\'ve also changed the design to a more sleek version.\r\nLanguage updates\r\nRemoved java script which were a problem for lynx users.\r\nRemoved strip tags from messages in eZ forum\r\nSpeeded up many features among them database connections, localisation, rendering of articles, templates and HTML.\r\nFixed bugs</bullet>\r\n\r\n\r\nRead on to learn how to use some of the new features.\r\n</page><page>\r\n<header>RSS Headlines</header>\r\nYou can access the RSS Headlines of eZ publish from the URL \"/article/rssheadlines\".\r\n\r\nIn \"site.ini\" you can configure some of its options; read more in the \"eZ article Admin\'s Guide\" and \"eZ publish Customisation Guide\".\r\n\r\n<header>About</header>\r\nIf you write in the URL \"/about\" you\'ll be presented with an about box for eZ publish.\r\n\r\n<header>User Preferences</header>\r\nWe\'ve added preference functionality. If you take a look into the left hand column of this site you\'ll find some links which are called \"intranet\", \"portal site\" and \"E-commerce\". Those links take you to different designs for eZ publish (only two links will be shown at any time).\r\n\r\nAn example of its usage might be to give users the option of reading your site with different amounts of graphics, or different text sizes.\r\n\r\n<header>Cookie-less Sessions</header>\r\nWe\'ve added cookie-less sessions.\r\n\r\n<header>Moderated Forums</header>\r\nWe\'ve added the much requested moderation functionality to forums. Now you can assign a moderator to each and every forum.\r\n\r\nUsage for this function might, in addition to plain old moderation, is to protect forums so that you can use them as an FAQ.</page></body></article>','admin user','Read the changelog...',27,20010126112508,20010126102640,2,'true',20010126102640,'tech\nThis is a major new release of eZ publish, we\'ve added lots information.Merged trade with publish\r\nAdded about module\r\nAdded ad address bug tracking calendar contact newsfeed statistics todo cookie-less sessions \r\nAdded absolute positioning products and articles\r\nAdded choosable sort mode on article categories\r\nAdded product previous/next paging lists (admin &amp; user )\r\nAdded RSS headlines\r\nAdded forum moderation assignment moderator\r\nAdded notification when articles are published\r\nAdded file uploads articles.\r\nAdded dynamically updating menues static pages.\r\nAdded upload to article\r\nAdded word wrap message replies in forum. Nicer looking replies.\r\nAdded tags (bullet lists/includes php files)\r\nAdded preferred layout for users\r\nMade the menus admin module expandable/collapsable as well moveable. This remembered by preferences each user. We\'ve also changed design more sleek version.\r\nLanguage updates\r\nRemoved java script which were problem lynx users.\r\nRemoved strip from messages forum\r\nSpeeded up many features among them database connections, localisation, rendering articles, templates HTML.\r\nFixed bugs\r\n\r\n\r\nRead learn how use some features.\r\n\r\nRSS Headlines\r\nYou can access Headlines publish URL \"/article/rssheadlines\".\r\n\r\nIn \"site.ini\" you configure its options; read \"eZ Admin\'s Guide\" Customisation Guide\".\r\n\r\nAbout\r\nIf write \"/about\" you\'ll be presented an box publish.\r\n\r\nUser Preferences\r\nWe\'ve preference functionality. If take look into left hand column this site find links called \"intranet\", \"portal site\" \"E-commerce\". Those different designs (only two will shown at any time).\r\n\r\nAn example usage might give users option reading your amounts graphics, or text sizes.\r\n\r\nCookie-less Sessions\r\nWe\'ve sessions.\r\n\r\nModerated Forums\r\nWe\'ve much requested functionality forums. Now assign moderator every forum.\r\n\r\nUsage function might, addition plain old moderation, protect forums so that FAQ. ');
INSERT INTO eZArticle_Article VALUES (4,'How does static pages work?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>Static pages are articles entered into the normal article system, but which belong to a category which has specific settings.</intro><body><page>All categories can have that special setting, which is called \"Exclude from search\". Not only does this disallow normal search functions, but those articles will not be shown in normal archive listings, nor latest article listings or the rss headlines.\r\n\r\nYou will neither see the name of the author of a static page; it is anonymous to the reader.\r\n\r\n<header>Intended Usage</header>\r\n\r\nThe usage of such pages is intended to create copyright notices, address info and other static information; thus the name.\r\n\r\nThat doesn\'t exclude any or all other methods you would like to use, but this is how we use it.\r\n\r\n<header>Tricks of the Trade</header>\r\n\r\nA category listing for static pages might be used on the front page; when you add a new page it will be added to the menu.\r\n\r\nBy changing the category sort method to \"Absolute positioning\" you can order the rendering of the menu to suit your desires.\r\n\r\nYou could also create several static page groups, and use those to good effect to distinguish information.\r\n\r\nFor all other intents and purposes articles written as static pages are the same as normal articles.</page></body></article>','admin user','',27,20010126102509,20010126101612,1,'true',20010126101612,'tech\nStatic pages are articles entered into the normal article system, but which belong to a category has specific settings.All categories can have that special setting, is called \"Exclude from search\". Not only does this disallow search functions, those will not be shown in archive listings, nor latest listings or rss headlines.\r\n\r\nYou neither see name of author static page; it anonymous reader.\r\n\r\nIntended Usage\r\n\r\nThe usage such intended create copyright notices, address info and other information; thus name.\r\n\r\nThat doesn\'t exclude any all methods you would like use, how we use it.\r\n\r\nTricks Trade\r\n\r\nA listing for might used on front when add new page added menu.\r\n\r\nBy changing sort method \"Absolute positioning\" order rendering menu suit your desires.\r\n\r\nYou could also several groups, good effect distinguish information.\r\n\r\nFor intents purposes written as same articles. ');
INSERT INTO eZArticle_Article VALUES (8,'eZ Trade','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ Trade has had a couple of face lifts as well.</intro><body><page><header>Category Sorting</header>\r\nYou can set the sorting methods of both article and trade categories.\r\n\r\nAmong other sorting methods we\'ve added absolute positioning. This feature might be good for presenting a front page of your site where certain items appear at specific places.\r\n\r\nBy \"cross posting\" news and products to both their main category and the category with absolute positioning you can have items appear on the front page at a certain position within the time limit you want.\r\n\r\n<header>Types</header>\r\nYou can define a product type, where you set what kind of information that type requires. Then when creating a product you can set the type of the product and enter the required data.\r\n\r\nLooks great and can be used for comparision of features.\r\n\r\nCombine this with options for your products to create really compelling product pages.\r\n</page></body></article>','admin user','',27,20010126120506,20010126112654,1,'true',20010126112654,'tech\neZ Trade has had a couple of face lifts as well.Category Sorting\r\nYou can set the sorting methods both article and trade categories.\r\n\r\nAmong other we\'ve added absolute positioning. This feature might be good for presenting front page your site where certain items appear at specific places.\r\n\r\nBy \"cross posting\" news products to their main category with positioning you have on position within time limit want.\r\n\r\nTypes\r\nYou define product type, what kind information that type requires. Then when creating enter required data.\r\n\r\nLooks great used comparision features.\r\n\r\nCombine this options create really compelling pages.\r\n ');
INSERT INTO eZArticle_Article VALUES (6,'eZ Newsfeed','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ newsfeed is a new module from 2.0. It fetches RSS headlines from other sites.</intro><body><page>The news feed is a module which fetches headlines from RSS enabled sites, pluss a couple of speciality sites.\r\n\r\nFetching RSS headlines is easy, just point eZ publish to the URL you want, and it will fetch the info into a queue. You can then select which items you want to publish from that queue.\r\n\r\nIt is possible to create your own fetch methods which fetches headlines from other sites. PHP programming required.</page></body></article>','admin user','',27,20010126112345,20010126111844,1,'true',20010126111844,'tech\neZ newsfeed is a new module from 2.0. It fetches RSS headlines other sites.The news feed which enabled sites, pluss couple of speciality sites.\r\n\r\nFetching easy, just point eZ publish to the URL you want, and it will fetch info into queue. You can then select items want that queue.\r\n\r\nIt possible create your own methods sites. PHP programming required. ');
INSERT INTO eZArticle_Article VALUES (7,'eZ Article','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>Some additions were made to eZ article, the main points are presented here.</intro><body><page><header>Article Comments</header>\r\nWhen readers comment on an article eZ publish will now send an e-mail to the user who published the article.\r\n\r\n<header>File Attachments</header>\r\nAs you can see from this article it is now possible to add files to an article; thus you can use the article for distributing files.\r\n\r\n<header>Category Sorting</header>\r\nYou can set the sorting methods of article categories.\r\n\r\nAmong other sorting methods we\'ve added absolute positioning. This feature might be good for presenting a front page of your site where certain items appear at specific places\r\n\r\n<header>Include Generated Content</header>\r\neZ Article now accepts a tag called module it takes a second argument, a file name, sans extension. The extension is assumed to be .php.\r\n\r\nThe article will parse and include that file from \"ezarticle/modules\". Thus you can create much fancier lay outs than what you\'d normally get from the standard renderer.</page></body></article>','admin user','',27,20010126125446,20010126112242,1,'true',20010126112242,'tech\nSome additions were made to eZ article, the main points are presented here.Article Comments\r\nWhen readers comment on an article publish will now send e-mail user who published article.\r\n\r\nFile Attachments\r\nAs you can see from this it is possible add files article; thus use for distributing files.\r\n\r\nCategory Sorting\r\nYou set sorting methods of categories.\r\n\r\nAmong other we\'ve added absolute positioning. This feature might be good presenting a front page your site where certain items appear at specific places\r\n\r\nInclude Generated Content\r\neZ Article accepts tag called module takes second argument, file name, sans extension. The extension assumed .php.\r\n\r\nThe parse and include that \"ezarticle/modules\". Thus create much fancier lay outs than what you\'d normally get standard renderer. ');
INSERT INTO eZArticle_Article VALUES (9,'What can eZ publish Do?','<?xml version=\"1.0\"?><article><generator>tech</generator>\n<intro>eZ publish is a web based application suite. It delivers functionality ranging from publishing of news, web logs and diaries, through web shop functionality like shopping carts and wishlists and forums to intranet functions like contact handling and bug reporting.\r\n\r\nThe software uses caching and other optimization techniques to speed up page serving. It handles users, user preferences and user tracking through a user database and both cookie-based and non-cookie sessions.\r\n\r\nIt supports statistics for page views, links followed and banner ads, both images and HTML with presentation logic.\r\n\r\nThe package lends itself easily to customization, from changing the look and feel by changing templates, localizing the languages and other internationalization issues to adding new functionality.\r\n\r\nThe target audience for eZ publish are e-commerce, ASP (Application Service Providers), BSP (Business Service Providers), news publishing, intranets, bug reporting, content management, discussion boards, FAQ and knowledge handling, file and image management, group ware, calendaring, polls, todo lists, appointments as well as personal web sites.\r\n</intro><body><page>eZ publish is a web based application suite which delivers the following functionality:\r\n\r\n<bullet>Advertising with statistics\r\nArticle publication and management\r\nBug handling and reporting\r\nCalendar functionality for creating appointments and events\r\nContact handling for keeping track of people and businesses\r\nFile manager for keeping track of uploaded files\r\nModerated forums for discussions\r\nImage manager for keeping track of uploaded images\r\nLink manager which is used to categorize links\r\nNews feed importing, fetch news and headlines from other sites and incorporate them in your own(1)\r\nPoll module for creating user polls.\r\nSession module for keeping track of users and their preferences\r\nStatistics module for information about page views and visitors\r\nTo-do module for assigning tasks to people\r\nTrade module which is an online shop, with shopping cart and wishlist\r\nUser management for registering users, giving access to different groups to different parts of the site</bullet>\r\n\r\nThe software does not believe in limits(2):\r\n\r\n<bullet>No limits on categories and items in categories\r\nArticles, products and links might belong to several different categories\r\nNo limits on people associated with a company, or the number of people and companies registered totally\r\nNo limits of addresses, phone numbers and other contact points for people and businesses\r\nNo limits on users, the groups they might belong to and number of user groups</bullet>\r\n      \r\nBased on PHP, the leading programming language module for the Apache web server software, eZ publish draws on the speed from this renown software. The backend database is MySQL which is fast and reliable, proven on thousands of Internet sites.\r\n\r\nFurther speed enhancements are made in the eZ publish by using caching of data and reduced connections to the database.\r\n\r\nAll the default templates delivered with eZ publish are tested on a diverse mix of browsers, Opera, Internet Explorer, Netscape, Konqueror and Lynx, thus enabling all users to gain access to your site.\r\n\r\nSo called cookie-less sessions are supported, a method used to enable user recognition even for those who objects to use cookies, no-one will feel left out or overseen.\r\n\r\neZ publish keeps track of the statistics of your site. How many visitors, from where, what do they buy and what are they looking at.\r\n\r\nThe package has been translated to several languages, you can even translate it yourself through the eZ Babel software we\'ve developed for this purpose specifically.\r\n\r\nChanging the design of your site is easy because of separation of content and design. You don\'t have to know anything about PHP or coding, just something about HTML.\r\n\r\nFor those proficient in programming PHP the source code is available, it can be used as a basis for adding new modules and functionality tailored to your specific needs.\r\n\r\nThe target audience for eZ publish are e-commerce, ASP (Application Service Providers), news publishing, intranets, bug reporting, content management, discussion boards, FAQ and knowledge handling, file and image management, group ware, calendaring, appointments.\r\n\r\n     \r\n(1) We do not encourage copyright infringements with this feature. Our default templates will not pass these news items as the site\'s own. \r\n\r\nAsk permission from copyright holder before publishing other site\'s news on your site.\r\n\r\n(2) There are limits, of course, since the system is based on other software, and because it will run on systems with different sizes of hard disks and ram, as well as processor speed.\r\n</page></body></article>','admin user','',27,20010126121313,20010126115247,1,'true',20010126115247,'tech\neZ publish is a web based application suite. It delivers functionality ranging from publishing of news, logs and diaries, through shop like shopping carts wishlists forums to intranet functions contact handling bug reporting.\r\n\r\nThe software uses caching other optimization techniques speed up page serving. handles users, user preferences tracking database both cookie-based non-cookie sessions.\r\n\r\nIt supports statistics for views, links followed banner ads, images HTML with presentation logic.\r\n\r\nThe package lends itself easily customization, changing the look feel by templates, localizing languages internationalization issues adding new functionality.\r\n\r\nThe target audience eZ are e-commerce, ASP (Application Service Providers), BSP (Business news publishing, intranets, reporting, content management, discussion boards, FAQ knowledge handling, file image group ware, calendaring, polls, todo lists, appointments as well personal sites.\r\neZ suite which following functionality:\r\n\r\nAdvertising statistics\r\nArticle publication management\r\nBug reporting\r\nCalendar creating events\r\nContact keeping track people businesses\r\nFile manager uploaded files\r\nModerated discussions\r\nImage images\r\nLink used categorize links\r\nNews feed importing, fetch headlines sites incorporate them in your own(1)\r\nPoll module polls.\r\nSession users their preferences\r\nStatistics information about views visitors\r\nTo-do assigning tasks people\r\nTrade an online shop, cart wishlist\r\nUser management registering giving access different groups parts site\r\n\r\nThe does not believe limits(2):\r\n\r\nNo limits on categories items categories\r\nArticles, products might belong several categories\r\nNo associated company, or number companies registered totally\r\nNo addresses, phone numbers points businesses\r\nNo they groups\r\n  \r\nBased PHP, leading programming language Apache server software, draws this renown software. The backend MySQL fast reliable, proven thousands Internet sites.\r\n\r\nFurther enhancements made using data reduced connections database.\r\n\r\nAll default templates delivered tested diverse mix browsers, Opera, Explorer, Netscape, Konqueror Lynx, thus enabling all gain site.\r\n\r\nSo called cookie-less sessions supported, method enable recognition even those who objects use cookies, no-one will left out overseen.\r\n\r\neZ keeps site. How many visitors, where, what do buy looking at.\r\n\r\nThe has been translated languages, you can translate it yourself Babel we\'ve developed purpose specifically.\r\n\r\nChanging design site easy because separation design. You don\'t have know anything PHP coding, just something HTML.\r\n\r\nFor proficient source code available, be basis modules tailored specific needs.\r\n\r\nThe appointments.\r\n\r\n \r\n(1) We encourage copyright infringements feature. Our pass these site\'s own. \r\n\r\nAsk permission holder before site.\r\n\r\n(2) There limits, course, since system run systems sizes hard disks ram, processor speed.\r\n ');

#
# Table structure for table 'eZArticle_ArticleCategoryDefinition'
#

DROP TABLE IF EXISTS eZArticle_ArticleCategoryDefinition;
CREATE TABLE eZArticle_ArticleCategoryDefinition (
  ID int(11) NOT NULL auto_increment,
  ArticleID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_ArticleCategoryDefinition'
#

INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (18,1,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (12,5,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (10,6,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (4,4,2);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (17,7,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (15,8,1);
INSERT INTO eZArticle_ArticleCategoryDefinition VALUES (16,9,1);

#
# Table structure for table 'eZArticle_ArticleCategoryLink'
#

DROP TABLE IF EXISTS eZArticle_ArticleCategoryLink;
CREATE TABLE eZArticle_ArticleCategoryLink (
  ID int(11) NOT NULL auto_increment,
  ArticleID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_ArticleCategoryLink'
#

INSERT INTO eZArticle_ArticleCategoryLink VALUES (22,1,3,23);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (14,5,1,10);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (21,1,4,22);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (4,4,2,4);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (20,1,1,21);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (12,6,1,18);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (19,7,1,20);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (18,9,1,13);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (17,8,1,19);
INSERT INTO eZArticle_ArticleCategoryLink VALUES (23,1,2,24);

#
# Table structure for table 'eZArticle_ArticleFileLink'
#

DROP TABLE IF EXISTS eZArticle_ArticleFileLink;
CREATE TABLE eZArticle_ArticleFileLink (
  ID int(11) NOT NULL auto_increment,
  ArticleID int(11) NOT NULL default '0',
  FileID int(11) NOT NULL default '0',
  Created timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_ArticleFileLink'
#

INSERT INTO eZArticle_ArticleFileLink VALUES (1,5,1,20010126103230);

#
# Table structure for table 'eZArticle_ArticleForumLink'
#

DROP TABLE IF EXISTS eZArticle_ArticleForumLink;
CREATE TABLE eZArticle_ArticleForumLink (
  ID int(11) NOT NULL auto_increment,
  ArticleID int(11) NOT NULL default '0',
  ForumID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_ArticleForumLink'
#

INSERT INTO eZArticle_ArticleForumLink VALUES (1,1,2);
INSERT INTO eZArticle_ArticleForumLink VALUES (2,5,3);
INSERT INTO eZArticle_ArticleForumLink VALUES (3,9,4);
INSERT INTO eZArticle_ArticleForumLink VALUES (4,6,5);
INSERT INTO eZArticle_ArticleForumLink VALUES (5,7,6);
INSERT INTO eZArticle_ArticleForumLink VALUES (6,8,7);

#
# Table structure for table 'eZArticle_ArticleImageDefinition'
#

DROP TABLE IF EXISTS eZArticle_ArticleImageDefinition;
CREATE TABLE eZArticle_ArticleImageDefinition (
  ArticleID int(11) NOT NULL default '0',
  ThumbnailImageID int(11) default NULL,
  PRIMARY KEY (ArticleID),
  UNIQUE KEY ArticleID(ArticleID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_ArticleImageDefinition'
#

INSERT INTO eZArticle_ArticleImageDefinition VALUES (4,20);
INSERT INTO eZArticle_ArticleImageDefinition VALUES (1,1);

#
# Table structure for table 'eZArticle_ArticleImageLink'
#

DROP TABLE IF EXISTS eZArticle_ArticleImageLink;
CREATE TABLE eZArticle_ArticleImageLink (
  ID int(11) NOT NULL auto_increment,
  ArticleID int(11) NOT NULL default '0',
  ImageID int(11) NOT NULL default '0',
  Created timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZArticle_ArticleImageLink'
#

INSERT INTO eZArticle_ArticleImageLink VALUES (1,1,1,20010126100427);
INSERT INTO eZArticle_ArticleImageLink VALUES (2,1,2,20010126100445);

#
# Table structure for table 'eZArticle_Category'
#

DROP TABLE IF EXISTS eZArticle_Category;
CREATE TABLE eZArticle_Category (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Description text,
  ParentID int(11) default '0',
  ExcludeFromSearch enum('true','false') default 'false',
  SortMode int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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

DROP TABLE IF EXISTS eZBug_Bug;
CREATE TABLE eZBug_Bug (
  ID int(11) NOT NULL auto_increment,
  Name varchar(150) default NULL,
  Description text,
  UserID int(11) NOT NULL default '0',
  Created timestamp(14) NOT NULL,
  IsHandled enum('true','false') NOT NULL default 'false',
  PriorityID int(11) NOT NULL default '0',
  StatusID int(11) NOT NULL default '0',
  IsClosed enum('true','false') default 'false',
  UserEmail varchar(100) default '',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZBug_Bug'
#

INSERT INTO eZBug_Bug VALUES (1,'Help!','It dosent work!',33,20010125202931,'false',0,0,'','');

#
# Table structure for table 'eZBug_BugCategoryLink'
#

DROP TABLE IF EXISTS eZBug_BugCategoryLink;
CREATE TABLE eZBug_BugCategoryLink (
  ID int(11) NOT NULL auto_increment,
  CategoryID int(11) default NULL,
  BugID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZBug_BugCategoryLink'
#

INSERT INTO eZBug_BugCategoryLink VALUES (1,2,1);

#
# Table structure for table 'eZBug_BugModuleLink'
#

DROP TABLE IF EXISTS eZBug_BugModuleLink;
CREATE TABLE eZBug_BugModuleLink (
  ID int(11) NOT NULL auto_increment,
  ModuleID int(11) default NULL,
  BugID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZBug_BugModuleLink'
#

INSERT INTO eZBug_BugModuleLink VALUES (1,1,1);

#
# Table structure for table 'eZBug_Category'
#

DROP TABLE IF EXISTS eZBug_Category;
CREATE TABLE eZBug_Category (
  ID int(11) NOT NULL auto_increment,
  Name varchar(150) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZBug_Category'
#

INSERT INTO eZBug_Category VALUES (1,'GUI','');
INSERT INTO eZBug_Category VALUES (2,'Feature request','');

#
# Table structure for table 'eZBug_Log'
#

DROP TABLE IF EXISTS eZBug_Log;
CREATE TABLE eZBug_Log (
  ID int(11) NOT NULL auto_increment,
  BugID int(11) NOT NULL default '0',
  UserID int(11) NOT NULL default '0',
  Description text,
  Created timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZBug_Log'
#


#
# Table structure for table 'eZBug_Module'
#

DROP TABLE IF EXISTS eZBug_Module;
CREATE TABLE eZBug_Module (
  ID int(11) NOT NULL auto_increment,
  ParentID int(11) default NULL,
  Name varchar(150) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZBug_Module'
#

INSERT INTO eZBug_Module VALUES (1,0,'My program','');

#
# Table structure for table 'eZBug_Priority'
#

DROP TABLE IF EXISTS eZBug_Priority;
CREATE TABLE eZBug_Priority (
  ID int(11) NOT NULL auto_increment,
  Name char(150) NOT NULL default '',
  Value int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZBug_Priority'
#

INSERT INTO eZBug_Priority VALUES (1,'High',NULL);
INSERT INTO eZBug_Priority VALUES (2,'Middels',NULL);
INSERT INTO eZBug_Priority VALUES (3,'Low',NULL);

#
# Table structure for table 'eZBug_Status'
#

DROP TABLE IF EXISTS eZBug_Status;
CREATE TABLE eZBug_Status (
  ID int(11) NOT NULL auto_increment,
  Name char(150) NOT NULL default '',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZBug_Status'
#

INSERT INTO eZBug_Status VALUES (1,'Fixed');

#
# Table structure for table 'eZCalendar_Appointment'
#

DROP TABLE IF EXISTS eZCalendar_Appointment;
CREATE TABLE eZCalendar_Appointment (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  Date timestamp(14) NOT NULL,
  Duration time default NULL,
  AppointmentTypeID int(11) NOT NULL default '0',
  EMailNotice int(11) default '0',
  IsPrivate int(11) default NULL,
  Name varchar(200) default NULL,
  Description text,
  Priority int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZCalendar_Appointment'
#


#
# Table structure for table 'eZCalendar_AppointmentType'
#

DROP TABLE IF EXISTS eZCalendar_AppointmentType;
CREATE TABLE eZCalendar_AppointmentType (
  ID int(11) NOT NULL auto_increment,
  ParentID int(11) NOT NULL default '0',
  Description text,
  Name varchar(200) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZCalendar_AppointmentType'
#


#
# Table structure for table 'eZContact_Company'
#

DROP TABLE IF EXISTS eZContact_Company;
CREATE TABLE eZContact_Company (
  ID int(11) NOT NULL auto_increment,
  CreatorID int(11) NOT NULL default '0',
  Name varchar(50) NOT NULL default '',
  Comment text,
  ContactType int(11) NOT NULL default '0',
  CompanyNo varchar(20) NOT NULL default '',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_Company'
#


#
# Table structure for table 'eZContact_CompanyAddressDict'
#

DROP TABLE IF EXISTS eZContact_CompanyAddressDict;
CREATE TABLE eZContact_CompanyAddressDict (
  CompanyID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID,AddressID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_CompanyAddressDict'
#


#
# Table structure for table 'eZContact_CompanyImageDefinition'
#

DROP TABLE IF EXISTS eZContact_CompanyImageDefinition;
CREATE TABLE eZContact_CompanyImageDefinition (
  CompanyID int(11) NOT NULL default '0',
  CompanyImageID int(11) NOT NULL default '0',
  LogoImageID int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_CompanyImageDefinition'
#


#
# Table structure for table 'eZContact_CompanyOnlineDict'
#

DROP TABLE IF EXISTS eZContact_CompanyOnlineDict;
CREATE TABLE eZContact_CompanyOnlineDict (
  CompanyID int(11) NOT NULL default '0',
  OnlineID int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID,OnlineID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_CompanyOnlineDict'
#


#
# Table structure for table 'eZContact_CompanyPersonDict'
#

DROP TABLE IF EXISTS eZContact_CompanyPersonDict;
CREATE TABLE eZContact_CompanyPersonDict (
  CompanyID int(11) NOT NULL default '0',
  PersonID int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID,PersonID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_CompanyPersonDict'
#


#
# Table structure for table 'eZContact_CompanyPhoneDict'
#

DROP TABLE IF EXISTS eZContact_CompanyPhoneDict;
CREATE TABLE eZContact_CompanyPhoneDict (
  CompanyID int(11) NOT NULL default '0',
  PhoneID int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID,PhoneID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_CompanyPhoneDict'
#


#
# Table structure for table 'eZContact_CompanyProjectDict'
#

DROP TABLE IF EXISTS eZContact_CompanyProjectDict;
CREATE TABLE eZContact_CompanyProjectDict (
  CompanyID int(11) NOT NULL default '0',
  ProjectID int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyID,ProjectID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_CompanyProjectDict'
#


#
# Table structure for table 'eZContact_CompanyType'
#

DROP TABLE IF EXISTS eZContact_CompanyType;
CREATE TABLE eZContact_CompanyType (
  ID int(11) NOT NULL auto_increment,
  Name varchar(50) NOT NULL default '',
  Description text,
  ParentID int(11) NOT NULL default '0',
  ImageID int(11) NOT NULL default '0',
  PRIMARY KEY (ID),
  KEY ParentID(ParentID),
  KEY Name(Name)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_CompanyType'
#


#
# Table structure for table 'eZContact_CompanyTypeDict'
#

DROP TABLE IF EXISTS eZContact_CompanyTypeDict;
CREATE TABLE eZContact_CompanyTypeDict (
  CompanyTypeID int(11) NOT NULL default '0',
  CompanyID int(11) NOT NULL default '0',
  PRIMARY KEY (CompanyTypeID,CompanyID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_CompanyTypeDict'
#


#
# Table structure for table 'eZContact_ConsulationCompanyDict'
#

DROP TABLE IF EXISTS eZContact_ConsulationCompanyDict;
CREATE TABLE eZContact_ConsulationCompanyDict (
  ConsultationID int(11) NOT NULL default '0',
  CompanyID int(11) NOT NULL default '0',
  PRIMARY KEY (ConsultationID,CompanyID)
) TYPE=ISAM PACK_KEYS=1;

#
# Dumping data for table 'eZContact_ConsulationCompanyDict'
#


#
# Table structure for table 'eZContact_Consultation'
#

DROP TABLE IF EXISTS eZContact_Consultation;
CREATE TABLE eZContact_Consultation (
  ID int(11) NOT NULL auto_increment,
  ShortDesc varchar(100) NOT NULL default '',
  Description text NOT NULL,
  Date datetime NOT NULL default '0000-00-00 00:00:00',
  StateID int(11) NOT NULL default '0',
  EmailNotifications varchar(255) NOT NULL default '',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_Consultation'
#


#
# Table structure for table 'eZContact_ConsultationCompanyUserDict'
#

DROP TABLE IF EXISTS eZContact_ConsultationCompanyUserDict;
CREATE TABLE eZContact_ConsultationCompanyUserDict (
  ConsultationID int(11) NOT NULL default '0',
  CompanyID int(11) NOT NULL default '0',
  UserID int(11) NOT NULL default '0',
  PRIMARY KEY (ConsultationID,CompanyID,UserID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_ConsultationCompanyUserDict'
#


#
# Table structure for table 'eZContact_ConsultationGroupsDict'
#

DROP TABLE IF EXISTS eZContact_ConsultationGroupsDict;
CREATE TABLE eZContact_ConsultationGroupsDict (
  ConsultationID int(11) NOT NULL default '0',
  GroupID int(11) NOT NULL default '0',
  PRIMARY KEY (ConsultationID,GroupID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_ConsultationGroupsDict'
#


#
# Table structure for table 'eZContact_ConsultationPersonUserDict'
#

DROP TABLE IF EXISTS eZContact_ConsultationPersonUserDict;
CREATE TABLE eZContact_ConsultationPersonUserDict (
  ConsultationID int(11) NOT NULL default '0',
  PersonID int(11) NOT NULL default '0',
  UserID int(11) NOT NULL default '0',
  PRIMARY KEY (ConsultationID,PersonID,UserID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_ConsultationPersonUserDict'
#


#
# Table structure for table 'eZContact_ConsultationType'
#

DROP TABLE IF EXISTS eZContact_ConsultationType;
CREATE TABLE eZContact_ConsultationType (
  ID int(11) NOT NULL auto_increment,
  Name varchar(50) default NULL,
  ListOrder int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_ConsultationType'
#


#
# Table structure for table 'eZContact_ContactType'
#

DROP TABLE IF EXISTS eZContact_ContactType;
CREATE TABLE eZContact_ContactType (
  ID int(11) NOT NULL auto_increment,
  Name varchar(50) NOT NULL default '',
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_ContactType'
#


#
# Table structure for table 'eZContact_ImageType'
#

DROP TABLE IF EXISTS eZContact_ImageType;
CREATE TABLE eZContact_ImageType (
  ID int(11) NOT NULL auto_increment,
  Name char(50) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_ImageType'
#


#
# Table structure for table 'eZContact_Person'
#

DROP TABLE IF EXISTS eZContact_Person;
CREATE TABLE eZContact_Person (
  ID int(11) NOT NULL auto_increment,
  FirstName varchar(50) default NULL,
  LastName varchar(50) default NULL,
  BirthDate date default NULL,
  Comment text,
  ContactTypeID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_Person'
#


#
# Table structure for table 'eZContact_PersonAddressDict'
#

DROP TABLE IF EXISTS eZContact_PersonAddressDict;
CREATE TABLE eZContact_PersonAddressDict (
  PersonID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  PRIMARY KEY (PersonID,AddressID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_PersonAddressDict'
#


#
# Table structure for table 'eZContact_PersonOnlineDict'
#

DROP TABLE IF EXISTS eZContact_PersonOnlineDict;
CREATE TABLE eZContact_PersonOnlineDict (
  PersonID int(11) NOT NULL default '0',
  OnlineID int(11) NOT NULL default '0',
  PRIMARY KEY (PersonID,OnlineID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_PersonOnlineDict'
#


#
# Table structure for table 'eZContact_PersonPhoneDict'
#

DROP TABLE IF EXISTS eZContact_PersonPhoneDict;
CREATE TABLE eZContact_PersonPhoneDict (
  PersonID int(11) NOT NULL default '0',
  PhoneID int(11) NOT NULL default '0',
  PRIMARY KEY (PersonID,PhoneID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_PersonPhoneDict'
#


#
# Table structure for table 'eZContact_PersonProjectDict'
#

DROP TABLE IF EXISTS eZContact_PersonProjectDict;
CREATE TABLE eZContact_PersonProjectDict (
  PersonID int(11) NOT NULL default '0',
  ProjectID int(11) NOT NULL default '0',
  PRIMARY KEY (PersonID,ProjectID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_PersonProjectDict'
#


#
# Table structure for table 'eZContact_ProjectType'
#

DROP TABLE IF EXISTS eZContact_ProjectType;
CREATE TABLE eZContact_ProjectType (
  ID int(11) NOT NULL auto_increment,
  Name char(50) NOT NULL default '',
  ListOrder int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_ProjectType'
#


#
# Table structure for table 'eZContact_UserCompanyDict'
#

DROP TABLE IF EXISTS eZContact_UserCompanyDict;
CREATE TABLE eZContact_UserCompanyDict (
  UserID int(11) NOT NULL default '0',
  CompanyID int(11) NOT NULL default '0',
  PRIMARY KEY (UserID,CompanyID),
  UNIQUE KEY CompanyID(CompanyID),
  UNIQUE KEY UserID(UserID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_UserCompanyDict'
#


#
# Table structure for table 'eZContact_UserPersonDict'
#

DROP TABLE IF EXISTS eZContact_UserPersonDict;
CREATE TABLE eZContact_UserPersonDict (
  UserID int(11) NOT NULL default '0',
  PersonID int(11) NOT NULL default '0',
  PRIMARY KEY (UserID,PersonID),
  UNIQUE KEY PersonID(PersonID),
  UNIQUE KEY UserID(UserID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZContact_UserPersonDict'
#


#
# Table structure for table 'eZFileManager_File'
#

DROP TABLE IF EXISTS eZFileManager_File;
CREATE TABLE eZFileManager_File (
  ID int(11) NOT NULL auto_increment,
  Name char(200) default NULL,
  Description char(200) default NULL,
  FileName char(200) default NULL,
  OriginalFileName char(200) default NULL,
  ReadPermission int(11) default '1',
  WritePermission int(11) default '1',
  UserID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZFileManager_File'
#

INSERT INTO eZFileManager_File VALUES (1,'CHANGELOG','The complete change log.','phpUuO7Ms','CHANGELOG',0,0,0);

#
# Table structure for table 'eZFileManager_FileFolderLink'
#

DROP TABLE IF EXISTS eZFileManager_FileFolderLink;
CREATE TABLE eZFileManager_FileFolderLink (
  ID int(11) NOT NULL auto_increment,
  FolderID int(11) NOT NULL default '0',
  FileID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZFileManager_FileFolderLink'
#


#
# Table structure for table 'eZFileManager_FilePageViewLink'
#

DROP TABLE IF EXISTS eZFileManager_FilePageViewLink;
CREATE TABLE eZFileManager_FilePageViewLink (
  ID int(11) NOT NULL auto_increment,
  PageViewID int(11) NOT NULL default '0',
  FileID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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

DROP TABLE IF EXISTS eZFileManager_Folder;
CREATE TABLE eZFileManager_Folder (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Description text,
  ParentID int(11) NOT NULL default '0',
  ReadPermission int(11) default '1',
  WritePermission int(11) default '1',
  UserID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZFileManager_Folder'
#


#
# Table structure for table 'eZForum_Category'
#

DROP TABLE IF EXISTS eZForum_Category;
CREATE TABLE eZForum_Category (
  Name varchar(20) default NULL,
  Description varchar(40) default NULL,
  Private enum('Y','N') default 'N',
  ID int(11) NOT NULL auto_increment,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZForum_Category'
#

INSERT INTO eZForum_Category VALUES ('Talk center','General talk','N',1);

#
# Table structure for table 'eZForum_Forum'
#

DROP TABLE IF EXISTS eZForum_Forum;
CREATE TABLE eZForum_Forum (
  Name varchar(20) NOT NULL default '',
  Description varchar(40) default NULL,
  Private enum('Y','N') default 'N',
  ID int(11) NOT NULL auto_increment,
  ModeratorID int(11) NOT NULL default '0',
  IsModerated int(11) NOT NULL default '0',
  GroupID int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZForum_Forum'
#

INSERT INTO eZForum_Forum VALUES ('Discussion','Discuss everything here','',1,27,0,0);
INSERT INTO eZForum_Forum VALUES ('Demo article','','',2,0,0,0);
INSERT INTO eZForum_Forum VALUES ('What is New?','','',3,0,0,0);
INSERT INTO eZForum_Forum VALUES ('What can eZ publish','','',4,0,0,0);
INSERT INTO eZForum_Forum VALUES ('eZ Newsfeed','','',5,0,0,0);
INSERT INTO eZForum_Forum VALUES ('eZ Article','','',6,0,0,0);
INSERT INTO eZForum_Forum VALUES ('eZ Trade','','',7,0,0,0);

#
# Table structure for table 'eZForum_ForumCategoryLink'
#

DROP TABLE IF EXISTS eZForum_ForumCategoryLink;
CREATE TABLE eZForum_ForumCategoryLink (
  ID int(11) NOT NULL auto_increment,
  ForumID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZForum_ForumCategoryLink'
#

INSERT INTO eZForum_ForumCategoryLink VALUES (3,1,1);

#
# Table structure for table 'eZForum_Message'
#

DROP TABLE IF EXISTS eZForum_Message;
CREATE TABLE eZForum_Message (
  ForumID int(11) NOT NULL default '0',
  Topic varchar(60) default NULL,
  Body text,
  UserID int(11) default NULL,
  Parent int(11) default NULL,
  EmailNotice enum('N','Y') default 'N',
  PostingTime timestamp(14) NOT NULL,
  TreeID int(11) default NULL,
  ThreadID int(11) default NULL,
  Depth int(11) default NULL,
  ID int(11) NOT NULL auto_increment,
  IsApproved int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZForum_Message'
#

INSERT INTO eZForum_Message VALUES (1,'First post!','This is the first post!',27,0,'N',20010122104742,1,0,0,1,1);
INSERT INTO eZForum_Message VALUES (1,'SV: First post!','> This is the first post!\r\nThis is the reply!',27,1,'N',20010122104747,0,0,1,2,1);

#
# Table structure for table 'eZImageCatalogue_Category'
#

DROP TABLE IF EXISTS eZImageCatalogue_Category;
CREATE TABLE eZImageCatalogue_Category (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Description text,
  ParentID int(11) default NULL,
  UserID int(11) default NULL,
  WritePermission int(11) default '1',
  ReadPermission int(11) default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZImageCatalogue_Category'
#

INSERT INTO eZImageCatalogue_Category VALUES (1,'Images',' ',0,27,2,3);

#
# Table structure for table 'eZImageCatalogue_Image'
#

DROP TABLE IF EXISTS eZImageCatalogue_Image;
CREATE TABLE eZImageCatalogue_Image (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Caption text,
  Description text,
  FileName varchar(100) default NULL,
  OriginalFileName varchar(100) default NULL,
  ReadPermission int(11) default '1',
  WritePermission int(11) default '1',
  UserID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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
INSERT INTO eZImageCatalogue_Image VALUES (8,'','','','php7CTbzs.gif','ezsystems-banner.gif',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (9,'','','','phpA9uCUK.gif','ezpublish-banner.gif',0,0,0);
INSERT INTO eZImageCatalogue_Image VALUES (10,'','','','phpUCtzjC.gif','ezpublish-anim-banner.gif',0,0,0);

#
# Table structure for table 'eZImageCatalogue_ImageCategoryLink'
#

DROP TABLE IF EXISTS eZImageCatalogue_ImageCategoryLink;
CREATE TABLE eZImageCatalogue_ImageCategoryLink (
  ID int(11) NOT NULL auto_increment,
  CategoryID int(11) default NULL,
  ImageID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZImageCatalogue_ImageCategoryLink'
#

INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (1,2,5);
INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (2,2,6);
INSERT INTO eZImageCatalogue_ImageCategoryLink VALUES (3,2,7);

#
# Table structure for table 'eZImageCatalogue_ImageVariation'
#

DROP TABLE IF EXISTS eZImageCatalogue_ImageVariation;
CREATE TABLE eZImageCatalogue_ImageVariation (
  ID int(11) NOT NULL auto_increment,
  ImageID int(11) default NULL,
  VariationGroupID int(11) default NULL,
  ImagePath char(100) default NULL,
  Width int(11) default NULL,
  Height int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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
INSERT INTO eZImageCatalogue_ImageVariation VALUES (18,1,6,'ezimagecatalogue/catalogue/variations/1-400x500.jpg',400,300);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (19,2,6,'ezimagecatalogue/catalogue/variations/2-400x500.jpg',400,300);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (20,3,9,'ezimagecatalogue/catalogue/variations/3-109x109.jpg',109,81);
INSERT INTO eZImageCatalogue_ImageVariation VALUES (21,4,9,'ezimagecatalogue/catalogue/variations/4-109x109.jpg',109,81);

#
# Table structure for table 'eZImageCatalogue_ImageVariationGroup'
#

DROP TABLE IF EXISTS eZImageCatalogue_ImageVariationGroup;
CREATE TABLE eZImageCatalogue_ImageVariationGroup (
  ID int(11) NOT NULL auto_increment,
  Width int(11) default NULL,
  Height int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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
INSERT INTO eZImageCatalogue_ImageVariationGroup VALUES (9,109,109);

#
# Table structure for table 'eZLink_Category'
#

DROP TABLE IF EXISTS eZLink_Category;
CREATE TABLE eZLink_Category (
  ID int(11) NOT NULL auto_increment,
  Parent int(11) default '0',
  Name char(100) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZLink_Category'
#

INSERT INTO eZLink_Category VALUES (1,0,'Cool links');
INSERT INTO eZLink_Category VALUES (2,0,'Not so cool links');

#
# Table structure for table 'eZLink_Hit'
#

DROP TABLE IF EXISTS eZLink_Hit;
CREATE TABLE eZLink_Hit (
  ID int(11) NOT NULL auto_increment,
  Link int(11) default NULL,
  Time timestamp(14) NOT NULL,
  RemoteIP char(15) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZLink_Hit'
#


#
# Table structure for table 'eZLink_Link'
#

DROP TABLE IF EXISTS eZLink_Link;
CREATE TABLE eZLink_Link (
  ID int(11) NOT NULL auto_increment,
  Title varchar(100) default NULL,
  Description text,
  LinkGroup int(11) default NULL,
  KeyWords varchar(100) default NULL,
  Modified timestamp(14) NOT NULL,
  Accepted enum('Y','N') default NULL,
  Created datetime default NULL,
  Url varchar(100) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZLink_Link'
#

INSERT INTO eZLink_Link VALUES (1,'eZ systems as','Test!',1,'Test!',20010125160958,'Y','2001-01-25 16:09:58','ez.no');

#
# Table structure for table 'eZLink_LinkCategoryDefinition'
#

DROP TABLE IF EXISTS eZLink_LinkCategoryDefinition;
CREATE TABLE eZLink_LinkCategoryDefinition (
  ID int(11) NOT NULL auto_increment,
  LinkID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZLink_LinkCategoryDefinition'
#

INSERT INTO eZLink_LinkCategoryDefinition VALUES (1,1,1);

#
# Table structure for table 'eZLink_LinkCategoryLink'
#

DROP TABLE IF EXISTS eZLink_LinkCategoryLink;
CREATE TABLE eZLink_LinkCategoryLink (
  ID int(11) NOT NULL auto_increment,
  LinkID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZLink_LinkCategoryLink'
#

INSERT INTO eZLink_LinkCategoryLink VALUES (1,1,1);

#
# Table structure for table 'eZLink_LinkGroup'
#

DROP TABLE IF EXISTS eZLink_LinkGroup;
CREATE TABLE eZLink_LinkGroup (
  ID int(11) NOT NULL auto_increment,
  Parent int(11) default '0',
  Title char(100) default NULL,
  ImageID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZLink_LinkGroup'
#

INSERT INTO eZLink_LinkGroup VALUES (1,0,'Cool links',NULL);

#
# Table structure for table 'eZNewsFeed_Category'
#

DROP TABLE IF EXISTS eZNewsFeed_Category;
CREATE TABLE eZNewsFeed_Category (
  ID int(11) NOT NULL auto_increment,
  Name varchar(150) NOT NULL default '',
  Description text,
  ParentID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZNewsFeed_Category'
#

INSERT INTO eZNewsFeed_Category VALUES (1,'News from freshmeat','',0);

#
# Table structure for table 'eZNewsFeed_News'
#

DROP TABLE IF EXISTS eZNewsFeed_News;
CREATE TABLE eZNewsFeed_News (
  ID int(11) NOT NULL auto_increment,
  IsPublished enum('true','false') default 'false',
  PublishingDate timestamp(14) NOT NULL,
  OriginalPublishingDate timestamp(14) NOT NULL,
  Name varchar(150) NOT NULL default '',
  Intro text,
  KeyWords varchar(200) default NULL,
  URL varchar(200) default NULL,
  Origin varchar(150) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZNewsFeed_News'
#


#
# Table structure for table 'eZNewsFeed_NewsCategoryLink'
#

DROP TABLE IF EXISTS eZNewsFeed_NewsCategoryLink;
CREATE TABLE eZNewsFeed_NewsCategoryLink (
  ID int(11) NOT NULL auto_increment,
  NewsID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZNewsFeed_NewsCategoryLink'
#


#
# Table structure for table 'eZNewsFeed_SourceSite'
#

DROP TABLE IF EXISTS eZNewsFeed_SourceSite;
CREATE TABLE eZNewsFeed_SourceSite (
  ID int(11) NOT NULL auto_increment,
  URL char(250) default NULL,
  Login char(30) default NULL,
  Password char(30) default NULL,
  CategoryID int(11) NOT NULL default '0',
  Name char(100) default NULL,
  Decoder char(50) default NULL,
  IsActive enum('true','false') default 'false',
  AutoPublish int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZNewsFeed_SourceSite'
#

INSERT INTO eZNewsFeed_SourceSite VALUES (1,'http://freshmeat.net/backend/fm.rdf','','',1,'Freshmeat','','false',0);

#
# Table structure for table 'eZPoll_MainPoll'
#

DROP TABLE IF EXISTS eZPoll_MainPoll;
CREATE TABLE eZPoll_MainPoll (
  ID int(11) NOT NULL auto_increment,
  PollID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZPoll_MainPoll'
#

INSERT INTO eZPoll_MainPoll VALUES (1,1);

#
# Table structure for table 'eZPoll_Poll'
#

DROP TABLE IF EXISTS eZPoll_Poll;
CREATE TABLE eZPoll_Poll (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Description text,
  Percent enum('true','false') default NULL,
  Number enum('true','false') default NULL,
  Anonymous enum('true','false') default NULL,
  IsEnabled enum('true','false') default 'false',
  IsClosed enum('true','false') default 'false',
  ShowResult enum('true','false') default 'false',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZPoll_Poll'
#

INSERT INTO eZPoll_Poll VALUES (1,'First poll','this is a demo poll',NULL,NULL,'true','true','false','true');

#
# Table structure for table 'eZPoll_PollChoice'
#

DROP TABLE IF EXISTS eZPoll_PollChoice;
CREATE TABLE eZPoll_PollChoice (
  ID int(11) NOT NULL auto_increment,
  PollID int(11) default NULL,
  Name char(100) default NULL,
  Offset int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZPoll_PollChoice'
#

INSERT INTO eZPoll_PollChoice VALUES (2,1,'Bar',0);
INSERT INTO eZPoll_PollChoice VALUES (1,1,'FOo',0);

#
# Table structure for table 'eZPoll_Vote'
#

DROP TABLE IF EXISTS eZPoll_Vote;
CREATE TABLE eZPoll_Vote (
  ID int(11) NOT NULL auto_increment,
  PollID int(11) default NULL,
  ChoiceID int(11) default NULL,
  VotingIP char(20) default NULL,
  UserID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZPoll_Vote'
#


#
# Table structure for table 'eZSession_Preferences'
#

DROP TABLE IF EXISTS eZSession_Preferences;
CREATE TABLE eZSession_Preferences (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  Name char(50) default NULL,
  Value char(255) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZSession_Preferences'
#


#
# Table structure for table 'eZSession_Session'
#

DROP TABLE IF EXISTS eZSession_Session;
CREATE TABLE eZSession_Session (
  ID int(11) NOT NULL auto_increment,
  Hash char(33) default NULL,
  Created timestamp(14) NOT NULL,
  LastAccessed timestamp(14) NOT NULL,
  SecondLastAccessed timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZSession_Session'
#

INSERT INTO eZSession_Session VALUES (1,'ed2cf21b9d66ce61c0ad6f9ed82c0cec',20010213165754,20010213170035,20010213170033);
INSERT INTO eZSession_Session VALUES (2,'6cebc659ac9782a67acc3163fa5c3a24',20010213165836,20010213165836,20010213165836);
INSERT INTO eZSession_Session VALUES (3,'466dd0324b6c92baca0e53681b7aadf2',20010213165836,20010213165836,20010213165836);
INSERT INTO eZSession_Session VALUES (4,'a579c5e8d25909cd6f97ba1cc5ccd919',20010213165836,20010213165836,20010213165836);
INSERT INTO eZSession_Session VALUES (5,'025b248fbc1e4afd14e020e8d2d27a49',20010213165837,20010213165837,20010213165837);
INSERT INTO eZSession_Session VALUES (6,'290fa671e5e45fc2056c461986a604d7',20010213165837,20010213165837,20010213165837);
INSERT INTO eZSession_Session VALUES (7,'067fc79851de27d9b4fa0de6f706a3a2',20010213165837,20010213165837,20010213165837);
INSERT INTO eZSession_Session VALUES (8,'53d157609a8888f4207f5745776b240c',20010213165843,20010213165843,20010213165843);
INSERT INTO eZSession_Session VALUES (9,'663dd26525277436cb14fcf42344ac49',20010213165843,20010213165843,20010213165843);
INSERT INTO eZSession_Session VALUES (10,'1a2a210f09a0877903e20591fb40e95c',20010213165844,20010213165844,20010213165844);
INSERT INTO eZSession_Session VALUES (11,'5196a4613a237e5aa044e5c8dc06a900',20010213165844,20010213165844,20010213165844);
INSERT INTO eZSession_Session VALUES (12,'86b808339b02b61eafcdd8637747094f',20010213165844,20010213165844,20010213165844);
INSERT INTO eZSession_Session VALUES (13,'59213ef379c241c700c61434eca27a2b',20010213165845,20010213165845,20010213165845);
INSERT INTO eZSession_Session VALUES (14,'a0b0a9c9fa529f794e101c9298deda31',20010213165845,20010213165845,20010213165845);
INSERT INTO eZSession_Session VALUES (15,'c2264fe2c9e2d1db976d84929c89c39c',20010213165845,20010213165845,20010213165845);
INSERT INTO eZSession_Session VALUES (16,'81d864f11337a82a2e9d354ca0c01658',20010213165846,20010213165846,20010213165846);
INSERT INTO eZSession_Session VALUES (17,'30b2a595dfc868e791aa9ff876c90fc4',20010213165846,20010213165846,20010213165846);
INSERT INTO eZSession_Session VALUES (18,'2d18a4be23430d9511800589b28195e6',20010213165846,20010213165846,20010213165846);
INSERT INTO eZSession_Session VALUES (19,'79182528476315bbedadca14b504d8e7',20010213165854,20010213165854,20010213165854);
INSERT INTO eZSession_Session VALUES (20,'34b24feb99a92eed9abced03c4fc1d95',20010213165855,20010213165855,20010213165855);
INSERT INTO eZSession_Session VALUES (21,'d8c95674d224e396286d7a1541376e21',20010213165855,20010213165855,20010213165855);
INSERT INTO eZSession_Session VALUES (22,'4fa798864c11d094d03dc706d05c69d6',20010213165855,20010213165855,20010213165855);
INSERT INTO eZSession_Session VALUES (23,'5b2277ad219ca90250efe607224096ba',20010213165856,20010213165856,20010213165856);
INSERT INTO eZSession_Session VALUES (24,'f25a77067de8f9443d162da8e1c3dfa1',20010213165856,20010213165856,20010213165856);
INSERT INTO eZSession_Session VALUES (25,'c08ebf7457d7ad0617df6b287afaaa77',20010213165856,20010213165856,20010213165856);
INSERT INTO eZSession_Session VALUES (26,'87b535dfa7cac169f78c0bf4ee148d9d',20010213165856,20010213165856,20010213165856);
INSERT INTO eZSession_Session VALUES (27,'ce74d3691b7c708281d4fe67ea03ea01',20010213165857,20010213165857,20010213165857);
INSERT INTO eZSession_Session VALUES (28,'7d6cc33294254ee88ca833f8c5939a07',20010213165857,20010213165857,20010213165857);
INSERT INTO eZSession_Session VALUES (29,'7143c245fdf18352f04e9a6bd6ea49b1',20010213165857,20010213165857,20010213165857);
INSERT INTO eZSession_Session VALUES (30,'5efd4a2296b9957ceaf51cadec610017',20010213165858,20010213165858,20010213165858);
INSERT INTO eZSession_Session VALUES (31,'932b619e81a7e48ebc706d8af2cfee1a',20010213165858,20010213165858,20010213165858);
INSERT INTO eZSession_Session VALUES (32,'bfe67dc8c4657ea8a4d96a745a89fb11',20010213165858,20010213165858,20010213165858);
INSERT INTO eZSession_Session VALUES (33,'5d814efd8871850330e3a68801083ef1',20010213165859,20010213165859,20010213165859);
INSERT INTO eZSession_Session VALUES (34,'3a4bfe0bcb67581c145406ec19b7f8c1',20010213165859,20010213165859,20010213165859);
INSERT INTO eZSession_Session VALUES (35,'bd30fe2209ecb5b20215fc34ce8b87ec',20010213165859,20010213165859,20010213165859);
INSERT INTO eZSession_Session VALUES (36,'4a7355e0b37390b2ffdb324f16c658f5',20010213165900,20010213165900,20010213165900);
INSERT INTO eZSession_Session VALUES (37,'c0c34dd7d31f2152676a88c94673565b',20010213165900,20010213165900,20010213165900);
INSERT INTO eZSession_Session VALUES (38,'d931bfcc4bdfed582fcf098ae6e86dc9',20010213165900,20010213165900,20010213165900);
INSERT INTO eZSession_Session VALUES (39,'19cd901d5cfc5f346edbbd79bc38d47a',20010213165900,20010213165900,20010213165900);
INSERT INTO eZSession_Session VALUES (40,'4e01b82f40d0d332b00af27e25061618',20010213165901,20010213165901,20010213165901);
INSERT INTO eZSession_Session VALUES (41,'11fa86911c77e5ae1161bb2fc7717d48',20010213165901,20010213165901,20010213165901);
INSERT INTO eZSession_Session VALUES (42,'8336ebc3bd2225a59e6893975e716e64',20010213165901,20010213165901,20010213165901);
INSERT INTO eZSession_Session VALUES (43,'2b2d6301e336df2e24c23f65afff28c0',20010213165902,20010213165902,20010213165902);
INSERT INTO eZSession_Session VALUES (44,'80313ce9202fffd70872db4480354f11',20010213165902,20010213165902,20010213165902);
INSERT INTO eZSession_Session VALUES (45,'61c1521796a48253ca96d3d0b9bc64bd',20010213165902,20010213165902,20010213165902);
INSERT INTO eZSession_Session VALUES (46,'b7071944ab65e7600576788bff5a34c3',20010213165903,20010213165903,20010213165903);
INSERT INTO eZSession_Session VALUES (47,'b70b6729436abfe7a0fa8881b1f2f1f2',20010213165903,20010213165903,20010213165903);
INSERT INTO eZSession_Session VALUES (48,'2bc0814aebb6fa247633e6e8f47a635e',20010213165903,20010213165903,20010213165903);
INSERT INTO eZSession_Session VALUES (49,'3c7e7c6cc59b9111c2c61a03d528c1fc',20010213165903,20010213165903,20010213165903);
INSERT INTO eZSession_Session VALUES (50,'d24408dc67b52502d44db3f7ec94f39f',20010213165904,20010213165904,20010213165904);
INSERT INTO eZSession_Session VALUES (51,'51cf37354f25b3f334e18e6db367291a',20010213165904,20010213165904,20010213165904);
INSERT INTO eZSession_Session VALUES (52,'fd634762f29f1d8f609f37246a130ec7',20010213165904,20010213165904,20010213165904);
INSERT INTO eZSession_Session VALUES (53,'bb438b28a3e19cbc0d6fda13197e43bf',20010213165905,20010213165905,20010213165905);
INSERT INTO eZSession_Session VALUES (54,'71fcd5a113b183eea10fc348aea2de39',20010213165905,20010213165905,20010213165905);
INSERT INTO eZSession_Session VALUES (55,'be76c64abb449fdb5012bfcaac121f03',20010213165905,20010213165905,20010213165905);
INSERT INTO eZSession_Session VALUES (56,'823efbe2179f0dd37430603f1767ad06',20010213165906,20010213165906,20010213165906);
INSERT INTO eZSession_Session VALUES (57,'1563692b8c63af4536178deaedc03760',20010213165906,20010213165906,20010213165906);
INSERT INTO eZSession_Session VALUES (58,'781b0c4ec901e23b760f698ec19e62b2',20010213165906,20010213165906,20010213165906);
INSERT INTO eZSession_Session VALUES (59,'07212f9ce65640cb700af5989783e4a8',20010213165906,20010213165906,20010213165906);
INSERT INTO eZSession_Session VALUES (60,'039877061d5bfc1dc8df52120d552d6e',20010213165907,20010213165907,20010213165907);
INSERT INTO eZSession_Session VALUES (61,'17fb8bae80c3bc67f5b4b98ccd5c47d4',20010213165907,20010213165907,20010213165907);
INSERT INTO eZSession_Session VALUES (62,'4ccef7b0c70a81adffaf3e47b653bb70',20010213165907,20010213165907,20010213165907);
INSERT INTO eZSession_Session VALUES (63,'eb67fd83f767ddfe06c604518313cf09',20010213165908,20010213165908,20010213165908);
INSERT INTO eZSession_Session VALUES (64,'2f23050debe7e5b3bda64d51764db8c3',20010213165908,20010213165908,20010213165908);
INSERT INTO eZSession_Session VALUES (65,'16a90e12ea548624763d82bf5a592128',20010213165908,20010213165908,20010213165908);
INSERT INTO eZSession_Session VALUES (66,'9d84d03c0f1686ea11e44378da6a2be2',20010213165909,20010213165909,20010213165909);
INSERT INTO eZSession_Session VALUES (67,'1e0a0d9bdaf616fa179d2a979e2652f1',20010213165909,20010213165909,20010213165909);
INSERT INTO eZSession_Session VALUES (68,'1f9dc35e5140d8bca36f6656cedeeb1d',20010213165909,20010213165909,20010213165909);
INSERT INTO eZSession_Session VALUES (69,'0a8ab52eec0cde420655c4692669f92e',20010213165910,20010213165910,20010213165910);
INSERT INTO eZSession_Session VALUES (70,'1bff82b94d21d4329a02f6a1d6205663',20010213165910,20010213165910,20010213165910);
INSERT INTO eZSession_Session VALUES (71,'29477caa5f93a6eace97048c274ddfb1',20010213165910,20010213165910,20010213165910);
INSERT INTO eZSession_Session VALUES (72,'4b3cef802698951e0e7b86241366f8d4',20010213165910,20010213165910,20010213165910);
INSERT INTO eZSession_Session VALUES (73,'3c0da17ca0b7d8ed0e4a3229d85ca1c4',20010213165910,20010213165910,20010213165910);
INSERT INTO eZSession_Session VALUES (74,'5b8188d4a9e7b124ed8654665350caf8',20010213165910,20010213165910,20010213165910);
INSERT INTO eZSession_Session VALUES (75,'b2067ddaf440534eac3215c066987169',20010213165911,20010213165911,20010213165911);
INSERT INTO eZSession_Session VALUES (76,'ba610bdf58181297b22490e1915b2149',20010213165911,20010213165911,20010213165911);
INSERT INTO eZSession_Session VALUES (77,'95c6309c9638221a2f268b38a3a45ab2',20010213165911,20010213165911,20010213165911);
INSERT INTO eZSession_Session VALUES (78,'97a7f30c365dad59d5874648b373daf1',20010213165911,20010213165911,20010213165911);
INSERT INTO eZSession_Session VALUES (79,'330484769a0d878cd745b4a63bd9421a',20010213165911,20010213165911,20010213165911);
INSERT INTO eZSession_Session VALUES (80,'a1d883f3a066c686b1b8edd7876d9e1d',20010213165911,20010213165911,20010213165911);
INSERT INTO eZSession_Session VALUES (81,'640f4f013be1088593b92c5e94a6f63a',20010213165912,20010213165912,20010213165912);
INSERT INTO eZSession_Session VALUES (82,'647cf3b8545e0e6573b5cb0a28b47708',20010213165912,20010213165912,20010213165912);
INSERT INTO eZSession_Session VALUES (83,'b9cb53a45bc1269c06faf9bf2e829be7',20010213165912,20010213165912,20010213165912);
INSERT INTO eZSession_Session VALUES (84,'56e325ec59adec057b50225ff1bfd00d',20010213165912,20010213165912,20010213165912);
INSERT INTO eZSession_Session VALUES (85,'e7f1ebd837cd62d3a6444fe740d3daca',20010213165913,20010213165913,20010213165913);
INSERT INTO eZSession_Session VALUES (86,'3781f36d309b312471ffbea7a2cc4928',20010213165913,20010213165913,20010213165913);
INSERT INTO eZSession_Session VALUES (87,'4fd5bdeabec94f5d4849e9b13e63b8c8',20010213165913,20010213165913,20010213165913);
INSERT INTO eZSession_Session VALUES (88,'accb5d36e946388430c80fd478c3b47b',20010213165914,20010213165914,20010213165914);
INSERT INTO eZSession_Session VALUES (89,'c7f2dca496a8fd447aaa2f6a8440b07e',20010213165914,20010213165914,20010213165914);
INSERT INTO eZSession_Session VALUES (90,'5a405a61dd1ab93a2791a8ce7690c46e',20010213165914,20010213165914,20010213165914);
INSERT INTO eZSession_Session VALUES (91,'6a491207d2fa36ac739ce668d09f7487',20010213165915,20010213165915,20010213165915);
INSERT INTO eZSession_Session VALUES (92,'706f8b0dbfe105990546c42ac8a23796',20010213165915,20010213165915,20010213165915);
INSERT INTO eZSession_Session VALUES (93,'6af151efd345320c754c85c83fb111f6',20010213165915,20010213165915,20010213165915);
INSERT INTO eZSession_Session VALUES (94,'39ffc8d66bb01b3a181ecadef345b241',20010213165916,20010213165916,20010213165916);
INSERT INTO eZSession_Session VALUES (95,'cbeb10842c73cb42ae85364b4a8297bb',20010213165916,20010213165916,20010213165916);
INSERT INTO eZSession_Session VALUES (96,'63fa2ae7484f738209dd6c4668b8ce06',20010213165916,20010213165916,20010213165916);
INSERT INTO eZSession_Session VALUES (97,'28d27bb7b689028c5f2cade64e92a417',20010213165916,20010213165916,20010213165916);
INSERT INTO eZSession_Session VALUES (98,'de3cfce91ae0eb30d5c0cd09294ce570',20010213165917,20010213165917,20010213165917);
INSERT INTO eZSession_Session VALUES (99,'3b4e492a4ba305e951503b761aa50743',20010213165917,20010213165917,20010213165917);
INSERT INTO eZSession_Session VALUES (100,'350dcbc47fe3b6be6ee071a882eeade9',20010213165917,20010213165917,20010213165917);
INSERT INTO eZSession_Session VALUES (101,'ebbb130ce4e2f9508f23dc2d286a137d',20010213165918,20010213165918,20010213165918);
INSERT INTO eZSession_Session VALUES (102,'754f5a359f45725d0b05b341b6075ef4',20010213165918,20010213165918,20010213165918);
INSERT INTO eZSession_Session VALUES (103,'336b92ab860f75221af6944bb64b2851',20010213165918,20010213165918,20010213165918);
INSERT INTO eZSession_Session VALUES (104,'59d66d31691b6a0a92c2b5ef22fe849d',20010213165919,20010213165919,20010213165919);
INSERT INTO eZSession_Session VALUES (105,'3801c31a11c582d9f7e520d5f8237824',20010213165919,20010213165919,20010213165919);
INSERT INTO eZSession_Session VALUES (106,'60cdd4d2d642b0c2d67477ff9ce123bd',20010213165919,20010213165919,20010213165919);
INSERT INTO eZSession_Session VALUES (107,'d044621afc3da1402a048e12f8b9069d',20010213165919,20010213165919,20010213165919);
INSERT INTO eZSession_Session VALUES (108,'a69a85659cf1b48cec12afb275316602',20010213165920,20010213165920,20010213165920);
INSERT INTO eZSession_Session VALUES (109,'7c6400da15f30d064eaa605f6ed025b4',20010213165920,20010213165920,20010213165920);
INSERT INTO eZSession_Session VALUES (110,'436b8432306f16f97fa2cfb5a37d6d87',20010213165920,20010213165920,20010213165920);
INSERT INTO eZSession_Session VALUES (111,'3df49babc49e439877b3190b0a85f369',20010213165921,20010213165921,20010213165921);
INSERT INTO eZSession_Session VALUES (112,'6ff13c50b2bf253e0150c2b4eef5625e',20010213165921,20010213165921,20010213165921);
INSERT INTO eZSession_Session VALUES (113,'31901426c50c489dc79cafba48b3156e',20010213165921,20010213165921,20010213165921);
INSERT INTO eZSession_Session VALUES (114,'22110d2e97fe302fba99636ff9cb7c65',20010213165922,20010213165922,20010213165922);
INSERT INTO eZSession_Session VALUES (115,'5b04d940c635ae3a9cfd172bdb4ca6c0',20010213165922,20010213165922,20010213165922);
INSERT INTO eZSession_Session VALUES (116,'fdf173114038dd7d796b4ee0d2e6a0de',20010213165922,20010213165922,20010213165922);
INSERT INTO eZSession_Session VALUES (117,'6b4f79fa28d0b708ed3640d2a5fca1c7',20010213165922,20010213165922,20010213165922);
INSERT INTO eZSession_Session VALUES (118,'c6dde5dc80746d57dc4712c2ec811831',20010213165923,20010213165923,20010213165923);
INSERT INTO eZSession_Session VALUES (119,'61aeea4c455da322a20a9e74733676a8',20010213165923,20010213165923,20010213165923);
INSERT INTO eZSession_Session VALUES (120,'8a9a4635ea0795ccae1de9cce8acc997',20010213165923,20010213165923,20010213165923);
INSERT INTO eZSession_Session VALUES (121,'56d32005027135758133e47965657885',20010213165924,20010213165924,20010213165924);
INSERT INTO eZSession_Session VALUES (122,'34847eb68eccf97994de889d55332bbe',20010213165924,20010213165924,20010213165924);
INSERT INTO eZSession_Session VALUES (123,'3051965afab4ca02538a989aad64666e',20010213165924,20010213165924,20010213165924);
INSERT INTO eZSession_Session VALUES (124,'2622b142d82beac1dd30d4a03d1d2b17',20010213165925,20010213165925,20010213165925);
INSERT INTO eZSession_Session VALUES (125,'8bc17569822196745792d99cfbf2bc86',20010213165925,20010213165925,20010213165925);
INSERT INTO eZSession_Session VALUES (126,'a1753fae487e7aaec02437408fabe9be',20010213165930,20010213165930,20010213165930);
INSERT INTO eZSession_Session VALUES (127,'1099ffebbcaef9dc20afd579a41ee5a3',20010213165930,20010213165930,20010213165930);
INSERT INTO eZSession_Session VALUES (128,'5e727a6265a38f50c10b9a70490e3629',20010213165931,20010213165931,20010213165931);
INSERT INTO eZSession_Session VALUES (129,'61cb7a29d06607d1f82f9621f6a3cdd4',20010213165931,20010213165931,20010213165931);
INSERT INTO eZSession_Session VALUES (130,'6b40f9561b39a0e0658f731389e89b9b',20010213165931,20010213165931,20010213165931);
INSERT INTO eZSession_Session VALUES (131,'9a4c8cb329b744b869c67f1294f0b0e3',20010213165932,20010213165932,20010213165932);
INSERT INTO eZSession_Session VALUES (132,'d87cfb8fdf3a06bb94bc2bdf30c04331',20010213165932,20010213165932,20010213165932);
INSERT INTO eZSession_Session VALUES (133,'dbc6638706a77f41b1351258673fd375',20010213165932,20010213165932,20010213165932);
INSERT INTO eZSession_Session VALUES (134,'4ebbdd75132112969765c455bfd47200',20010213165932,20010213165932,20010213165932);
INSERT INTO eZSession_Session VALUES (135,'a49719d48983e026913593f0fa95d4f9',20010213165932,20010213165932,20010213165932);
INSERT INTO eZSession_Session VALUES (136,'9c0735f7dfc891cd823b29ed271455b8',20010213165933,20010213165933,20010213165933);
INSERT INTO eZSession_Session VALUES (137,'e64aa9a00bba512ce5474f3e293d3425',20010213165933,20010213165933,20010213165933);
INSERT INTO eZSession_Session VALUES (138,'cb1cb60792d0d2bf8b40df133ce253d4',20010213165933,20010213165933,20010213165933);
INSERT INTO eZSession_Session VALUES (139,'893e6e46c328cf431e4ee2442c7c421d',20010213165933,20010213165933,20010213165933);
INSERT INTO eZSession_Session VALUES (140,'0d240ed2690654608d6a8803983bec94',20010213165933,20010213165933,20010213165933);
INSERT INTO eZSession_Session VALUES (141,'7d9e8eadb4c9fc8c3256c00cfd294eaa',20010213165934,20010213165934,20010213165934);
INSERT INTO eZSession_Session VALUES (142,'ed0670a43aa1846ed96006af8c2125a2',20010213165934,20010213165934,20010213165934);
INSERT INTO eZSession_Session VALUES (143,'46aa5d63c9c25520360299a298f1925f',20010213165934,20010213165934,20010213165934);
INSERT INTO eZSession_Session VALUES (144,'51d5e43690410e7c5a6dc0fecc5a8ecf',20010213165934,20010213165934,20010213165934);
INSERT INTO eZSession_Session VALUES (145,'e43c0a554364ac2f3d48f01fc3016df8',20010213165934,20010213165934,20010213165934);
INSERT INTO eZSession_Session VALUES (146,'0edb9812bcb95c4737b0c9bb4196ece3',20010213165934,20010213165934,20010213165934);
INSERT INTO eZSession_Session VALUES (147,'82c8f63e51613611a1b4cbe388e9d19c',20010213165935,20010213165935,20010213165935);
INSERT INTO eZSession_Session VALUES (148,'de0b0e1df8ea274b4960b5df26dd073e',20010213165935,20010213165935,20010213165935);
INSERT INTO eZSession_Session VALUES (149,'dcb0d9505baca0a9639cd6fce40774f5',20010213165935,20010213165935,20010213165935);
INSERT INTO eZSession_Session VALUES (150,'152fe486b1c67524c40d36e579eb10b3',20010213165935,20010213165935,20010213165935);
INSERT INTO eZSession_Session VALUES (151,'1bad6cedb7cbb97335199b397297da15',20010213165935,20010213165935,20010213165935);
INSERT INTO eZSession_Session VALUES (152,'db294960cbb97db1bccc157869ca61e4',20010213165935,20010213165935,20010213165935);
INSERT INTO eZSession_Session VALUES (153,'0e455960b88946af99ddc6af1919d515',20010213165936,20010213165936,20010213165936);
INSERT INTO eZSession_Session VALUES (154,'a9149d3d004f79a221e1ea11c3295c77',20010213165936,20010213165936,20010213165936);
INSERT INTO eZSession_Session VALUES (155,'1db18f5bc84e8ea0ae2f826adfc83553',20010213165936,20010213165936,20010213165936);
INSERT INTO eZSession_Session VALUES (156,'b5078daa71c9306a2d9f6a8db1bb5347',20010213165936,20010213165936,20010213165936);
INSERT INTO eZSession_Session VALUES (157,'385301b7383e38194486655eade0e2eb',20010213165936,20010213165936,20010213165936);
INSERT INTO eZSession_Session VALUES (158,'faece4c7cad53b6443d97f874ea75589',20010213165936,20010213165936,20010213165936);
INSERT INTO eZSession_Session VALUES (159,'ce9ff532ff60f6b08d0601c44fdb36b5',20010213165937,20010213165937,20010213165937);
INSERT INTO eZSession_Session VALUES (160,'7b693afe42ff2d83a188a6e86efdc11f',20010213165937,20010213165937,20010213165937);
INSERT INTO eZSession_Session VALUES (161,'3aa7e38ed7a685276dc424ac515420de',20010213165937,20010213165937,20010213165937);
INSERT INTO eZSession_Session VALUES (162,'f43394f25a30ea06228a863c1ce37c16',20010213165937,20010213165937,20010213165937);
INSERT INTO eZSession_Session VALUES (163,'5c965f382340070cb8f4fb0d6c64b0a6',20010213165937,20010213165937,20010213165937);
INSERT INTO eZSession_Session VALUES (164,'b82a9b0cbdf28aef7f61c976ce45230e',20010213165937,20010213165937,20010213165937);
INSERT INTO eZSession_Session VALUES (165,'f9b04979a1ec33c055ae3c1aa7b2b103',20010213165938,20010213165938,20010213165938);
INSERT INTO eZSession_Session VALUES (166,'da8abaa047be12af0ede665ae47d2aac',20010213165938,20010213165938,20010213165938);
INSERT INTO eZSession_Session VALUES (167,'580959edfea70cf802c89fdd58b22e01',20010213165938,20010213165938,20010213165938);
INSERT INTO eZSession_Session VALUES (168,'efd1789b1d880644a16e374985ac0a0f',20010213165938,20010213165938,20010213165938);
INSERT INTO eZSession_Session VALUES (169,'2f4fdb1e3abda21547741c82f12d9deb',20010213165938,20010213165938,20010213165938);
INSERT INTO eZSession_Session VALUES (170,'23f08312a1b6344f59b999c016b652ae',20010213165939,20010213165939,20010213165939);
INSERT INTO eZSession_Session VALUES (171,'f992a905befe56b906e5b116eeff3195',20010213165939,20010213165939,20010213165939);
INSERT INTO eZSession_Session VALUES (172,'b7be23c25e17f72b0026ce27a2abcfeb',20010213165939,20010213165939,20010213165939);
INSERT INTO eZSession_Session VALUES (173,'74e4b492ce56f768e6092f4c0318b6fc',20010213165939,20010213165939,20010213165939);
INSERT INTO eZSession_Session VALUES (174,'8d5a13b43de1fab549ad209ac27994aa',20010213165939,20010213165939,20010213165939);
INSERT INTO eZSession_Session VALUES (175,'1dcc882b825e38d7debc60645491df5f',20010213165939,20010213165939,20010213165939);
INSERT INTO eZSession_Session VALUES (176,'477721c0f3d61a11c8abd8b5e6071b69',20010213165940,20010213165940,20010213165940);
INSERT INTO eZSession_Session VALUES (177,'08f461aeaf6765b1ec675860482037c4',20010213165940,20010213165940,20010213165940);
INSERT INTO eZSession_Session VALUES (178,'917ab744d9cf3edc897ac91f6189863f',20010213165940,20010213165940,20010213165940);
INSERT INTO eZSession_Session VALUES (179,'85d13f703b7293e14cc37f3969f6d4cd',20010213165940,20010213165940,20010213165940);
INSERT INTO eZSession_Session VALUES (180,'6bca64423328bcea0d45e36343713a8e',20010213165940,20010213165940,20010213165940);
INSERT INTO eZSession_Session VALUES (181,'555178368af8ab1e45067ed25edcc547',20010213165940,20010213165940,20010213165940);
INSERT INTO eZSession_Session VALUES (182,'3f3e7d44060668067fdbf4da6bd30e5e',20010213165940,20010213165940,20010213165940);
INSERT INTO eZSession_Session VALUES (183,'3e78542a7985b552a40cbb4818d9ff19',20010213165941,20010213165941,20010213165941);
INSERT INTO eZSession_Session VALUES (184,'b55cd933495175520c3d321205838d0c',20010213165941,20010213165941,20010213165941);
INSERT INTO eZSession_Session VALUES (185,'478040bfe4b2ca0f231448102fe04b59',20010213165941,20010213165941,20010213165941);
INSERT INTO eZSession_Session VALUES (186,'516c8dee7194189299984eca77938ffc',20010213165941,20010213165941,20010213165941);
INSERT INTO eZSession_Session VALUES (187,'fcd8b03810731710c2b5cfa2d4e8e140',20010213165941,20010213165941,20010213165941);
INSERT INTO eZSession_Session VALUES (188,'1b3e5a6004b5e8490643a850d5ef29e0',20010213165942,20010213165942,20010213165942);
INSERT INTO eZSession_Session VALUES (189,'a2ae0e1f676b06e06f134a57cb75aa4c',20010213165942,20010213165942,20010213165942);
INSERT INTO eZSession_Session VALUES (190,'100037f6e94144821e48aac3d8ff56ef',20010213165942,20010213165942,20010213165942);
INSERT INTO eZSession_Session VALUES (191,'d5a2d7e82929e61522fe606dff57cb9d',20010213165942,20010213165942,20010213165942);
INSERT INTO eZSession_Session VALUES (192,'00d506d382d5af240e04c1d44912d21f',20010213165942,20010213165942,20010213165942);
INSERT INTO eZSession_Session VALUES (193,'00c7427cf1c084b48437bdcf97c5c4d0',20010213165942,20010213165942,20010213165942);
INSERT INTO eZSession_Session VALUES (194,'f5738326750408d903a926d046cfe2f6',20010213165943,20010213165943,20010213165943);
INSERT INTO eZSession_Session VALUES (195,'1c2cfef266cf11916c31f540775375dd',20010213165943,20010213165943,20010213165943);
INSERT INTO eZSession_Session VALUES (196,'c6d99fef4f03246b542df6694647996e',20010213165943,20010213165943,20010213165943);
INSERT INTO eZSession_Session VALUES (197,'32be22f9861e69429832ceded9ff4f98',20010213165943,20010213165943,20010213165943);
INSERT INTO eZSession_Session VALUES (198,'b83b99424177f8015b8486a090f0e0be',20010213165943,20010213165943,20010213165943);
INSERT INTO eZSession_Session VALUES (199,'6ce11c17fc7fa2971f9034bc17184394',20010213165943,20010213165943,20010213165943);
INSERT INTO eZSession_Session VALUES (200,'0c8a59f84db7e8f95a32c3388902ade1',20010213165944,20010213165944,20010213165944);
INSERT INTO eZSession_Session VALUES (201,'ae1645f995745ef90c7626ebf7db2bea',20010213165944,20010213165944,20010213165944);
INSERT INTO eZSession_Session VALUES (202,'37348b46dab016c5d8e912dc0161a44d',20010213165944,20010213165944,20010213165944);
INSERT INTO eZSession_Session VALUES (203,'e75181a882bae92798a4381dfe4dbab9',20010213165944,20010213165944,20010213165944);
INSERT INTO eZSession_Session VALUES (204,'6726bf21887e98c6cd605abc44cdec57',20010213165944,20010213165944,20010213165944);
INSERT INTO eZSession_Session VALUES (205,'2576086e57d298ba28eb64978954577a',20010213165944,20010213165944,20010213165944);
INSERT INTO eZSession_Session VALUES (206,'c4bf5d7becf7a0e95d79cc71b337e865',20010213165945,20010213165945,20010213165945);
INSERT INTO eZSession_Session VALUES (207,'6f94325a8627c7e7fece23cf16120454',20010213165945,20010213165945,20010213165945);
INSERT INTO eZSession_Session VALUES (208,'e0a82d7514689988a84475be6ad6ab6b',20010213165945,20010213165945,20010213165945);
INSERT INTO eZSession_Session VALUES (209,'d6abae0f2379b15ff909f3ab0fb57f35',20010213165945,20010213165945,20010213165945);
INSERT INTO eZSession_Session VALUES (210,'596983e19ef2b670c3572ad4c7632443',20010213165945,20010213165945,20010213165945);
INSERT INTO eZSession_Session VALUES (211,'178140e82d8a29b188df681c95e5f128',20010213165946,20010213165946,20010213165946);
INSERT INTO eZSession_Session VALUES (212,'366e305e2969da21f0b3acfb93f427e1',20010213165946,20010213165946,20010213165946);
INSERT INTO eZSession_Session VALUES (213,'c37bc7c0d35ceef4f598ac3c0e28bca9',20010213165946,20010213165946,20010213165946);
INSERT INTO eZSession_Session VALUES (214,'23794df694fe56128454ae53c80d4a0a',20010213165946,20010213165946,20010213165946);
INSERT INTO eZSession_Session VALUES (215,'dc70be06fd00056e007a683625a8e0b5',20010213165946,20010213165946,20010213165946);
INSERT INTO eZSession_Session VALUES (216,'fe5ea1a3dbde3c00078f40ab96d66631',20010213165946,20010213165946,20010213165946);
INSERT INTO eZSession_Session VALUES (217,'cb36745d81eaf40d2cc689ad7ce3a8e1',20010213165947,20010213165947,20010213165947);
INSERT INTO eZSession_Session VALUES (218,'484f8b57566368ac155e5f1c27bc3dda',20010213165947,20010213165947,20010213165947);
INSERT INTO eZSession_Session VALUES (219,'a38ca41bd17a0f7d00240458c6cea1d4',20010213165947,20010213165947,20010213165947);
INSERT INTO eZSession_Session VALUES (220,'d4d8229ab0f964b4eb5e2af99abc58f7',20010213165947,20010213165947,20010213165947);
INSERT INTO eZSession_Session VALUES (221,'871f4f0cb891944a4debddb227f2da9b',20010213165947,20010213165947,20010213165947);
INSERT INTO eZSession_Session VALUES (222,'7b1ab997bab1337b210ab1fb373ce7fa',20010213165948,20010213165948,20010213165948);
INSERT INTO eZSession_Session VALUES (223,'15a56d962e9b883a20c6d61cd314b3e8',20010213165948,20010213165948,20010213165948);
INSERT INTO eZSession_Session VALUES (224,'476ba993bb6ca10f0df533545afa204b',20010213165948,20010213165948,20010213165948);
INSERT INTO eZSession_Session VALUES (225,'90d4db0eed983b09fa5e751bcef4da67',20010213165948,20010213165948,20010213165948);
INSERT INTO eZSession_Session VALUES (226,'2c3493f1c8c1f97187d8de139bdd6772',20010213165949,20010213165949,20010213165949);
INSERT INTO eZSession_Session VALUES (227,'f1bbfe73426bfc633be828b8c9845115',20010213165949,20010213165949,20010213165949);
INSERT INTO eZSession_Session VALUES (228,'6455afa56ca3f60b11ae18533aefe97e',20010213170046,20010213170046,20010213170046);
INSERT INTO eZSession_Session VALUES (229,'c6220bdbabd637186f25b711077356cd',20010213170046,20010213170046,20010213170046);
INSERT INTO eZSession_Session VALUES (230,'31fcfa3a2a151200fc7262124586123c',20010213170047,20010213170047,20010213170047);
INSERT INTO eZSession_Session VALUES (231,'df1f8e194c6cf0a551b606da0ab594ee',20010213170047,20010213170047,20010213170047);
INSERT INTO eZSession_Session VALUES (232,'afc7c8776d33a523cf53505ab0f9cb1d',20010213170047,20010213170047,20010213170047);
INSERT INTO eZSession_Session VALUES (233,'9d56b1e4eeebe0aa9c215043a5921b8a',20010213170048,20010213170048,20010213170048);

#
# Table structure for table 'eZSession_SessionVariable'
#

DROP TABLE IF EXISTS eZSession_SessionVariable;
CREATE TABLE eZSession_SessionVariable (
  ID int(11) NOT NULL auto_increment,
  SessionID int(11) default NULL,
  Name char(25) default NULL,
  Value char(50) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZSession_SessionVariable'
#

INSERT INTO eZSession_SessionVariable VALUES (1,1,'SessionIP','10.0.2.3');
INSERT INTO eZSession_SessionVariable VALUES (2,1,'SiteDesign','standard');
INSERT INTO eZSession_SessionVariable VALUES (3,2,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (4,3,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (5,4,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (6,5,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (7,6,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (8,7,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (9,8,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (10,9,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (11,10,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (12,11,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (13,12,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (14,13,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (15,14,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (16,15,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (17,16,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (18,17,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (19,18,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (20,19,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (21,20,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (22,21,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (23,22,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (24,23,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (25,24,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (26,25,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (27,26,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (28,27,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (29,28,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (30,29,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (31,30,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (32,31,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (33,32,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (34,33,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (35,34,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (36,35,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (37,36,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (38,37,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (39,38,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (40,39,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (41,40,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (42,41,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (43,42,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (44,43,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (45,44,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (46,45,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (47,46,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (48,47,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (49,48,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (50,49,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (51,50,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (52,51,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (53,52,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (54,53,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (55,54,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (56,55,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (57,56,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (58,57,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (59,58,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (60,59,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (61,60,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (62,61,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (63,62,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (64,63,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (65,64,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (66,65,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (67,66,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (68,67,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (69,68,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (70,69,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (71,70,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (72,71,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (73,72,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (74,73,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (75,74,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (76,75,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (77,76,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (78,77,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (79,78,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (80,79,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (81,80,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (82,81,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (83,82,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (84,83,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (85,84,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (86,85,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (87,86,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (88,87,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (89,88,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (90,89,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (91,90,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (92,91,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (93,92,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (94,93,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (95,94,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (96,95,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (97,96,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (98,97,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (99,98,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (100,99,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (101,100,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (102,101,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (103,102,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (104,103,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (105,104,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (106,105,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (107,106,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (108,107,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (109,108,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (110,109,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (111,110,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (112,111,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (113,112,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (114,113,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (115,114,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (116,115,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (117,116,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (118,117,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (119,118,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (120,119,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (121,120,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (122,121,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (123,122,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (124,123,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (125,124,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (126,125,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (127,126,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (128,127,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (129,128,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (130,129,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (131,130,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (132,131,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (133,132,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (134,133,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (135,134,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (136,135,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (137,136,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (138,137,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (139,138,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (140,139,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (141,140,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (142,141,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (143,142,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (144,143,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (145,144,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (146,145,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (147,146,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (148,147,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (149,148,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (150,149,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (151,150,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (152,151,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (153,152,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (154,153,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (155,154,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (156,155,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (157,156,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (158,157,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (159,158,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (160,159,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (161,160,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (162,161,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (163,162,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (164,163,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (165,164,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (166,165,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (167,166,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (168,167,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (169,168,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (170,169,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (171,170,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (172,171,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (173,172,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (174,173,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (175,174,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (176,175,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (177,176,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (178,177,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (179,178,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (180,179,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (181,180,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (182,181,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (183,182,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (184,183,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (185,184,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (186,185,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (187,186,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (188,187,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (189,188,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (190,189,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (191,190,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (192,191,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (193,192,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (194,193,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (195,194,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (196,195,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (197,196,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (198,197,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (199,198,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (200,199,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (201,200,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (202,201,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (203,202,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (204,203,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (205,204,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (206,205,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (207,206,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (208,207,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (209,208,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (210,209,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (211,210,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (212,211,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (213,212,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (214,213,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (215,214,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (216,215,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (217,216,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (218,217,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (219,218,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (220,219,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (221,220,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (222,221,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (223,222,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (224,223,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (225,224,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (226,225,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (227,226,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (228,227,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (229,228,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (230,229,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (231,230,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (232,231,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (233,232,'SessionIP','10.0.2.200');
INSERT INTO eZSession_SessionVariable VALUES (234,233,'SessionIP','10.0.2.200');

#
# Table structure for table 'eZStats_BrowserType'
#

DROP TABLE IF EXISTS eZStats_BrowserType;
CREATE TABLE eZStats_BrowserType (
  ID int(11) NOT NULL auto_increment,
  BrowserType char(250) NOT NULL default '',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZStats_BrowserType'
#

INSERT INTO eZStats_BrowserType VALUES (1,'Mozilla/4.76 [en] (X11; U; Linux 2.4.0-0.26smp i686)');
INSERT INTO eZStats_BrowserType VALUES (2,'PHP/4.0.4pl1');

#
# Table structure for table 'eZStats_PageView'
#

DROP TABLE IF EXISTS eZStats_PageView;
CREATE TABLE eZStats_PageView (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  BrowserTypeID int(11) NOT NULL default '0',
  RemoteHostID int(11) NOT NULL default '0',
  RefererURLID int(11) NOT NULL default '0',
  Date timestamp(14) NOT NULL,
  RequestPageID int(11) NOT NULL default '0',
  DateValue date NOT NULL default '0000-00-00',
  TimeValue time NOT NULL default '00:00:00',
  PRIMARY KEY (ID),
  KEY DateValue(DateValue),
  KEY TimeValue(TimeValue),
  KEY Date(Date)
) TYPE=MyISAM;

#
# Dumping data for table 'eZStats_PageView'
#

INSERT INTO eZStats_PageView VALUES (1,0,1,1,1,20010213165753,1,'2001-02-13','16:57:53');
INSERT INTO eZStats_PageView VALUES (2,0,1,1,1,20010213165800,1,'2001-02-13','16:58:00');
INSERT INTO eZStats_PageView VALUES (3,0,1,1,1,20010213165803,1,'2001-02-13','16:58:03');
INSERT INTO eZStats_PageView VALUES (4,0,1,1,2,20010213165805,1,'2001-02-13','16:58:05');
INSERT INTO eZStats_PageView VALUES (5,0,1,1,2,20010213165805,1,'2001-02-13','16:58:05');
INSERT INTO eZStats_PageView VALUES (6,0,1,1,3,20010213165807,1,'2001-02-13','16:58:07');
INSERT INTO eZStats_PageView VALUES (7,0,1,1,3,20010213165808,1,'2001-02-13','16:58:08');
INSERT INTO eZStats_PageView VALUES (8,0,1,1,3,20010213165810,2,'2001-02-13','16:58:10');
INSERT INTO eZStats_PageView VALUES (9,0,1,1,4,20010213165812,3,'2001-02-13','16:58:12');
INSERT INTO eZStats_PageView VALUES (10,0,1,1,1,20010213165815,1,'2001-02-13','16:58:15');
INSERT INTO eZStats_PageView VALUES (11,0,2,2,5,20010213165836,1,'2001-02-13','16:58:36');
INSERT INTO eZStats_PageView VALUES (12,0,2,2,5,20010213165836,1,'2001-02-13','16:58:36');
INSERT INTO eZStats_PageView VALUES (13,0,2,2,5,20010213165836,1,'2001-02-13','16:58:36');
INSERT INTO eZStats_PageView VALUES (14,0,2,2,5,20010213165837,1,'2001-02-13','16:58:37');
INSERT INTO eZStats_PageView VALUES (15,0,2,2,5,20010213165837,1,'2001-02-13','16:58:37');
INSERT INTO eZStats_PageView VALUES (16,0,2,2,5,20010213165837,1,'2001-02-13','16:58:37');
INSERT INTO eZStats_PageView VALUES (17,0,2,2,5,20010213165843,1,'2001-02-13','16:58:43');
INSERT INTO eZStats_PageView VALUES (18,0,2,2,5,20010213165843,1,'2001-02-13','16:58:43');
INSERT INTO eZStats_PageView VALUES (19,0,2,2,5,20010213165844,1,'2001-02-13','16:58:44');
INSERT INTO eZStats_PageView VALUES (20,0,2,2,5,20010213165844,1,'2001-02-13','16:58:44');
INSERT INTO eZStats_PageView VALUES (21,0,2,2,5,20010213165844,1,'2001-02-13','16:58:44');
INSERT INTO eZStats_PageView VALUES (22,0,2,2,5,20010213165844,1,'2001-02-13','16:58:44');
INSERT INTO eZStats_PageView VALUES (23,0,2,2,5,20010213165845,1,'2001-02-13','16:58:45');
INSERT INTO eZStats_PageView VALUES (24,0,2,2,5,20010213165845,1,'2001-02-13','16:58:45');
INSERT INTO eZStats_PageView VALUES (25,0,2,2,5,20010213165845,1,'2001-02-13','16:58:45');
INSERT INTO eZStats_PageView VALUES (26,0,2,2,5,20010213165846,1,'2001-02-13','16:58:46');
INSERT INTO eZStats_PageView VALUES (27,0,2,2,5,20010213165846,1,'2001-02-13','16:58:46');
INSERT INTO eZStats_PageView VALUES (28,0,2,2,5,20010213165854,1,'2001-02-13','16:58:54');
INSERT INTO eZStats_PageView VALUES (29,0,2,2,5,20010213165854,1,'2001-02-13','16:58:54');
INSERT INTO eZStats_PageView VALUES (30,0,2,2,5,20010213165855,1,'2001-02-13','16:58:55');
INSERT INTO eZStats_PageView VALUES (31,0,2,2,5,20010213165855,1,'2001-02-13','16:58:55');
INSERT INTO eZStats_PageView VALUES (32,0,2,2,5,20010213165855,1,'2001-02-13','16:58:55');
INSERT INTO eZStats_PageView VALUES (33,0,2,2,5,20010213165856,1,'2001-02-13','16:58:56');
INSERT INTO eZStats_PageView VALUES (34,0,2,2,5,20010213165856,1,'2001-02-13','16:58:56');
INSERT INTO eZStats_PageView VALUES (35,0,2,2,5,20010213165856,1,'2001-02-13','16:58:56');
INSERT INTO eZStats_PageView VALUES (36,0,2,2,5,20010213165857,1,'2001-02-13','16:58:57');
INSERT INTO eZStats_PageView VALUES (37,0,2,2,5,20010213165857,1,'2001-02-13','16:58:57');
INSERT INTO eZStats_PageView VALUES (38,0,2,2,5,20010213165857,1,'2001-02-13','16:58:57');
INSERT INTO eZStats_PageView VALUES (39,0,2,2,5,20010213165857,1,'2001-02-13','16:58:57');
INSERT INTO eZStats_PageView VALUES (40,0,2,2,5,20010213165858,1,'2001-02-13','16:58:58');
INSERT INTO eZStats_PageView VALUES (41,0,2,2,5,20010213165858,1,'2001-02-13','16:58:58');
INSERT INTO eZStats_PageView VALUES (42,0,2,2,5,20010213165858,1,'2001-02-13','16:58:58');
INSERT INTO eZStats_PageView VALUES (43,0,2,2,5,20010213165859,1,'2001-02-13','16:58:59');
INSERT INTO eZStats_PageView VALUES (44,0,2,2,5,20010213165859,1,'2001-02-13','16:58:59');
INSERT INTO eZStats_PageView VALUES (45,0,2,2,5,20010213165859,1,'2001-02-13','16:58:59');
INSERT INTO eZStats_PageView VALUES (46,0,2,2,5,20010213165900,1,'2001-02-13','16:59:00');
INSERT INTO eZStats_PageView VALUES (47,0,2,2,5,20010213165900,1,'2001-02-13','16:59:00');
INSERT INTO eZStats_PageView VALUES (48,0,2,2,5,20010213165900,1,'2001-02-13','16:59:00');
INSERT INTO eZStats_PageView VALUES (49,0,2,2,5,20010213165901,1,'2001-02-13','16:59:01');
INSERT INTO eZStats_PageView VALUES (50,0,2,2,5,20010213165901,1,'2001-02-13','16:59:01');
INSERT INTO eZStats_PageView VALUES (51,0,2,2,5,20010213165901,1,'2001-02-13','16:59:01');
INSERT INTO eZStats_PageView VALUES (52,0,2,2,5,20010213165901,1,'2001-02-13','16:59:01');
INSERT INTO eZStats_PageView VALUES (53,0,2,2,5,20010213165902,1,'2001-02-13','16:59:02');
INSERT INTO eZStats_PageView VALUES (54,0,2,2,5,20010213165902,1,'2001-02-13','16:59:02');
INSERT INTO eZStats_PageView VALUES (55,0,2,2,5,20010213165902,1,'2001-02-13','16:59:02');
INSERT INTO eZStats_PageView VALUES (56,0,2,2,5,20010213165903,1,'2001-02-13','16:59:03');
INSERT INTO eZStats_PageView VALUES (57,0,2,2,5,20010213165903,1,'2001-02-13','16:59:03');
INSERT INTO eZStats_PageView VALUES (58,0,2,2,5,20010213165903,1,'2001-02-13','16:59:03');
INSERT INTO eZStats_PageView VALUES (59,0,2,2,5,20010213165904,1,'2001-02-13','16:59:04');
INSERT INTO eZStats_PageView VALUES (60,0,2,2,5,20010213165904,1,'2001-02-13','16:59:04');
INSERT INTO eZStats_PageView VALUES (61,0,2,2,5,20010213165904,1,'2001-02-13','16:59:04');
INSERT INTO eZStats_PageView VALUES (62,0,2,2,5,20010213165904,1,'2001-02-13','16:59:04');
INSERT INTO eZStats_PageView VALUES (63,0,2,2,5,20010213165905,1,'2001-02-13','16:59:05');
INSERT INTO eZStats_PageView VALUES (64,0,2,2,5,20010213165905,1,'2001-02-13','16:59:05');
INSERT INTO eZStats_PageView VALUES (65,0,2,2,5,20010213165905,1,'2001-02-13','16:59:05');
INSERT INTO eZStats_PageView VALUES (66,0,2,2,5,20010213165906,1,'2001-02-13','16:59:06');
INSERT INTO eZStats_PageView VALUES (67,0,2,2,5,20010213165906,1,'2001-02-13','16:59:06');
INSERT INTO eZStats_PageView VALUES (68,0,2,2,5,20010213165906,1,'2001-02-13','16:59:06');
INSERT INTO eZStats_PageView VALUES (69,0,2,2,5,20010213165907,1,'2001-02-13','16:59:07');
INSERT INTO eZStats_PageView VALUES (70,0,2,2,5,20010213165907,1,'2001-02-13','16:59:07');
INSERT INTO eZStats_PageView VALUES (71,0,2,2,5,20010213165907,1,'2001-02-13','16:59:07');
INSERT INTO eZStats_PageView VALUES (72,0,2,2,5,20010213165908,1,'2001-02-13','16:59:08');
INSERT INTO eZStats_PageView VALUES (73,0,2,2,5,20010213165908,1,'2001-02-13','16:59:08');
INSERT INTO eZStats_PageView VALUES (74,0,2,2,5,20010213165908,1,'2001-02-13','16:59:08');
INSERT INTO eZStats_PageView VALUES (75,0,2,2,5,20010213165908,1,'2001-02-13','16:59:08');
INSERT INTO eZStats_PageView VALUES (76,0,2,2,5,20010213165909,1,'2001-02-13','16:59:09');
INSERT INTO eZStats_PageView VALUES (77,0,2,2,5,20010213165909,1,'2001-02-13','16:59:09');
INSERT INTO eZStats_PageView VALUES (78,0,2,2,5,20010213165909,1,'2001-02-13','16:59:09');
INSERT INTO eZStats_PageView VALUES (79,0,2,2,5,20010213165909,1,'2001-02-13','16:59:09');
INSERT INTO eZStats_PageView VALUES (80,0,2,2,5,20010213165910,1,'2001-02-13','16:59:10');
INSERT INTO eZStats_PageView VALUES (81,0,2,2,5,20010213165910,1,'2001-02-13','16:59:10');
INSERT INTO eZStats_PageView VALUES (82,0,2,2,5,20010213165910,1,'2001-02-13','16:59:10');
INSERT INTO eZStats_PageView VALUES (83,0,2,2,5,20010213165910,1,'2001-02-13','16:59:10');
INSERT INTO eZStats_PageView VALUES (84,0,2,2,5,20010213165910,1,'2001-02-13','16:59:10');
INSERT INTO eZStats_PageView VALUES (85,0,2,2,5,20010213165910,1,'2001-02-13','16:59:10');
INSERT INTO eZStats_PageView VALUES (86,0,2,2,5,20010213165911,1,'2001-02-13','16:59:11');
INSERT INTO eZStats_PageView VALUES (87,0,2,2,5,20010213165911,1,'2001-02-13','16:59:11');
INSERT INTO eZStats_PageView VALUES (88,0,2,2,5,20010213165911,1,'2001-02-13','16:59:11');
INSERT INTO eZStats_PageView VALUES (89,0,2,2,5,20010213165911,1,'2001-02-13','16:59:11');
INSERT INTO eZStats_PageView VALUES (90,0,2,2,5,20010213165911,1,'2001-02-13','16:59:11');
INSERT INTO eZStats_PageView VALUES (91,0,2,2,5,20010213165912,1,'2001-02-13','16:59:12');
INSERT INTO eZStats_PageView VALUES (92,0,2,2,5,20010213165912,1,'2001-02-13','16:59:12');
INSERT INTO eZStats_PageView VALUES (93,0,2,2,5,20010213165912,1,'2001-02-13','16:59:12');
INSERT INTO eZStats_PageView VALUES (94,0,2,2,5,20010213165913,1,'2001-02-13','16:59:13');
INSERT INTO eZStats_PageView VALUES (95,0,2,2,5,20010213165913,1,'2001-02-13','16:59:13');
INSERT INTO eZStats_PageView VALUES (96,0,2,2,5,20010213165913,1,'2001-02-13','16:59:13');
INSERT INTO eZStats_PageView VALUES (97,0,2,2,5,20010213165913,1,'2001-02-13','16:59:13');
INSERT INTO eZStats_PageView VALUES (98,0,2,2,5,20010213165914,1,'2001-02-13','16:59:14');
INSERT INTO eZStats_PageView VALUES (99,0,2,2,5,20010213165914,1,'2001-02-13','16:59:14');
INSERT INTO eZStats_PageView VALUES (100,0,2,2,5,20010213165914,1,'2001-02-13','16:59:14');
INSERT INTO eZStats_PageView VALUES (101,0,2,2,5,20010213165915,1,'2001-02-13','16:59:15');
INSERT INTO eZStats_PageView VALUES (102,0,2,2,5,20010213165915,1,'2001-02-13','16:59:15');
INSERT INTO eZStats_PageView VALUES (103,0,2,2,5,20010213165915,1,'2001-02-13','16:59:15');
INSERT INTO eZStats_PageView VALUES (104,0,2,2,5,20010213165916,1,'2001-02-13','16:59:16');
INSERT INTO eZStats_PageView VALUES (105,0,2,2,5,20010213165916,1,'2001-02-13','16:59:16');
INSERT INTO eZStats_PageView VALUES (106,0,2,2,5,20010213165916,1,'2001-02-13','16:59:16');
INSERT INTO eZStats_PageView VALUES (107,0,2,2,5,20010213165917,1,'2001-02-13','16:59:17');
INSERT INTO eZStats_PageView VALUES (108,0,2,2,5,20010213165917,1,'2001-02-13','16:59:17');
INSERT INTO eZStats_PageView VALUES (109,0,2,2,5,20010213165917,1,'2001-02-13','16:59:17');
INSERT INTO eZStats_PageView VALUES (110,0,2,2,5,20010213165917,1,'2001-02-13','16:59:17');
INSERT INTO eZStats_PageView VALUES (111,0,2,2,5,20010213165918,1,'2001-02-13','16:59:18');
INSERT INTO eZStats_PageView VALUES (112,0,2,2,5,20010213165918,1,'2001-02-13','16:59:18');
INSERT INTO eZStats_PageView VALUES (113,0,2,2,5,20010213165918,1,'2001-02-13','16:59:18');
INSERT INTO eZStats_PageView VALUES (114,0,2,2,5,20010213165919,1,'2001-02-13','16:59:19');
INSERT INTO eZStats_PageView VALUES (115,0,2,2,5,20010213165919,1,'2001-02-13','16:59:19');
INSERT INTO eZStats_PageView VALUES (116,0,2,2,5,20010213165919,1,'2001-02-13','16:59:19');
INSERT INTO eZStats_PageView VALUES (117,0,2,2,5,20010213165920,1,'2001-02-13','16:59:20');
INSERT INTO eZStats_PageView VALUES (118,0,2,2,5,20010213165920,1,'2001-02-13','16:59:20');
INSERT INTO eZStats_PageView VALUES (119,0,2,2,5,20010213165920,1,'2001-02-13','16:59:20');
INSERT INTO eZStats_PageView VALUES (120,0,2,2,5,20010213165920,1,'2001-02-13','16:59:20');
INSERT INTO eZStats_PageView VALUES (121,0,2,2,5,20010213165921,1,'2001-02-13','16:59:21');
INSERT INTO eZStats_PageView VALUES (122,0,2,2,5,20010213165921,1,'2001-02-13','16:59:21');
INSERT INTO eZStats_PageView VALUES (123,0,2,2,5,20010213165921,1,'2001-02-13','16:59:21');
INSERT INTO eZStats_PageView VALUES (124,0,2,2,5,20010213165922,1,'2001-02-13','16:59:22');
INSERT INTO eZStats_PageView VALUES (125,0,2,2,5,20010213165922,1,'2001-02-13','16:59:22');
INSERT INTO eZStats_PageView VALUES (126,0,2,2,5,20010213165922,1,'2001-02-13','16:59:22');
INSERT INTO eZStats_PageView VALUES (127,0,2,2,5,20010213165923,1,'2001-02-13','16:59:23');
INSERT INTO eZStats_PageView VALUES (128,0,2,2,5,20010213165923,1,'2001-02-13','16:59:23');
INSERT INTO eZStats_PageView VALUES (129,0,2,2,5,20010213165923,1,'2001-02-13','16:59:23');
INSERT INTO eZStats_PageView VALUES (130,0,2,2,5,20010213165923,1,'2001-02-13','16:59:23');
INSERT INTO eZStats_PageView VALUES (131,0,2,2,5,20010213165924,1,'2001-02-13','16:59:24');
INSERT INTO eZStats_PageView VALUES (132,0,2,2,5,20010213165924,1,'2001-02-13','16:59:24');
INSERT INTO eZStats_PageView VALUES (133,0,2,2,5,20010213165924,1,'2001-02-13','16:59:24');
INSERT INTO eZStats_PageView VALUES (134,0,2,2,5,20010213165925,1,'2001-02-13','16:59:25');
INSERT INTO eZStats_PageView VALUES (135,0,2,2,5,20010213165930,1,'2001-02-13','16:59:30');
INSERT INTO eZStats_PageView VALUES (136,0,2,2,5,20010213165930,1,'2001-02-13','16:59:30');
INSERT INTO eZStats_PageView VALUES (137,0,2,2,5,20010213165931,1,'2001-02-13','16:59:31');
INSERT INTO eZStats_PageView VALUES (138,0,2,2,5,20010213165931,1,'2001-02-13','16:59:31');
INSERT INTO eZStats_PageView VALUES (139,0,2,2,5,20010213165931,1,'2001-02-13','16:59:31');
INSERT INTO eZStats_PageView VALUES (140,0,2,2,5,20010213165931,1,'2001-02-13','16:59:31');
INSERT INTO eZStats_PageView VALUES (141,0,2,2,5,20010213165932,1,'2001-02-13','16:59:32');
INSERT INTO eZStats_PageView VALUES (142,0,2,2,5,20010213165932,1,'2001-02-13','16:59:32');
INSERT INTO eZStats_PageView VALUES (143,0,2,2,5,20010213165932,1,'2001-02-13','16:59:32');
INSERT INTO eZStats_PageView VALUES (144,0,2,2,5,20010213165932,1,'2001-02-13','16:59:32');
INSERT INTO eZStats_PageView VALUES (145,0,2,2,5,20010213165932,1,'2001-02-13','16:59:32');
INSERT INTO eZStats_PageView VALUES (146,0,2,2,5,20010213165933,1,'2001-02-13','16:59:33');
INSERT INTO eZStats_PageView VALUES (147,0,2,2,5,20010213165933,1,'2001-02-13','16:59:33');
INSERT INTO eZStats_PageView VALUES (148,0,2,2,5,20010213165933,1,'2001-02-13','16:59:33');
INSERT INTO eZStats_PageView VALUES (149,0,2,2,5,20010213165933,1,'2001-02-13','16:59:33');
INSERT INTO eZStats_PageView VALUES (150,0,2,2,5,20010213165933,1,'2001-02-13','16:59:33');
INSERT INTO eZStats_PageView VALUES (151,0,2,2,5,20010213165933,1,'2001-02-13','16:59:33');
INSERT INTO eZStats_PageView VALUES (152,0,2,2,5,20010213165934,1,'2001-02-13','16:59:34');
INSERT INTO eZStats_PageView VALUES (153,0,2,2,5,20010213165934,1,'2001-02-13','16:59:34');
INSERT INTO eZStats_PageView VALUES (154,0,2,2,5,20010213165934,1,'2001-02-13','16:59:34');
INSERT INTO eZStats_PageView VALUES (155,0,2,2,5,20010213165934,1,'2001-02-13','16:59:34');
INSERT INTO eZStats_PageView VALUES (156,0,2,2,5,20010213165934,1,'2001-02-13','16:59:34');
INSERT INTO eZStats_PageView VALUES (157,0,2,2,5,20010213165934,1,'2001-02-13','16:59:34');
INSERT INTO eZStats_PageView VALUES (158,0,2,2,5,20010213165935,1,'2001-02-13','16:59:35');
INSERT INTO eZStats_PageView VALUES (159,0,2,2,5,20010213165935,1,'2001-02-13','16:59:35');
INSERT INTO eZStats_PageView VALUES (160,0,2,2,5,20010213165935,1,'2001-02-13','16:59:35');
INSERT INTO eZStats_PageView VALUES (161,0,2,2,5,20010213165935,1,'2001-02-13','16:59:35');
INSERT INTO eZStats_PageView VALUES (162,0,2,2,5,20010213165935,1,'2001-02-13','16:59:35');
INSERT INTO eZStats_PageView VALUES (163,0,2,2,5,20010213165935,1,'2001-02-13','16:59:35');
INSERT INTO eZStats_PageView VALUES (164,0,2,2,5,20010213165936,1,'2001-02-13','16:59:36');
INSERT INTO eZStats_PageView VALUES (165,0,2,2,5,20010213165936,1,'2001-02-13','16:59:36');
INSERT INTO eZStats_PageView VALUES (166,0,2,2,5,20010213165936,1,'2001-02-13','16:59:36');
INSERT INTO eZStats_PageView VALUES (167,0,2,2,5,20010213165936,1,'2001-02-13','16:59:36');
INSERT INTO eZStats_PageView VALUES (168,0,2,2,5,20010213165936,1,'2001-02-13','16:59:36');
INSERT INTO eZStats_PageView VALUES (169,0,2,2,5,20010213165937,1,'2001-02-13','16:59:37');
INSERT INTO eZStats_PageView VALUES (170,0,2,2,5,20010213165937,1,'2001-02-13','16:59:37');
INSERT INTO eZStats_PageView VALUES (171,0,2,2,5,20010213165937,1,'2001-02-13','16:59:37');
INSERT INTO eZStats_PageView VALUES (172,0,2,2,5,20010213165937,1,'2001-02-13','16:59:37');
INSERT INTO eZStats_PageView VALUES (173,0,2,2,5,20010213165937,1,'2001-02-13','16:59:37');
INSERT INTO eZStats_PageView VALUES (174,0,2,2,5,20010213165937,1,'2001-02-13','16:59:37');
INSERT INTO eZStats_PageView VALUES (175,0,2,2,5,20010213165938,1,'2001-02-13','16:59:38');
INSERT INTO eZStats_PageView VALUES (176,0,2,2,5,20010213165938,1,'2001-02-13','16:59:38');
INSERT INTO eZStats_PageView VALUES (177,0,2,2,5,20010213165938,1,'2001-02-13','16:59:38');
INSERT INTO eZStats_PageView VALUES (178,0,2,2,5,20010213165938,1,'2001-02-13','16:59:38');
INSERT INTO eZStats_PageView VALUES (179,0,2,2,5,20010213165938,1,'2001-02-13','16:59:38');
INSERT INTO eZStats_PageView VALUES (180,0,2,2,5,20010213165938,1,'2001-02-13','16:59:38');
INSERT INTO eZStats_PageView VALUES (181,0,2,2,5,20010213165939,1,'2001-02-13','16:59:39');
INSERT INTO eZStats_PageView VALUES (182,0,2,2,5,20010213165939,1,'2001-02-13','16:59:39');
INSERT INTO eZStats_PageView VALUES (183,0,2,2,5,20010213165939,1,'2001-02-13','16:59:39');
INSERT INTO eZStats_PageView VALUES (184,0,2,2,5,20010213165939,1,'2001-02-13','16:59:39');
INSERT INTO eZStats_PageView VALUES (185,0,2,2,5,20010213165939,1,'2001-02-13','16:59:39');
INSERT INTO eZStats_PageView VALUES (186,0,2,2,5,20010213165939,1,'2001-02-13','16:59:39');
INSERT INTO eZStats_PageView VALUES (187,0,2,2,5,20010213165940,1,'2001-02-13','16:59:40');
INSERT INTO eZStats_PageView VALUES (188,0,2,2,5,20010213165940,1,'2001-02-13','16:59:40');
INSERT INTO eZStats_PageView VALUES (189,0,2,2,5,20010213165940,1,'2001-02-13','16:59:40');
INSERT INTO eZStats_PageView VALUES (190,0,2,2,5,20010213165940,1,'2001-02-13','16:59:40');
INSERT INTO eZStats_PageView VALUES (191,0,2,2,5,20010213165940,1,'2001-02-13','16:59:40');
INSERT INTO eZStats_PageView VALUES (192,0,2,2,5,20010213165940,1,'2001-02-13','16:59:40');
INSERT INTO eZStats_PageView VALUES (193,0,2,2,5,20010213165941,1,'2001-02-13','16:59:41');
INSERT INTO eZStats_PageView VALUES (194,0,2,2,5,20010213165941,1,'2001-02-13','16:59:41');
INSERT INTO eZStats_PageView VALUES (195,0,2,2,5,20010213165941,1,'2001-02-13','16:59:41');
INSERT INTO eZStats_PageView VALUES (196,0,2,2,5,20010213165941,1,'2001-02-13','16:59:41');
INSERT INTO eZStats_PageView VALUES (197,0,2,2,5,20010213165941,1,'2001-02-13','16:59:41');
INSERT INTO eZStats_PageView VALUES (198,0,2,2,5,20010213165941,1,'2001-02-13','16:59:41');
INSERT INTO eZStats_PageView VALUES (199,0,2,2,5,20010213165942,1,'2001-02-13','16:59:42');
INSERT INTO eZStats_PageView VALUES (200,0,2,2,5,20010213165942,1,'2001-02-13','16:59:42');
INSERT INTO eZStats_PageView VALUES (201,0,2,2,5,20010213165942,1,'2001-02-13','16:59:42');
INSERT INTO eZStats_PageView VALUES (202,0,2,2,5,20010213165942,1,'2001-02-13','16:59:42');
INSERT INTO eZStats_PageView VALUES (203,0,2,2,5,20010213165942,1,'2001-02-13','16:59:42');
INSERT INTO eZStats_PageView VALUES (204,0,2,2,5,20010213165943,1,'2001-02-13','16:59:43');
INSERT INTO eZStats_PageView VALUES (205,0,2,2,5,20010213165943,1,'2001-02-13','16:59:43');
INSERT INTO eZStats_PageView VALUES (206,0,2,2,5,20010213165943,1,'2001-02-13','16:59:43');
INSERT INTO eZStats_PageView VALUES (207,0,2,2,5,20010213165943,1,'2001-02-13','16:59:43');
INSERT INTO eZStats_PageView VALUES (208,0,2,2,5,20010213165943,1,'2001-02-13','16:59:43');
INSERT INTO eZStats_PageView VALUES (209,0,2,2,5,20010213165943,1,'2001-02-13','16:59:43');
INSERT INTO eZStats_PageView VALUES (210,0,2,2,5,20010213165944,1,'2001-02-13','16:59:44');
INSERT INTO eZStats_PageView VALUES (211,0,2,2,5,20010213165944,1,'2001-02-13','16:59:44');
INSERT INTO eZStats_PageView VALUES (212,0,2,2,5,20010213165944,1,'2001-02-13','16:59:44');
INSERT INTO eZStats_PageView VALUES (213,0,2,2,5,20010213165944,1,'2001-02-13','16:59:44');
INSERT INTO eZStats_PageView VALUES (214,0,2,2,5,20010213165944,1,'2001-02-13','16:59:44');
INSERT INTO eZStats_PageView VALUES (215,0,2,2,5,20010213165944,1,'2001-02-13','16:59:44');
INSERT INTO eZStats_PageView VALUES (216,0,2,2,5,20010213165945,1,'2001-02-13','16:59:45');
INSERT INTO eZStats_PageView VALUES (217,0,2,2,5,20010213165945,1,'2001-02-13','16:59:45');
INSERT INTO eZStats_PageView VALUES (218,0,2,2,5,20010213165945,1,'2001-02-13','16:59:45');
INSERT INTO eZStats_PageView VALUES (219,0,2,2,5,20010213165945,1,'2001-02-13','16:59:45');
INSERT INTO eZStats_PageView VALUES (220,0,2,2,5,20010213165945,1,'2001-02-13','16:59:45');
INSERT INTO eZStats_PageView VALUES (221,0,2,2,5,20010213165945,1,'2001-02-13','16:59:45');
INSERT INTO eZStats_PageView VALUES (222,0,2,2,5,20010213165946,1,'2001-02-13','16:59:46');
INSERT INTO eZStats_PageView VALUES (223,0,2,2,5,20010213165946,1,'2001-02-13','16:59:46');
INSERT INTO eZStats_PageView VALUES (224,0,2,2,5,20010213165946,1,'2001-02-13','16:59:46');
INSERT INTO eZStats_PageView VALUES (225,0,2,2,5,20010213165946,1,'2001-02-13','16:59:46');
INSERT INTO eZStats_PageView VALUES (226,0,2,2,5,20010213165946,1,'2001-02-13','16:59:46');
INSERT INTO eZStats_PageView VALUES (227,0,2,2,5,20010213165946,1,'2001-02-13','16:59:46');
INSERT INTO eZStats_PageView VALUES (228,0,2,2,5,20010213165947,1,'2001-02-13','16:59:47');
INSERT INTO eZStats_PageView VALUES (229,0,2,2,5,20010213165947,1,'2001-02-13','16:59:47');
INSERT INTO eZStats_PageView VALUES (230,0,2,2,5,20010213165947,1,'2001-02-13','16:59:47');
INSERT INTO eZStats_PageView VALUES (231,0,2,2,5,20010213165947,1,'2001-02-13','16:59:47');
INSERT INTO eZStats_PageView VALUES (232,0,2,2,5,20010213165948,1,'2001-02-13','16:59:48');
INSERT INTO eZStats_PageView VALUES (233,0,2,2,5,20010213165948,1,'2001-02-13','16:59:48');
INSERT INTO eZStats_PageView VALUES (234,0,2,2,5,20010213165948,1,'2001-02-13','16:59:48');
INSERT INTO eZStats_PageView VALUES (235,0,2,2,5,20010213165949,1,'2001-02-13','16:59:49');
INSERT INTO eZStats_PageView VALUES (236,0,2,2,5,20010213165949,1,'2001-02-13','16:59:49');
INSERT INTO eZStats_PageView VALUES (237,0,1,1,2,20010213170013,4,'2001-02-13','17:00:13');
INSERT INTO eZStats_PageView VALUES (238,0,1,1,6,20010213170015,3,'2001-02-13','17:00:15');
INSERT INTO eZStats_PageView VALUES (239,0,1,1,1,20010213170016,1,'2001-02-13','17:00:16');
INSERT INTO eZStats_PageView VALUES (240,0,1,1,2,20010213170023,5,'2001-02-13','17:00:23');
INSERT INTO eZStats_PageView VALUES (241,0,1,1,7,20010213170026,6,'2001-02-13','17:00:26');
INSERT INTO eZStats_PageView VALUES (242,0,1,1,8,20010213170028,7,'2001-02-13','17:00:28');
INSERT INTO eZStats_PageView VALUES (243,0,1,1,9,20010213170030,8,'2001-02-13','17:00:30');
INSERT INTO eZStats_PageView VALUES (244,0,1,1,10,20010213170033,7,'2001-02-13','17:00:33');
INSERT INTO eZStats_PageView VALUES (245,0,1,1,9,20010213170035,4,'2001-02-13','17:00:35');
INSERT INTO eZStats_PageView VALUES (246,0,2,2,5,20010213170046,1,'2001-02-13','17:00:46');
INSERT INTO eZStats_PageView VALUES (247,0,2,2,5,20010213170046,1,'2001-02-13','17:00:46');
INSERT INTO eZStats_PageView VALUES (248,0,2,2,5,20010213170046,1,'2001-02-13','17:00:46');
INSERT INTO eZStats_PageView VALUES (249,0,2,2,5,20010213170047,1,'2001-02-13','17:00:47');
INSERT INTO eZStats_PageView VALUES (250,0,2,2,5,20010213170047,1,'2001-02-13','17:00:47');
INSERT INTO eZStats_PageView VALUES (251,0,2,2,5,20010213170047,1,'2001-02-13','17:00:47');

#
# Table structure for table 'eZStats_RefererURL'
#

DROP TABLE IF EXISTS eZStats_RefererURL;
CREATE TABLE eZStats_RefererURL (
  ID int(11) NOT NULL auto_increment,
  Domain char(100) default NULL,
  URI char(200) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZStats_RefererURL'
#

INSERT INTO eZStats_RefererURL VALUES (1,'trade.php.ez.no','/article/archive/4/');
INSERT INTO eZStats_RefererURL VALUES (2,'trade.php.ez.no','/article/articleview/1/');
INSERT INTO eZStats_RefererURL VALUES (3,'trade.php.ez.no','/article/articleview/1/?PHPSESSID=245dc16d1ef23acf9ba619437c589051');
INSERT INTO eZStats_RefererURL VALUES (4,'trade.php.ez.no','/newsfeed/allcategories/');
INSERT INTO eZStats_RefererURL VALUES (5,'','');
INSERT INTO eZStats_RefererURL VALUES (6,'trade.php.ez.no','/article/archive/0/');
INSERT INTO eZStats_RefererURL VALUES (7,'trade.php.ez.no','/link/group/1/');
INSERT INTO eZStats_RefererURL VALUES (8,'trade.php.ez.no','/forum/forumlist/1/');
INSERT INTO eZStats_RefererURL VALUES (9,'trade.php.ez.no','/forum/messagelist/1/');
INSERT INTO eZStats_RefererURL VALUES (10,'trade.php.ez.no','/forum/message/1/');

#
# Table structure for table 'eZStats_RemoteHost'
#

DROP TABLE IF EXISTS eZStats_RemoteHost;
CREATE TABLE eZStats_RemoteHost (
  ID int(11) NOT NULL auto_increment,
  IP char(15) default NULL,
  HostName char(150) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZStats_RemoteHost'
#

INSERT INTO eZStats_RemoteHost VALUES (1,'10.0.2.3',NULL);
INSERT INTO eZStats_RemoteHost VALUES (2,'10.0.2.200',NULL);

#
# Table structure for table 'eZStats_RequestPage'
#

DROP TABLE IF EXISTS eZStats_RequestPage;
CREATE TABLE eZStats_RequestPage (
  ID int(11) NOT NULL auto_increment,
  URI char(250) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZStats_RequestPage'
#

INSERT INTO eZStats_RequestPage VALUES (1,'/article/articleview/1/');
INSERT INTO eZStats_RequestPage VALUES (2,'/newsfeed/allcategories/');
INSERT INTO eZStats_RequestPage VALUES (3,'/article/archive/4/');
INSERT INTO eZStats_RequestPage VALUES (4,'/article/archive/0/');
INSERT INTO eZStats_RequestPage VALUES (5,'/link/group/1/');
INSERT INTO eZStats_RequestPage VALUES (6,'/forum/forumlist/1/');
INSERT INTO eZStats_RequestPage VALUES (7,'/forum/messagelist/1/');
INSERT INTO eZStats_RequestPage VALUES (8,'/forum/message/1/');

#
# Table structure for table 'eZTodo_Category'
#

DROP TABLE IF EXISTS eZTodo_Category;
CREATE TABLE eZTodo_Category (
  Description text,
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTodo_Category'
#

INSERT INTO eZTodo_Category VALUES (NULL,1,'Bugfix');
INSERT INTO eZTodo_Category VALUES (NULL,2,'Programming');

#
# Table structure for table 'eZTodo_Priority'
#

DROP TABLE IF EXISTS eZTodo_Priority;
CREATE TABLE eZTodo_Priority (
  ID int(11) NOT NULL auto_increment,
  Name varchar(30) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTodo_Priority'
#

INSERT INTO eZTodo_Priority VALUES (1,'Low');
INSERT INTO eZTodo_Priority VALUES (2,'Medium');
INSERT INTO eZTodo_Priority VALUES (3,'High');

#
# Table structure for table 'eZTodo_Todo'
#

DROP TABLE IF EXISTS eZTodo_Todo;
CREATE TABLE eZTodo_Todo (
  Category int(11) default NULL,
  Priority int(11) default NULL,
  Permission enum('Public','Private') default 'Private',
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default NULL,
  OwnerID int(11) default NULL,
  Name varchar(30) default NULL,
  Date timestamp(14) NOT NULL,
  Due timestamp(14) NOT NULL,
  Description text,
  Status int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTodo_Todo'
#

INSERT INTO eZTodo_Todo VALUES (2,1,'Private',1,27,27,'This is a test Todo',20010116142211,00000000000000,'Please add this feature.',1);

#
# Table structure for table 'eZTrade_Attribute'
#

DROP TABLE IF EXISTS eZTrade_Attribute;
CREATE TABLE eZTrade_Attribute (
  ID int(11) NOT NULL auto_increment,
  TypeID int(11) default NULL,
  Name char(150) default NULL,
  Created timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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

DROP TABLE IF EXISTS eZTrade_AttributeValue;
CREATE TABLE eZTrade_AttributeValue (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  AttributeID int(11) default NULL,
  Value char(200) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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

DROP TABLE IF EXISTS eZTrade_Cart;
CREATE TABLE eZTrade_Cart (
  ID int(11) NOT NULL auto_increment,
  SessionID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_Cart'
#

INSERT INTO eZTrade_Cart VALUES (3,4);
INSERT INTO eZTrade_Cart VALUES (2,1);
INSERT INTO eZTrade_Cart VALUES (4,5);
INSERT INTO eZTrade_Cart VALUES (5,11);
INSERT INTO eZTrade_Cart VALUES (6,10);
INSERT INTO eZTrade_Cart VALUES (7,3);

#
# Table structure for table 'eZTrade_CartItem'
#

DROP TABLE IF EXISTS eZTrade_CartItem;
CREATE TABLE eZTrade_CartItem (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  Count int(11) default NULL,
  CartID int(11) default NULL,
  WishListItemID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_CartItem'
#


#
# Table structure for table 'eZTrade_CartOptionValue'
#

DROP TABLE IF EXISTS eZTrade_CartOptionValue;
CREATE TABLE eZTrade_CartOptionValue (
  ID int(11) NOT NULL auto_increment,
  CartItemID int(11) default NULL,
  OptionID int(11) default NULL,
  OptionValueID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_CartOptionValue'
#


#
# Table structure for table 'eZTrade_Category'
#

DROP TABLE IF EXISTS eZTrade_Category;
CREATE TABLE eZTrade_Category (
  ID int(11) NOT NULL auto_increment,
  Parent int(11) default NULL,
  Description text,
  Name varchar(100) default NULL,
  ImageID int(11) default NULL,
  SortMode int(11) NOT NULL default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_Category'
#

INSERT INTO eZTrade_Category VALUES (1,0,'','Products',NULL,1);

#
# Table structure for table 'eZTrade_CategoryOptionLink'
#

DROP TABLE IF EXISTS eZTrade_CategoryOptionLink;
CREATE TABLE eZTrade_CategoryOptionLink (
  ID int(11) NOT NULL auto_increment,
  CategoryID int(11) default NULL,
  OptionID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_CategoryOptionLink'
#


#
# Table structure for table 'eZTrade_Option'
#

DROP TABLE IF EXISTS eZTrade_Option;
CREATE TABLE eZTrade_Option (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_Option'
#


#
# Table structure for table 'eZTrade_OptionValue'
#

DROP TABLE IF EXISTS eZTrade_OptionValue;
CREATE TABLE eZTrade_OptionValue (
  ID int(11) NOT NULL auto_increment,
  Name char(100) default NULL,
  OptionID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_OptionValue'
#


#
# Table structure for table 'eZTrade_Order'
#

DROP TABLE IF EXISTS eZTrade_Order;
CREATE TABLE eZTrade_Order (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  ShippingCharge float(10,2) default NULL,
  PaymentMethod text,
  ShippingAddressID int(11) default NULL,
  BillingAddressID int(11) default NULL,
  IsExported int(11) NOT NULL default '0',
  Date datetime default NULL,
  IsActive int(11) default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_Order'
#

INSERT INTO eZTrade_Order VALUES (1,27,50.00,'1',1,1,0,NULL,0);

#
# Table structure for table 'eZTrade_OrderItem'
#

DROP TABLE IF EXISTS eZTrade_OrderItem;
CREATE TABLE eZTrade_OrderItem (
  ID int(11) NOT NULL auto_increment,
  OrderID int(11) NOT NULL default '0',
  Count int(11) default NULL,
  Price float(10,2) default NULL,
  ProductID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_OrderItem'
#

INSERT INTO eZTrade_OrderItem VALUES (1,1,1,142.00,1);

#
# Table structure for table 'eZTrade_OrderOptionValue'
#

DROP TABLE IF EXISTS eZTrade_OrderOptionValue;
CREATE TABLE eZTrade_OrderOptionValue (
  ID int(11) NOT NULL auto_increment,
  OrderItemID int(11) default NULL,
  OptionName char(25) default NULL,
  ValueName char(25) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_OrderOptionValue'
#


#
# Table structure for table 'eZTrade_OrderStatus'
#

DROP TABLE IF EXISTS eZTrade_OrderStatus;
CREATE TABLE eZTrade_OrderStatus (
  ID int(11) NOT NULL auto_increment,
  StatusID int(11) NOT NULL default '0',
  Altered timestamp(14) NOT NULL,
  AdminID int(11) default NULL,
  OrderID int(11) NOT NULL default '0',
  Comment text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_OrderStatus'
#

INSERT INTO eZTrade_OrderStatus VALUES (1,1,20010126102943,0,1,'');

#
# Table structure for table 'eZTrade_OrderStatusType'
#

DROP TABLE IF EXISTS eZTrade_OrderStatusType;
CREATE TABLE eZTrade_OrderStatusType (
  ID int(11) NOT NULL auto_increment,
  Name char(25) NOT NULL default '',
  PRIMARY KEY (ID),
  UNIQUE KEY Name(Name)
) TYPE=MyISAM;

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
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Brief text,
  Description text,
  Keywords varchar(100) default NULL,
  Price float(10,2) default NULL,
  ShowPrice enum('true','false') default NULL,
  ShowProduct enum('true','false') default NULL,
  Discontinued enum('true','false') default NULL,
  InheritOptions enum('true','false') default NULL,
  ProductNumber varchar(100) default NULL,
  ExternalLink varchar(200) default NULL,
  IsHotDeal enum('true','false') default 'false',
  Published timestamp(14) NOT NULL,
  Altered timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_Product'
#

INSERT INTO eZTrade_Product VALUES (1,'Cat','Test product','Please buy this product','',142.00,'true','true','false',NULL,'','','true',20010126102820,00000000000000);
INSERT INTO eZTrade_Product VALUES (2,'Flower','This is a flower','Description','',42.00,'true','true','false',NULL,'','www.ez.no','true',20010126130741,00000000000000);

#
# Table structure for table 'eZTrade_ProductCategoryDefinition'
#

DROP TABLE IF EXISTS eZTrade_ProductCategoryDefinition;
CREATE TABLE eZTrade_ProductCategoryDefinition (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) NOT NULL default '0',
  CategoryID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_ProductCategoryDefinition'
#

INSERT INTO eZTrade_ProductCategoryDefinition VALUES (1,1,1);
INSERT INTO eZTrade_ProductCategoryDefinition VALUES (2,2,1);

#
# Table structure for table 'eZTrade_ProductCategoryLink'
#

DROP TABLE IF EXISTS eZTrade_ProductCategoryLink;
CREATE TABLE eZTrade_ProductCategoryLink (
  ID int(11) NOT NULL auto_increment,
  CategoryID int(11) default NULL,
  ProductID int(11) default NULL,
  Placement int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_ProductCategoryLink'
#

INSERT INTO eZTrade_ProductCategoryLink VALUES (1,1,1,0);
INSERT INTO eZTrade_ProductCategoryLink VALUES (2,1,2,0);

#
# Table structure for table 'eZTrade_ProductImageDefinition'
#

DROP TABLE IF EXISTS eZTrade_ProductImageDefinition;
CREATE TABLE eZTrade_ProductImageDefinition (
  ProductID int(11) NOT NULL default '0',
  ThumbnailImageID int(11) default NULL,
  MainImageID int(11) default NULL,
  PRIMARY KEY (ProductID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_ProductImageDefinition'
#

INSERT INTO eZTrade_ProductImageDefinition VALUES (1,3,3);
INSERT INTO eZTrade_ProductImageDefinition VALUES (2,4,4);

#
# Table structure for table 'eZTrade_ProductImageLink'
#

DROP TABLE IF EXISTS eZTrade_ProductImageLink;
CREATE TABLE eZTrade_ProductImageLink (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  ImageID int(11) default NULL,
  Created timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_ProductImageLink'
#

INSERT INTO eZTrade_ProductImageLink VALUES (1,1,3,20010126102759);
INSERT INTO eZTrade_ProductImageLink VALUES (2,2,4,20010126130705);

#
# Table structure for table 'eZTrade_ProductOptionLink'
#

DROP TABLE IF EXISTS eZTrade_ProductOptionLink;
CREATE TABLE eZTrade_ProductOptionLink (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  OptionID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_ProductOptionLink'
#


#
# Table structure for table 'eZTrade_ProductTypeLink'
#

DROP TABLE IF EXISTS eZTrade_ProductTypeLink;
CREATE TABLE eZTrade_ProductTypeLink (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  TypeID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_ProductTypeLink'
#

INSERT INTO eZTrade_ProductTypeLink VALUES (1,2,1);

#
# Table structure for table 'eZTrade_Type'
#

DROP TABLE IF EXISTS eZTrade_Type;
CREATE TABLE eZTrade_Type (
  ID int(11) NOT NULL auto_increment,
  Name varchar(150) default NULL,
  Description text,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_Type'
#

INSERT INTO eZTrade_Type VALUES (1,'Flower','');

#
# Table structure for table 'eZTrade_WishList'
#

DROP TABLE IF EXISTS eZTrade_WishList;
CREATE TABLE eZTrade_WishList (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default NULL,
  IsPublic int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_WishList'
#

INSERT INTO eZTrade_WishList VALUES (1,27,0);

#
# Table structure for table 'eZTrade_WishListItem'
#

DROP TABLE IF EXISTS eZTrade_WishListItem;
CREATE TABLE eZTrade_WishListItem (
  ID int(11) NOT NULL auto_increment,
  ProductID int(11) default NULL,
  Count int(11) default NULL,
  WishListID int(11) default NULL,
  IsBought int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_WishListItem'
#

INSERT INTO eZTrade_WishListItem VALUES (1,1,1,1,0);

#
# Table structure for table 'eZTrade_WishListOptionValue'
#

DROP TABLE IF EXISTS eZTrade_WishListOptionValue;
CREATE TABLE eZTrade_WishListOptionValue (
  ID int(11) NOT NULL auto_increment,
  WishListItemID int(11) default NULL,
  OptionID int(11) default NULL,
  OptionValueID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZTrade_WishListOptionValue'
#


#
# Table structure for table 'eZUser_Forgot'
#

DROP TABLE IF EXISTS eZUser_Forgot;
CREATE TABLE eZUser_Forgot (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  Hash char(33) default NULL,
  Time timestamp(14) NOT NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZUser_Forgot'
#


#
# Table structure for table 'eZUser_Group'
#

DROP TABLE IF EXISTS eZUser_Group;
CREATE TABLE eZUser_Group (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) default NULL,
  Description text,
  SessionTimeout int(11) default '60',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZUser_Group'
#

INSERT INTO eZUser_Group VALUES (2,'Anonymous','Users that register themself on the user page, eg forum users.',60);
INSERT INTO eZUser_Group VALUES (1,'Administrators','All rights',7200);

#
# Table structure for table 'eZUser_GroupPermissionLink'
#

DROP TABLE IF EXISTS eZUser_GroupPermissionLink;
CREATE TABLE eZUser_GroupPermissionLink (
  ID int(11) NOT NULL auto_increment,
  GroupID int(11) default NULL,
  PermissionID int(11) default NULL,
  IsEnabled enum('true','false') default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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

DROP TABLE IF EXISTS eZUser_Module;
CREATE TABLE eZUser_Module (
  ID int(11) NOT NULL auto_increment,
  Name char(100) NOT NULL default '',
  PRIMARY KEY (ID),
  UNIQUE KEY Name(Name)
) TYPE=MyISAM;

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

DROP TABLE IF EXISTS eZUser_Permission;
CREATE TABLE eZUser_Permission (
  ID int(11) NOT NULL auto_increment,
  ModuleID int(11) default NULL,
  Name char(100) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

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

DROP TABLE IF EXISTS eZUser_User;
CREATE TABLE eZUser_User (
  ID int(11) NOT NULL auto_increment,
  Login varchar(50) NOT NULL default '',
  Password varchar(50) NOT NULL default '',
  Email varchar(50) default NULL,
  FirstName varchar(50) default NULL,
  LastName varchar(50) default NULL,
  InfoSubscription enum('true','false') default 'false',
  Signature text NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE KEY Login(Login)
) TYPE=MyISAM;

#
# Dumping data for table 'eZUser_User'
#

INSERT INTO eZUser_User VALUES (27,'admin','0c947f956f7aa781','bf@ez.no','admin','user','false','');

#
# Table structure for table 'eZUser_UserAddressLink'
#

DROP TABLE IF EXISTS eZUser_UserAddressLink;
CREATE TABLE eZUser_UserAddressLink (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) NOT NULL default '0',
  AddressID int(11) NOT NULL default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZUser_UserAddressLink'
#

INSERT INTO eZUser_UserAddressLink VALUES (1,27,1);

#
# Table structure for table 'eZUser_UserGroupLink'
#

DROP TABLE IF EXISTS eZUser_UserGroupLink;
CREATE TABLE eZUser_UserGroupLink (
  ID int(11) NOT NULL auto_increment,
  UserID int(11) default NULL,
  GroupID int(11) default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

#
# Dumping data for table 'eZUser_UserGroupLink'
#

INSERT INTO eZUser_UserGroupLink VALUES (52,27,1);

