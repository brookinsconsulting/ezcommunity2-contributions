#
# Table structure for table 'eZAddress_Address'
#
DROP TABLE IF EXISTS eZAddress_Address;
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

#
# Table structure for table 'eZAddress_AddressType'
#
DROP TABLE IF EXISTS eZAddress_AddressType;
CREATE TABLE eZAddress_AddressType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Table structure for table 'eZAddress_AddressDefinition'
#
DROP TABLE IF EXISTS eZAddress_AddressDefinition;
CREATE TABLE eZAddress_AddressDefinition (
  UserID int(11) DEFAULT '0' NOT NULL,
  AddressID int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (UserID, AddressID)
);

#
# Dumping data for table 'eZAddress_AddressDefinition'
#

#
# Table structure for table 'eZAddress_Country'
#
DROP TABLE IF EXISTS eZAddress_Country;
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

INSERT INTO eZAddress_Country VALUES (2,'AF','Afghanistan','');
INSERT INTO eZAddress_Country VALUES (3,'AL','Albania','');
INSERT INTO eZAddress_Country VALUES (4,'DZ','Algeria','');
INSERT INTO eZAddress_Country VALUES (5,'AS','American Samoa','');
INSERT INTO eZAddress_Country VALUES (6,'AD','Andorra','');
INSERT INTO eZAddress_Country VALUES (7,'AO','Angola','');
INSERT INTO eZAddress_Country VALUES (8,'AI','Anguilla','');
INSERT INTO eZAddress_Country VALUES (9,'AQ','Antarctica','');
INSERT INTO eZAddress_Country VALUES (10,'AG','Antigua and Barbuda','');
INSERT INTO eZAddress_Country VALUES (11,'AR','Argentina','');
INSERT INTO eZAddress_Country VALUES (12,'AM','Armenia','');
INSERT INTO eZAddress_Country VALUES (13,'AW','Aruba','');
INSERT INTO eZAddress_Country VALUES (14,'AU','Australia','');
INSERT INTO eZAddress_Country VALUES (15,'AT','Austria','');
INSERT INTO eZAddress_Country VALUES (16,'AZ','Azerbaijan','');
INSERT INTO eZAddress_Country VALUES (17,'BS','Bahamas','');
INSERT INTO eZAddress_Country VALUES (18,'BH','Bahrain','');
INSERT INTO eZAddress_Country VALUES (19,'BD','Bangladesh','');
INSERT INTO eZAddress_Country VALUES (20,'BB','Barbados','');
INSERT INTO eZAddress_Country VALUES (21,'BY','Belarus','');
INSERT INTO eZAddress_Country VALUES (22,'BE','Belgium','');
INSERT INTO eZAddress_Country VALUES (23,'BZ','Belize','');
INSERT INTO eZAddress_Country VALUES (24,'BJ','Benin','');
INSERT INTO eZAddress_Country VALUES (25,'BM','Bermuda','');
INSERT INTO eZAddress_Country VALUES (26,'BT','Bhutan','');
INSERT INTO eZAddress_Country VALUES (27,'BO','Bolivia','');
INSERT INTO eZAddress_Country VALUES (28,'BA','Bosnia and Herzegovina','');
INSERT INTO eZAddress_Country VALUES (29,'BW','Botswana','');
INSERT INTO eZAddress_Country VALUES (30,'BV','Bouvet Island','');
INSERT INTO eZAddress_Country VALUES (31,'BR','Brazil','');
INSERT INTO eZAddress_Country VALUES (32,'IO','British Indian Ocean Territory','');
INSERT INTO eZAddress_Country VALUES (33,'BN','Brunei Darussalam','');
INSERT INTO eZAddress_Country VALUES (34,'BG','Bulgaria','');
INSERT INTO eZAddress_Country VALUES (35,'BF','Burkina Faso','');
INSERT INTO eZAddress_Country VALUES (36,'BI','Burundi','');
INSERT INTO eZAddress_Country VALUES (37,'KH','Cambodia','');
INSERT INTO eZAddress_Country VALUES (38,'CM','Cameroon','');
INSERT INTO eZAddress_Country VALUES (39,'CA','Canada','');
INSERT INTO eZAddress_Country VALUES (40,'CV','Cape Verde','');
INSERT INTO eZAddress_Country VALUES (41,'KY','Cayman Islands','');
INSERT INTO eZAddress_Country VALUES (42,'CF','Central African Republic','');
INSERT INTO eZAddress_Country VALUES (43,'TD','Chad','');
INSERT INTO eZAddress_Country VALUES (44,'CL','Chile','');
INSERT INTO eZAddress_Country VALUES (45,'CN','China','');
INSERT INTO eZAddress_Country VALUES (46,'CX','Christmas Island','');
INSERT INTO eZAddress_Country VALUES (47,'CC','Cocos (Keeling) Islands','');
INSERT INTO eZAddress_Country VALUES (48,'CO','Colombia','');
INSERT INTO eZAddress_Country VALUES (49,'KM','Comoros','');
INSERT INTO eZAddress_Country VALUES (50,'CG','Congo','');
INSERT INTO eZAddress_Country VALUES (51,'CK','Cook Islands','');
INSERT INTO eZAddress_Country VALUES (52,'CR','Costa Rica','');
INSERT INTO eZAddress_Country VALUES (53,'CI','Cote d\'Ivoire','');
INSERT INTO eZAddress_Country VALUES (54,'HR','Croatia','');
INSERT INTO eZAddress_Country VALUES (55,'CU','Cuba','');
INSERT INTO eZAddress_Country VALUES (56,'CY','Cyprus','');
INSERT INTO eZAddress_Country VALUES (57,'CZ','Czech Republic','');
INSERT INTO eZAddress_Country VALUES (58,'DK','Denmark','');
INSERT INTO eZAddress_Country VALUES (59,'DJ','Djibouti','');
INSERT INTO eZAddress_Country VALUES (60,'DM','Dominica','');
INSERT INTO eZAddress_Country VALUES (61,'DO','Dominican Republic','');
INSERT INTO eZAddress_Country VALUES (62,'TP','East Timor','');
INSERT INTO eZAddress_Country VALUES (63,'EC','Ecuador','');
INSERT INTO eZAddress_Country VALUES (64,'EG','Egypt','');
INSERT INTO eZAddress_Country VALUES (65,'SV','El Salvador','');
INSERT INTO eZAddress_Country VALUES (66,'GQ','Equatorial Guinea','');
INSERT INTO eZAddress_Country VALUES (67,'ER','Eritrea','');
INSERT INTO eZAddress_Country VALUES (68,'EE','Estonia','');
INSERT INTO eZAddress_Country VALUES (69,'ET','Ethiopia','');
INSERT INTO eZAddress_Country VALUES (70,'FK','Falkland Islands (Malvinas)','');
INSERT INTO eZAddress_Country VALUES (71,'FO','Faroe Islands','');
INSERT INTO eZAddress_Country VALUES (72,'FJ','Fiji','');
INSERT INTO eZAddress_Country VALUES (73,'FI','Finland','');
INSERT INTO eZAddress_Country VALUES (74,'FR','France','');
INSERT INTO eZAddress_Country VALUES (75,'FX','France, Metropolitan','');
INSERT INTO eZAddress_Country VALUES (76,'GF','French Guiana','');
INSERT INTO eZAddress_Country VALUES (77,'PF','French Polynesia','');
INSERT INTO eZAddress_Country VALUES (78,'TF','French Southern Territories','');
INSERT INTO eZAddress_Country VALUES (79,'GA','Gabon','');
INSERT INTO eZAddress_Country VALUES (80,'GM','Gambia','');
INSERT INTO eZAddress_Country VALUES (81,'GE','Georgia','');
INSERT INTO eZAddress_Country VALUES (82,'DE','Germany','');
INSERT INTO eZAddress_Country VALUES (83,'GH','Ghana','');
INSERT INTO eZAddress_Country VALUES (84,'GI','Gibraltar','');
INSERT INTO eZAddress_Country VALUES (85,'GR','Greece','');
INSERT INTO eZAddress_Country VALUES (86,'GL','Greenland','');
INSERT INTO eZAddress_Country VALUES (87,'GD','Grenada','');
INSERT INTO eZAddress_Country VALUES (88,'GP','Guadeloupe','');
INSERT INTO eZAddress_Country VALUES (89,'GU','Guam','');
INSERT INTO eZAddress_Country VALUES (90,'GT','Guatemala','');
INSERT INTO eZAddress_Country VALUES (91,'GN','Guinea','');
INSERT INTO eZAddress_Country VALUES (92,'GW','Guinea-Bissau','');
INSERT INTO eZAddress_Country VALUES (93,'GY','Guyana','');
INSERT INTO eZAddress_Country VALUES (94,'HT','Haiti','');
INSERT INTO eZAddress_Country VALUES (95,'HM','Heard Island and McDonald Islands','');
INSERT INTO eZAddress_Country VALUES (96,'HN','Honduras','');
INSERT INTO eZAddress_Country VALUES (97,'HK','Hong Kong','');
INSERT INTO eZAddress_Country VALUES (98,'HU','Hungary','');
INSERT INTO eZAddress_Country VALUES (99,'IS','Iceland','');
INSERT INTO eZAddress_Country VALUES (100,'IN','India','');
INSERT INTO eZAddress_Country VALUES (101,'ID','Indonesia','');
INSERT INTO eZAddress_Country VALUES (102,'IR','Iran (Islamic Republic of)','');
INSERT INTO eZAddress_Country VALUES (103,'IQ','Iraq','');
INSERT INTO eZAddress_Country VALUES (104,'IE','Ireland','');
INSERT INTO eZAddress_Country VALUES (105,'IL','Israel','');
INSERT INTO eZAddress_Country VALUES (106,'IT','Italy','');
INSERT INTO eZAddress_Country VALUES (107,'JM','Jamaica','');
INSERT INTO eZAddress_Country VALUES (108,'JP','Japan','');
INSERT INTO eZAddress_Country VALUES (109,'JO','Jordan','');
INSERT INTO eZAddress_Country VALUES (110,'KZ','Kazakhstan','');
INSERT INTO eZAddress_Country VALUES (111,'KE','Kenya','');
INSERT INTO eZAddress_Country VALUES (112,'KI','Kiribati','');
INSERT INTO eZAddress_Country VALUES (113,'KP','Korea, Democratic People\'s Republic of','');
INSERT INTO eZAddress_Country VALUES (114,'KR','Korea, Republic of','');
INSERT INTO eZAddress_Country VALUES (115,'KW','Kuwait','');
INSERT INTO eZAddress_Country VALUES (116,'KG','Kyrgyzstan','');
INSERT INTO eZAddress_Country VALUES (117,'LA','Lao People\'s Democratic Republic','');
INSERT INTO eZAddress_Country VALUES (118,'LT','Latin America','');
INSERT INTO eZAddress_Country VALUES (119,'LV','Latvia','');
INSERT INTO eZAddress_Country VALUES (120,'LB','Lebanon','');
INSERT INTO eZAddress_Country VALUES (121,'LS','Lesotho','');
INSERT INTO eZAddress_Country VALUES (122,'LR','Liberia','');
INSERT INTO eZAddress_Country VALUES (123,'LY','Libyan Arab Jamahiriya','');
INSERT INTO eZAddress_Country VALUES (124,'LI','Liechtenstein','');
INSERT INTO eZAddress_Country VALUES (125,'LX','Lithuania','');
INSERT INTO eZAddress_Country VALUES (126,'LU','Luxembourg','');
INSERT INTO eZAddress_Country VALUES (127,'MO','Macau','');
INSERT INTO eZAddress_Country VALUES (128,'MK','Macedonia','');
INSERT INTO eZAddress_Country VALUES (129,'MG','Madagascar','');
INSERT INTO eZAddress_Country VALUES (130,'MW','Malawi','');
INSERT INTO eZAddress_Country VALUES (131,'MY','Malaysia','');
INSERT INTO eZAddress_Country VALUES (132,'MV','Maldives','');
INSERT INTO eZAddress_Country VALUES (133,'ML','Mali','');
INSERT INTO eZAddress_Country VALUES (134,'MT','Malta','');
INSERT INTO eZAddress_Country VALUES (135,'MH','Marshall Islands','');
INSERT INTO eZAddress_Country VALUES (136,'MQ','Martinique','');
INSERT INTO eZAddress_Country VALUES (137,'MR','Mauritania','');
INSERT INTO eZAddress_Country VALUES (138,'MU','Mauritius','');
INSERT INTO eZAddress_Country VALUES (139,'YT','Mayotte','');
INSERT INTO eZAddress_Country VALUES (140,'MX','Mexico','');
INSERT INTO eZAddress_Country VALUES (141,'FM','Micronesia (Federated States of)','');
INSERT INTO eZAddress_Country VALUES (142,'MD','Moldova, Republic of','');
INSERT INTO eZAddress_Country VALUES (143,'MC','Monaco','');
INSERT INTO eZAddress_Country VALUES (144,'MN','Mongolia','');
INSERT INTO eZAddress_Country VALUES (145,'MS','Montserrat','');
INSERT INTO eZAddress_Country VALUES (146,'MA','Morocco','');
INSERT INTO eZAddress_Country VALUES (147,'MZ','Mozambique','');
INSERT INTO eZAddress_Country VALUES (148,'MM','Myanmar','');
INSERT INTO eZAddress_Country VALUES (149,'NA','Namibia','');
INSERT INTO eZAddress_Country VALUES (150,'NR','Nauru','');
INSERT INTO eZAddress_Country VALUES (151,'NP','Nepal','');
INSERT INTO eZAddress_Country VALUES (152,'NL','Netherlands','');
INSERT INTO eZAddress_Country VALUES (153,'AN','Netherlands Antilles','');
INSERT INTO eZAddress_Country VALUES (154,'NC','New Caledonia','');
INSERT INTO eZAddress_Country VALUES (155,'NZ','New Zealand','');
INSERT INTO eZAddress_Country VALUES (156,'NI','Nicaragua','');
INSERT INTO eZAddress_Country VALUES (157,'NE','Niger','');
INSERT INTO eZAddress_Country VALUES (158,'NG','Nigeria','');
INSERT INTO eZAddress_Country VALUES (159,'NU','Niue','');
INSERT INTO eZAddress_Country VALUES (160,'NF','Norfolk Island','');
INSERT INTO eZAddress_Country VALUES (161,'MP','Northern Mariana Islands','');
INSERT INTO eZAddress_Country VALUES (162,'NO','Norway','');
INSERT INTO eZAddress_Country VALUES (163,'OM','Oman','');
INSERT INTO eZAddress_Country VALUES (164,'PK','Pakistan','');
INSERT INTO eZAddress_Country VALUES (165,'PW','Palau','');
INSERT INTO eZAddress_Country VALUES (166,'PA','Panama','');
INSERT INTO eZAddress_Country VALUES (167,'PG','Papua New Guinea','');
INSERT INTO eZAddress_Country VALUES (168,'PY','Paraguay','');
INSERT INTO eZAddress_Country VALUES (169,'PE','Peru','');
INSERT INTO eZAddress_Country VALUES (170,'PH','Philippines','');
INSERT INTO eZAddress_Country VALUES (171,'PN','Pitcairn','');
INSERT INTO eZAddress_Country VALUES (172,'PL','Poland','');
INSERT INTO eZAddress_Country VALUES (173,'PT','Portugal','');
INSERT INTO eZAddress_Country VALUES (174,'PR','Puerto Rico','');
INSERT INTO eZAddress_Country VALUES (175,'QA','Qatar','');
INSERT INTO eZAddress_Country VALUES (176,'RE','Reunion','');
INSERT INTO eZAddress_Country VALUES (177,'RO','Romania','');
INSERT INTO eZAddress_Country VALUES (178,'RU','Russian Federation','');
INSERT INTO eZAddress_Country VALUES (179,'RW','Rwanda','');
INSERT INTO eZAddress_Country VALUES (180,'SH','Saint Helena','');
INSERT INTO eZAddress_Country VALUES (181,'KN','Saint Kitts and Nevis','');
INSERT INTO eZAddress_Country VALUES (182,'LC','Saint Lucia','');
INSERT INTO eZAddress_Country VALUES (183,'PM','Saint Pierre and Miquelon','');
INSERT INTO eZAddress_Country VALUES (184,'VC','Saint Vincent and the Grenadines','');
INSERT INTO eZAddress_Country VALUES (185,'WS','Samoa','');
INSERT INTO eZAddress_Country VALUES (186,'SM','San Marino','');
INSERT INTO eZAddress_Country VALUES (187,'ST','Sao Tome and Principe','');
INSERT INTO eZAddress_Country VALUES (188,'SA','Saudi Arabia','');
INSERT INTO eZAddress_Country VALUES (189,'SN','Senegal','');
INSERT INTO eZAddress_Country VALUES (190,'SC','Seychelles','');
INSERT INTO eZAddress_Country VALUES (191,'SL','Sierra Leone','');
INSERT INTO eZAddress_Country VALUES (192,'SG','Singapore','');
INSERT INTO eZAddress_Country VALUES (193,'SK','Slovakia','');
INSERT INTO eZAddress_Country VALUES (194,'SI','Slovenia','');
INSERT INTO eZAddress_Country VALUES (195,'SB','Solomon Islands','');
INSERT INTO eZAddress_Country VALUES (196,'SO','Somalia','');
INSERT INTO eZAddress_Country VALUES (197,'ZA','South Africa','');
INSERT INTO eZAddress_Country VALUES (198,'GS','South Georgia and the South Sandwich Island','');
INSERT INTO eZAddress_Country VALUES (199,'ES','Spain','');
INSERT INTO eZAddress_Country VALUES (200,'LK','Sri Lanka','');
INSERT INTO eZAddress_Country VALUES (201,'SD','Sudan','');
INSERT INTO eZAddress_Country VALUES (202,'SR','Suriname','');
INSERT INTO eZAddress_Country VALUES (203,'SJ','Svalbard and Jan Mayen Islands','');
INSERT INTO eZAddress_Country VALUES (204,'SZ','Swaziland','');
INSERT INTO eZAddress_Country VALUES (205,'SE','Sweden','');
INSERT INTO eZAddress_Country VALUES (206,'CH','Switzerland','');
INSERT INTO eZAddress_Country VALUES (207,'SY','Syrian Arab Republic','');
INSERT INTO eZAddress_Country VALUES (208,'TW','Taiwan, Republic of China','');
INSERT INTO eZAddress_Country VALUES (209,'TJ','Tajikistan','');
INSERT INTO eZAddress_Country VALUES (210,'TZ','Tanzania, United Republic of','');
INSERT INTO eZAddress_Country VALUES (211,'TH','Thailand','');
INSERT INTO eZAddress_Country VALUES (212,'TG','Togo','');
INSERT INTO eZAddress_Country VALUES (213,'TK','Tokelau','');
INSERT INTO eZAddress_Country VALUES (214,'TO','Tonga','');
INSERT INTO eZAddress_Country VALUES (215,'TT','Trinidad and Tobago','');
INSERT INTO eZAddress_Country VALUES (216,'TN','Tunisia','');
INSERT INTO eZAddress_Country VALUES (217,'TR','Turkey','');
INSERT INTO eZAddress_Country VALUES (218,'TM','Turkmenistan','');
INSERT INTO eZAddress_Country VALUES (219,'TC','Turks and Caicos Islands','');
INSERT INTO eZAddress_Country VALUES (220,'TV','Tuvalu','');
INSERT INTO eZAddress_Country VALUES (221,'UG','Uganda','');
INSERT INTO eZAddress_Country VALUES (222,'UA','Ukraine','');
INSERT INTO eZAddress_Country VALUES (223,'AE','United Arab Emirates','');
INSERT INTO eZAddress_Country VALUES (224,'GB','United Kingdom','');
INSERT INTO eZAddress_Country VALUES (225,'UM','United States Minor Outlying Islands','');
INSERT INTO eZAddress_Country VALUES (226,'UY','Uruguay','');
INSERT INTO eZAddress_Country VALUES (227,'UZ','Uzbekistan','');
INSERT INTO eZAddress_Country VALUES (228,'VU','Vanuatu','');
INSERT INTO eZAddress_Country VALUES (229,'VA','Vatican City State (Holy See)','');
INSERT INTO eZAddress_Country VALUES (230,'VE','Venezuela','');
INSERT INTO eZAddress_Country VALUES (231,'VN','Viet Nam','');
INSERT INTO eZAddress_Country VALUES (232,'VG','Virgin Islands (British)','');
INSERT INTO eZAddress_Country VALUES (233,'VI','Virgin Islands (U.S.)','');
INSERT INTO eZAddress_Country VALUES (234,'WF','Wallis and Futuna Islands','');
INSERT INTO eZAddress_Country VALUES (235,'EH','Western Sahara','');
INSERT INTO eZAddress_Country VALUES (236,'YE','Yemen','');
INSERT INTO eZAddress_Country VALUES (237,'YU','Yugoslavia','');
INSERT INTO eZAddress_Country VALUES (238,'ZR','Zaire','');
INSERT INTO eZAddress_Country VALUES (239,'ZM','Zambia','');
INSERT INTO eZAddress_Country VALUES (240,'US','United States of America','');

#
# Table structure for table 'eZAddress_Online'
#
DROP TABLE IF EXISTS eZAddress_Online;
CREATE TABLE eZAddress_Online (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  URL char(255),
  OnlineTypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_Online'
#

#
# Table structure for table 'eZAddress_OnlineType'
#
DROP TABLE IF EXISTS eZAddress_OnlineType;
CREATE TABLE eZAddress_OnlineType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name char(50),
  ListOrder int(11) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_OnlineType'
#

#
# Table structure for table 'eZAddress_Phone'
#
DROP TABLE IF EXISTS eZAddress_Phone;
CREATE TABLE eZAddress_Phone (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Number varchar(22),
  PhoneTypeID int(11),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_Phone'
#

#
# Table structure for table 'eZAddress_PhoneType'
#
DROP TABLE IF EXISTS eZAddress_PhoneType;
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
