CREATE TABLE eZAddress_Address (
  ID int NOT NULL,
  Address text,
  AddressTypeID int,
  CountryID int,
  Name varchar(50),
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_Address VALUES (1,'Adminstreet1',0,162,'');

CREATE TABLE eZAddress_AddressDefinition (
  UserID int NOT NULL,
  AddressID int NOT NULL,
  PRIMARY KEY (UserID,AddressID)
);

CREATE TABLE eZAddress_AddressType (
  ID int NOT NULL,
  Name varchar(50),
  ListOrder int NOT NULL,
  Removed int NOT NULL DEFAULT '0',
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_AddressType VALUES (1,'Post adresse',1,0);

CREATE TABLE eZAddress_Country (
  ID int NOT NULL,
  ParentID int DEFAULT '0',
  ISO varchar(2),
  Name varchar(100),
  HasVAT int DEFAULT '0',
  Removed int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_Country VALUES (2,0,'AF','Afghanistan',0);
INSERT INTO eZAddress_Country VALUES (3,0,'AL','Albania',0);
INSERT INTO eZAddress_Country VALUES (4,0,'DZ','Algeria',0);
INSERT INTO eZAddress_Country VALUES (5,0,'AS','American Samoa',0);
INSERT INTO eZAddress_Country VALUES (6,0,'AD','Andorra',0);
INSERT INTO eZAddress_Country VALUES (7,0,'AO','Angola',0);
INSERT INTO eZAddress_Country VALUES (8,0,'AI','Anguilla',0);
INSERT INTO eZAddress_Country VALUES (9,0,'AQ','Antarctica',0);
INSERT INTO eZAddress_Country VALUES (10,0,'AG','Antigua and Barbuda',0);
INSERT INTO eZAddress_Country VALUES (11,0,'AR','Argentina',0);
INSERT INTO eZAddress_Country VALUES (12,0,'AM','Armenia',0);
INSERT INTO eZAddress_Country VALUES (13,0,'AW','Aruba',0);
INSERT INTO eZAddress_Country VALUES (14,0,'AU','Australia',0);
INSERT INTO eZAddress_Country VALUES (15,0,'AT','Austria',0);
INSERT INTO eZAddress_Country VALUES (16,0,'AZ','Azerbaijan',0);
INSERT INTO eZAddress_Country VALUES (17,0,'BS','Bahamas',0);
INSERT INTO eZAddress_Country VALUES (18,0,'BH','Bahrain',0);
INSERT INTO eZAddress_Country VALUES (19,0,'BD','Bangladesh',0);
INSERT INTO eZAddress_Country VALUES (20,0,'BB','Barbados',0);
INSERT INTO eZAddress_Country VALUES (21,0,'BY','Belarus',0);
INSERT INTO eZAddress_Country VALUES (22,0,'BE','Belgium',0);
INSERT INTO eZAddress_Country VALUES (23,0,'BZ','Belize',0);
INSERT INTO eZAddress_Country VALUES (24,0,'BJ','Benin',0);
INSERT INTO eZAddress_Country VALUES (25,0,'BM','Bermuda',0);
INSERT INTO eZAddress_Country VALUES (26,0,'BT','Bhutan',0);
INSERT INTO eZAddress_Country VALUES (27,0,'BO','Bolivia',0);
INSERT INTO eZAddress_Country VALUES (28,0,'BA','Bosnia and Herzegovina',0);
INSERT INTO eZAddress_Country VALUES (29,0,'BW','Botswana',0);
INSERT INTO eZAddress_Country VALUES (30,0,'BV','Bouvet Island',0);
INSERT INTO eZAddress_Country VALUES (31,0,'BR','Brazil',0);
INSERT INTO eZAddress_Country VALUES (32,0,'IO','British Indian Ocean Territory',0);
INSERT INTO eZAddress_Country VALUES (33,0,'BN','Brunei Darussalam',0);
INSERT INTO eZAddress_Country VALUES (34,0,'BG','Bulgaria',0);
INSERT INTO eZAddress_Country VALUES (35,0,'BF','Burkina Faso',0);
INSERT INTO eZAddress_Country VALUES (36,0,'BI','Burundi',0);
INSERT INTO eZAddress_Country VALUES (37,0,'KH','Cambodia',0);
INSERT INTO eZAddress_Country VALUES (38,0,'CM','Cameroon',0);
INSERT INTO eZAddress_Country VALUES (39,0,'CA','Canada',0);
INSERT INTO eZAddress_Country VALUES (40,0,'CV','Cape Verde',0);
INSERT INTO eZAddress_Country VALUES (41,0,'KY','Cayman Islands',0);
INSERT INTO eZAddress_Country VALUES (42,0,'CF','Central African Republic',0);
INSERT INTO eZAddress_Country VALUES (43,0,'TD','Chad',0);
INSERT INTO eZAddress_Country VALUES (44,0,'CL','Chile',0);
INSERT INTO eZAddress_Country VALUES (45,0,'CN','China',0);
INSERT INTO eZAddress_Country VALUES (46,0,'CX','Christmas Island',0);
INSERT INTO eZAddress_Country VALUES (47,0,'CC','Cocos (Keeling) Islands',0);
INSERT INTO eZAddress_Country VALUES (48,0,'CO','Colombia',0);
INSERT INTO eZAddress_Country VALUES (49,0,'KM','Comoros',0);
INSERT INTO eZAddress_Country VALUES (50,0,'CG','Congo',0);
INSERT INTO eZAddress_Country VALUES (51,0,'CK','Cook Islands',0);
INSERT INTO eZAddress_Country VALUES (52,0,'CR','Costa Rica',0);
INSERT INTO eZAddress_Country VALUES (53,0,'CI','Cote d\'Ivoire',0);
INSERT INTO eZAddress_Country VALUES (54,0,'HR','Croatia',0);
INSERT INTO eZAddress_Country VALUES (55,0,'CU','Cuba',0);
INSERT INTO eZAddress_Country VALUES (56,0,'CY','Cyprus',0);
INSERT INTO eZAddress_Country VALUES (57,0,'CZ','Czech Republic',0);
INSERT INTO eZAddress_Country VALUES (58,0,'DK','Denmark',0);
INSERT INTO eZAddress_Country VALUES (59,0,'DJ','Djibouti',0);
INSERT INTO eZAddress_Country VALUES (60,0,'DM','Dominica',0);
INSERT INTO eZAddress_Country VALUES (61,0,'DO','Dominican Republic',0);
INSERT INTO eZAddress_Country VALUES (62,0,'TP','East Timor',0);
INSERT INTO eZAddress_Country VALUES (63,0,'EC','Ecuador',0);
INSERT INTO eZAddress_Country VALUES (64,0,'EG','Egypt',0);
INSERT INTO eZAddress_Country VALUES (65,0,'SV','El Salvador',0);
INSERT INTO eZAddress_Country VALUES (66,0,'GQ','Equatorial Guinea',0);
INSERT INTO eZAddress_Country VALUES (67,0,'ER','Eritrea',0);
INSERT INTO eZAddress_Country VALUES (68,0,'EE','Estonia',0);
INSERT INTO eZAddress_Country VALUES (69,0,'ET','Ethiopia',0);
INSERT INTO eZAddress_Country VALUES (70,0,'FK','Falkland Islands (Malvinas)',0);
INSERT INTO eZAddress_Country VALUES (71,0,'FO','Faroe Islands',0);
INSERT INTO eZAddress_Country VALUES (72,0,'FJ','Fiji',0);
INSERT INTO eZAddress_Country VALUES (73,0,'FI','Finland',0);
INSERT INTO eZAddress_Country VALUES (74,0,'FR','France',0);
INSERT INTO eZAddress_Country VALUES (75,0,'FX','France, Metropolitan',0);
INSERT INTO eZAddress_Country VALUES (76,0,'GF','French Guiana',0);
INSERT INTO eZAddress_Country VALUES (77,0,'PF','French Polynesia',0);
INSERT INTO eZAddress_Country VALUES (78,0,'TF','French Southern Territories',0);
INSERT INTO eZAddress_Country VALUES (79,0,'GA','Gabon',0);
INSERT INTO eZAddress_Country VALUES (80,0,'GM','Gambia',0);
INSERT INTO eZAddress_Country VALUES (81,0,'GE','Georgia',0);
INSERT INTO eZAddress_Country VALUES (82,0,'DE','Germany',0);
INSERT INTO eZAddress_Country VALUES (83,0,'GH','Ghana',0);
INSERT INTO eZAddress_Country VALUES (84,0,'GI','Gibraltar',0);
INSERT INTO eZAddress_Country VALUES (85,0,'GR','Greece',0);
INSERT INTO eZAddress_Country VALUES (86,0,'GL','Greenland',0);
INSERT INTO eZAddress_Country VALUES (87,0,'GD','Grenada',0);
INSERT INTO eZAddress_Country VALUES (88,0,'GP','Guadeloupe',0);
INSERT INTO eZAddress_Country VALUES (89,0,'GU','Guam',0);
INSERT INTO eZAddress_Country VALUES (90,0,'GT','Guatemala',0);
INSERT INTO eZAddress_Country VALUES (91,0,'GN','Guinea',0);
INSERT INTO eZAddress_Country VALUES (92,0,'GW','Guinea-Bissau',0);
INSERT INTO eZAddress_Country VALUES (93,0,'GY','Guyana',0);
INSERT INTO eZAddress_Country VALUES (94,0,'HT','Haiti',0);
INSERT INTO eZAddress_Country VALUES (95,0,'HM','Heard Island and McDonald Islands',0);
INSERT INTO eZAddress_Country VALUES (96,0,'HN','Honduras',0);
INSERT INTO eZAddress_Country VALUES (97,0,'HK','Hong Kong',0);
INSERT INTO eZAddress_Country VALUES (98,0,'HU','Hungary',0);
INSERT INTO eZAddress_Country VALUES (99,0,'IS','Iceland',0);
INSERT INTO eZAddress_Country VALUES (100,0,'IN','India',0);
INSERT INTO eZAddress_Country VALUES (101,0,'ID','Indonesia',0);
INSERT INTO eZAddress_Country VALUES (102,0,'IR','Iran (Islamic Republic of)',0);
INSERT INTO eZAddress_Country VALUES (103,0,'IQ','Iraq',0);
INSERT INTO eZAddress_Country VALUES (104,0,'IE','Ireland',0);
INSERT INTO eZAddress_Country VALUES (105,0,'IL','Israel',0);
INSERT INTO eZAddress_Country VALUES (106,0,'IT','Italy',0);
INSERT INTO eZAddress_Country VALUES (107,0,'JM','Jamaica',0);
INSERT INTO eZAddress_Country VALUES (108,0,'JP','Japan',0);
INSERT INTO eZAddress_Country VALUES (109,0,'JO','Jordan',0);
INSERT INTO eZAddress_Country VALUES (110,0,'KZ','Kazakhstan',0);
INSERT INTO eZAddress_Country VALUES (111,0,'KE','Kenya',0);
INSERT INTO eZAddress_Country VALUES (112,0,'KI','Kiribati',0);
INSERT INTO eZAddress_Country VALUES (113,0,'KP','Korea, Democratic People\'s Republic of',0);
INSERT INTO eZAddress_Country VALUES (114,0,'KR','Korea, Republic of',0);
INSERT INTO eZAddress_Country VALUES (115,0,'KW','Kuwait',0);
INSERT INTO eZAddress_Country VALUES (116,0,'KG','Kyrgyzstan',0);
INSERT INTO eZAddress_Country VALUES (117,0,'LA','Lao People\'s Democratic Republic',0);
INSERT INTO eZAddress_Country VALUES (118,0,'LT','Latin America',0);
INSERT INTO eZAddress_Country VALUES (119,0,'LV','Latvia',0);
INSERT INTO eZAddress_Country VALUES (120,0,'LB','Lebanon',0);
INSERT INTO eZAddress_Country VALUES (121,0,'LS','Lesotho',0);
INSERT INTO eZAddress_Country VALUES (122,0,'LR','Liberia',0);
INSERT INTO eZAddress_Country VALUES (123,0,'LY','Libyan Arab Jamahiriya',0);
INSERT INTO eZAddress_Country VALUES (124,0,'LI','Liechtenstein',0);
INSERT INTO eZAddress_Country VALUES (125,0,'LX','Lithuania',0);
INSERT INTO eZAddress_Country VALUES (126,0,'LU','Luxembourg',0);
INSERT INTO eZAddress_Country VALUES (127,0,'MO','Macau',0);
INSERT INTO eZAddress_Country VALUES (128,0,'MK','Macedonia',0);
INSERT INTO eZAddress_Country VALUES (129,0,'MG','Madagascar',0);
INSERT INTO eZAddress_Country VALUES (130,0,'MW','Malawi',0);
INSERT INTO eZAddress_Country VALUES (131,0,'MY','Malaysia',0);
INSERT INTO eZAddress_Country VALUES (132,0,'MV','Maldives',0);
INSERT INTO eZAddress_Country VALUES (133,0,'ML','Mali',0);
INSERT INTO eZAddress_Country VALUES (134,0,'MT','Malta',0);
INSERT INTO eZAddress_Country VALUES (135,0,'MH','Marshall Islands',0);
INSERT INTO eZAddress_Country VALUES (136,0,'MQ','Martinique',0);
INSERT INTO eZAddress_Country VALUES (137,0,'MR','Mauritania',0);
INSERT INTO eZAddress_Country VALUES (138,0,'MU','Mauritius',0);
INSERT INTO eZAddress_Country VALUES (139,0,'YT','Mayotte',0);
INSERT INTO eZAddress_Country VALUES (140,0,'MX','Mexico',0);
INSERT INTO eZAddress_Country VALUES (141,0,'FM','Micronesia (Federated States of)',0);
INSERT INTO eZAddress_Country VALUES (142,0,'MD','Moldova, Republic of',0);
INSERT INTO eZAddress_Country VALUES (143,0,'MC','Monaco',0);
INSERT INTO eZAddress_Country VALUES (144,0,'MN','Mongolia',0);
INSERT INTO eZAddress_Country VALUES (145,0,'MS','Montserrat',0);
INSERT INTO eZAddress_Country VALUES (146,0,'MA','Morocco',0);
INSERT INTO eZAddress_Country VALUES (147,0,'MZ','Mozambique',0);
INSERT INTO eZAddress_Country VALUES (148,0,'MM','Myanmar',0);
INSERT INTO eZAddress_Country VALUES (149,0,'NA','Namibia',0);
INSERT INTO eZAddress_Country VALUES (150,0,'NR','Nauru',0);
INSERT INTO eZAddress_Country VALUES (151,0,'NP','Nepal',0);
INSERT INTO eZAddress_Country VALUES (152,0,'NL','Netherlands',0);
INSERT INTO eZAddress_Country VALUES (153,0,'AN','Netherlands Antilles',0);
INSERT INTO eZAddress_Country VALUES (154,0,'NC','New Caledonia',0);
INSERT INTO eZAddress_Country VALUES (155,0,'NZ','New Zealand',0);
INSERT INTO eZAddress_Country VALUES (156,0,'NI','Nicaragua',0);
INSERT INTO eZAddress_Country VALUES (157,0,'NE','Niger',0);
INSERT INTO eZAddress_Country VALUES (158,0,'NG','Nigeria',0);
INSERT INTO eZAddress_Country VALUES (159,0,'NU','Niue',0);
INSERT INTO eZAddress_Country VALUES (160,0,'NF','Norfolk Island',0);
INSERT INTO eZAddress_Country VALUES (161,0,'MP','Northern Mariana Islands',0);
INSERT INTO eZAddress_Country VALUES (162,0,'NO','Norway',0);
INSERT INTO eZAddress_Country VALUES (163,0,'OM','Oman',0);
INSERT INTO eZAddress_Country VALUES (164,0,'PK','Pakistan',0);
INSERT INTO eZAddress_Country VALUES (165,0,'PW','Palau',0);
INSERT INTO eZAddress_Country VALUES (166,0,'PA','Panama',0);
INSERT INTO eZAddress_Country VALUES (167,0,'PG','Papua New Guinea',0);
INSERT INTO eZAddress_Country VALUES (168,0,'PY','Paraguay',0);
INSERT INTO eZAddress_Country VALUES (169,0,'PE','Peru',0);
INSERT INTO eZAddress_Country VALUES (170,0,'PH','Philippines',0);
INSERT INTO eZAddress_Country VALUES (171,0,'PN','Pitcairn',0);
INSERT INTO eZAddress_Country VALUES (172,0,'PL','Poland',0);
INSERT INTO eZAddress_Country VALUES (173,0,'PT','Portugal',0);
INSERT INTO eZAddress_Country VALUES (174,0,'PR','Puerto Rico',0);
INSERT INTO eZAddress_Country VALUES (175,0,'QA','Qatar',0);
INSERT INTO eZAddress_Country VALUES (176,0,'RE','Reunion',0);
INSERT INTO eZAddress_Country VALUES (177,0,'RO','Romania',0);
INSERT INTO eZAddress_Country VALUES (178,0,'RU','Russian Federation',0);
INSERT INTO eZAddress_Country VALUES (179,0,'RW','Rwanda',0);
INSERT INTO eZAddress_Country VALUES (180,0,'SH','Saint Helena',0);
INSERT INTO eZAddress_Country VALUES (181,0,'KN','Saint Kitts and Nevis',0);
INSERT INTO eZAddress_Country VALUES (182,0,'LC','Saint Lucia',0);
INSERT INTO eZAddress_Country VALUES (183,0,'PM','Saint Pierre and Miquelon',0);
INSERT INTO eZAddress_Country VALUES (184,0,'VC','Saint Vincent and the Grenadines',0);
INSERT INTO eZAddress_Country VALUES (185,0,'WS','Samoa',0);
INSERT INTO eZAddress_Country VALUES (186,0,'SM','San Marino',0);
INSERT INTO eZAddress_Country VALUES (187,0,'ST','Sao Tome and Principe',0);
INSERT INTO eZAddress_Country VALUES (188,0,'SA','Saudi Arabia',0);
INSERT INTO eZAddress_Country VALUES (189,0,'SN','Senegal',0);
INSERT INTO eZAddress_Country VALUES (190,0,'SC','Seychelles',0);
INSERT INTO eZAddress_Country VALUES (191,0,'SL','Sierra Leone',0);
INSERT INTO eZAddress_Country VALUES (192,0,'SG','Singapore',0);
INSERT INTO eZAddress_Country VALUES (193,0,'SK','Slovakia',0);
INSERT INTO eZAddress_Country VALUES (194,0,'SI','Slovenia',0);
INSERT INTO eZAddress_Country VALUES (195,0,'SB','Solomon Islands',0);
INSERT INTO eZAddress_Country VALUES (196,0,'SO','Somalia',0);
INSERT INTO eZAddress_Country VALUES (197,0,'ZA','South Africa',0);
INSERT INTO eZAddress_Country VALUES (198,0,'GS','South Georgia and the South Sandwich Island',0);
INSERT INTO eZAddress_Country VALUES (199,0,'ES','Spain',0);
INSERT INTO eZAddress_Country VALUES (200,0,'LK','Sri Lanka',0);
INSERT INTO eZAddress_Country VALUES (201,0,'SD','Sudan',0);
INSERT INTO eZAddress_Country VALUES (202,0,'SR','Suriname',0);
INSERT INTO eZAddress_Country VALUES (203,0,'SJ','Svalbard and Jan Mayen Islands',0);
INSERT INTO eZAddress_Country VALUES (204,0,'SZ','Swaziland',0);
INSERT INTO eZAddress_Country VALUES (205,0,'SE','Sweden',0);
INSERT INTO eZAddress_Country VALUES (206,0,'CH','Switzerland',0);
INSERT INTO eZAddress_Country VALUES (207,0,'SY','Syrian Arab Republic',0);
INSERT INTO eZAddress_Country VALUES (208,0,'TW','Taiwan, Republic of China',0);
INSERT INTO eZAddress_Country VALUES (209,0,'TJ','Tajikistan',0);
INSERT INTO eZAddress_Country VALUES (210,0,'TZ','Tanzania, United Republic of',0);
INSERT INTO eZAddress_Country VALUES (211,0,'TH','Thailand',0);
INSERT INTO eZAddress_Country VALUES (212,0,'TG','Togo',0);
INSERT INTO eZAddress_Country VALUES (213,0,'TK','Tokelau',0);
INSERT INTO eZAddress_Country VALUES (214,0,'TO','Tonga',0);
INSERT INTO eZAddress_Country VALUES (215,0,'TT','Trinidad and Tobago',0);
INSERT INTO eZAddress_Country VALUES (216,0,'TN','Tunisia',0);
INSERT INTO eZAddress_Country VALUES (217,0,'TR','Turkey',0);
INSERT INTO eZAddress_Country VALUES (218,0,'TM','Turkmenistan',0);
INSERT INTO eZAddress_Country VALUES (219,0,'TC','Turks and Caicos Islands',0);
INSERT INTO eZAddress_Country VALUES (220,0,'TV','Tuvalu',0);
INSERT INTO eZAddress_Country VALUES (221,0,'UG','Uganda',0);
INSERT INTO eZAddress_Country VALUES (222,0,'UA','Ukraine',0);
INSERT INTO eZAddress_Country VALUES (223,0,'AE','United Arab Emirates',0);
INSERT INTO eZAddress_Country VALUES (224,0,'GB','United Kingdom',0);
INSERT INTO eZAddress_Country VALUES (225,0,'UM','United States Minor Outlying Islands',0);
INSERT INTO eZAddress_Country VALUES (226,0,'UY','Uruguay',0);
INSERT INTO eZAddress_Country VALUES (227,0,'UZ','Uzbekistan',0);
INSERT INTO eZAddress_Country VALUES (228,0,'VU','Vanuatu',0);
INSERT INTO eZAddress_Country VALUES (229,0,'VA','Vatican City State (Holy See)',0);
INSERT INTO eZAddress_Country VALUES (230,0,'VE','Venezuela',0);
INSERT INTO eZAddress_Country VALUES (231,0,'VN','Viet Nam',0);
INSERT INTO eZAddress_Country VALUES (232,0,'VG','Virgin Islands (British)',0);
INSERT INTO eZAddress_Country VALUES (233,0,'VI','Virgin Islands (U.S.)',0);
INSERT INTO eZAddress_Country VALUES (234,0,'WF','Wallis and Futuna Islands',0);
INSERT INTO eZAddress_Country VALUES (235,0,'EH','Western Sahara',0);
INSERT INTO eZAddress_Country VALUES (236,0,'YE','Yemen',0);
INSERT INTO eZAddress_Country VALUES (237,0,'YU','Yugoslavia',0);
INSERT INTO eZAddress_Country VALUES (238,0,'ZR','Zaire',0);
INSERT INTO eZAddress_Country VALUES (239,0,'ZM','Zambia',0);
INSERT INTO eZAddress_Country VALUES (240,0,'US','United States of America',0);

CREATE TABLE eZAddress_Language (
  ID int NOT NULL,
  Name varchar(100),
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_Language VALUES ( '1', 'Abkhazian' );
INSERT INTO eZAddress_Language VALUES ( '2', 'Afar' );
INSERT INTO eZAddress_Language VALUES ( '3', 'Afrikaans' );
INSERT INTO eZAddress_Language VALUES ( '4', 'Albanian' );
INSERT INTO eZAddress_Language VALUES ( '5', 'Amharic' );
INSERT INTO eZAddress_Language VALUES ( '6', 'Arabic' );
INSERT INTO eZAddress_Language VALUES ( '7', 'Armenian' );
INSERT INTO eZAddress_Language VALUES ( '8', 'Assamese' );
INSERT INTO eZAddress_Language VALUES ( '9', 'Aymara' );
INSERT INTO eZAddress_Language VALUES ( '10', 'Azerbaijani' );
INSERT INTO eZAddress_Language VALUES ( '11', 'Bashkir' );
INSERT INTO eZAddress_Language VALUES ( '12', 'Basque' );
INSERT INTO eZAddress_Language VALUES ( '13', 'Bengali (Bangla)' );
INSERT INTO eZAddress_Language VALUES ( '14', 'Bhutani' );
INSERT INTO eZAddress_Language VALUES ( '15', 'Bihari' );
INSERT INTO eZAddress_Language VALUES ( '16', 'Bislama' );
INSERT INTO eZAddress_Language VALUES ( '17', 'Bosnian' );
INSERT INTO eZAddress_Language VALUES ( '18', 'Breton' );
INSERT INTO eZAddress_Language VALUES ( '19', 'Bulgarian' );
INSERT INTO eZAddress_Language VALUES ( '20', 'Burmese' );
INSERT INTO eZAddress_Language VALUES ( '21', 'Byelorussian' );
INSERT INTO eZAddress_Language VALUES ( '22', 'Cambodian' );
INSERT INTO eZAddress_Language VALUES ( '23', 'Catalan' );
INSERT INTO eZAddress_Language VALUES ( '24', 'Chinese' );
INSERT INTO eZAddress_Language VALUES ( '25', 'Corsican' );
INSERT INTO eZAddress_Language VALUES ( '26', 'Croatian' );
INSERT INTO eZAddress_Language VALUES ( '27', 'Czech' );
INSERT INTO eZAddress_Language VALUES ( '28', 'Danish' );
INSERT INTO eZAddress_Language VALUES ( '29', 'Dutch' );
INSERT INTO eZAddress_Language VALUES ( '30', 'English' );
INSERT INTO eZAddress_Language VALUES ( '31', 'Esperanto' );
INSERT INTO eZAddress_Language VALUES ( '32', 'Estonian' );
INSERT INTO eZAddress_Language VALUES ( '33', 'Faroese' );
INSERT INTO eZAddress_Language VALUES ( '34', 'Fiji' );
INSERT INTO eZAddress_Language VALUES ( '35', 'Finnish' );
INSERT INTO eZAddress_Language VALUES ( '36', 'French' );
INSERT INTO eZAddress_Language VALUES ( '37', 'Frisian' );
INSERT INTO eZAddress_Language VALUES ( '38', 'Galician' );
INSERT INTO eZAddress_Language VALUES ( '39', 'Georgian' );
INSERT INTO eZAddress_Language VALUES ( '40', 'German' );
INSERT INTO eZAddress_Language VALUES ( '41', 'Greek' );
INSERT INTO eZAddress_Language VALUES ( '42', 'Greenlandic' );
INSERT INTO eZAddress_Language VALUES ( '43', 'Guarani' );
INSERT INTO eZAddress_Language VALUES ( '44', 'Gujarati' );
INSERT INTO eZAddress_Language VALUES ( '45', 'Hausa' );
INSERT INTO eZAddress_Language VALUES ( '46', 'Hebrew' );
INSERT INTO eZAddress_Language VALUES ( '47', 'Hindi' );
INSERT INTO eZAddress_Language VALUES ( '48', 'Hungarian' );
INSERT INTO eZAddress_Language VALUES ( '49', 'Icelandic' );
INSERT INTO eZAddress_Language VALUES ( '50', 'Indonesian' );
INSERT INTO eZAddress_Language VALUES ( '51', 'Interlingua' );
INSERT INTO eZAddress_Language VALUES ( '52', 'Interlingue' );
INSERT INTO eZAddress_Language VALUES ( '53', 'Inupiak' );
INSERT INTO eZAddress_Language VALUES ( '54', 'Irish' );
INSERT INTO eZAddress_Language VALUES ( '55', 'Italian' );
INSERT INTO eZAddress_Language VALUES ( '56', 'Japanese' );
INSERT INTO eZAddress_Language VALUES ( '57', 'Javanese' );
INSERT INTO eZAddress_Language VALUES ( '58', 'Kannada' );
INSERT INTO eZAddress_Language VALUES ( '59', 'Kashmiri' );
INSERT INTO eZAddress_Language VALUES ( '60', 'Kazakh' );
INSERT INTO eZAddress_Language VALUES ( '61', 'Kinyarwanda' );
INSERT INTO eZAddress_Language VALUES ( '62', 'Kirghiz' );
INSERT INTO eZAddress_Language VALUES ( '63', 'Kirundi' );
INSERT INTO eZAddress_Language VALUES ( '64', 'Korean' );
INSERT INTO eZAddress_Language VALUES ( '65', 'Kurdish' );
INSERT INTO eZAddress_Language VALUES ( '66', 'Laothian' );
INSERT INTO eZAddress_Language VALUES ( '67', 'Latin' );
INSERT INTO eZAddress_Language VALUES ( '68', 'Latvian, Lettish' );
INSERT INTO eZAddress_Language VALUES ( '69', 'Lingala' );
INSERT INTO eZAddress_Language VALUES ( '70', 'Lithuanian' );
INSERT INTO eZAddress_Language VALUES ( '71', 'Macedonian' );
INSERT INTO eZAddress_Language VALUES ( '72', 'Malagasy' );
INSERT INTO eZAddress_Language VALUES ( '73', 'Malay' );
INSERT INTO eZAddress_Language VALUES ( '74', 'Malayalam' );
INSERT INTO eZAddress_Language VALUES ( '75', 'Maltese' );
INSERT INTO eZAddress_Language VALUES ( '76', 'Maori' );
INSERT INTO eZAddress_Language VALUES ( '77', 'Marathi' );
INSERT INTO eZAddress_Language VALUES ( '78', 'Moldavian' );
INSERT INTO eZAddress_Language VALUES ( '79', 'Mongolian' );
INSERT INTO eZAddress_Language VALUES ( '80', 'Nauru' );
INSERT INTO eZAddress_Language VALUES ( '81', 'Nepali' );
INSERT INTO eZAddress_Language VALUES ( '82', 'Norwegian' );
INSERT INTO eZAddress_Language VALUES ( '83', 'Occitan' );
INSERT INTO eZAddress_Language VALUES ( '84', 'Oriya' );
INSERT INTO eZAddress_Language VALUES ( '85', 'Oromo (Afan)' );
INSERT INTO eZAddress_Language VALUES ( '86', 'Pashto, Pushto' );
INSERT INTO eZAddress_Language VALUES ( '87', 'Persian' );
INSERT INTO eZAddress_Language VALUES ( '88', 'Polish' );
INSERT INTO eZAddress_Language VALUES ( '89', 'Portuguese' );
INSERT INTO eZAddress_Language VALUES ( '90', 'Punjabi' );
INSERT INTO eZAddress_Language VALUES ( '91', 'Quechua' );
INSERT INTO eZAddress_Language VALUES ( '92', 'Rhaeto-Romance' );
INSERT INTO eZAddress_Language VALUES ( '93', 'Roma' );
INSERT INTO eZAddress_Language VALUES ( '94', 'Romanian' );
INSERT INTO eZAddress_Language VALUES ( '95', 'Russian' );
INSERT INTO eZAddress_Language VALUES ( '96', 'Samoan' );
INSERT INTO eZAddress_Language VALUES ( '97', 'Sangro' );
INSERT INTO eZAddress_Language VALUES ( '98', 'Sanskrit' );
INSERT INTO eZAddress_Language VALUES ( '99', 'Scots Gaelic' );
INSERT INTO eZAddress_Language VALUES ( '100', 'Serbian' );
INSERT INTO eZAddress_Language VALUES ( '101', 'Serbo-Croatian' );
INSERT INTO eZAddress_Language VALUES ( '102', 'Sesotho' );
INSERT INTO eZAddress_Language VALUES ( '103', 'Setswana' );
INSERT INTO eZAddress_Language VALUES ( '104', 'Shona' );
INSERT INTO eZAddress_Language VALUES ( '105', 'Sindhi' );
INSERT INTO eZAddress_Language VALUES ( '106', 'Singhalese' );
INSERT INTO eZAddress_Language VALUES ( '107', 'Siswati' );
INSERT INTO eZAddress_Language VALUES ( '108', 'Slovak' );
INSERT INTO eZAddress_Language VALUES ( '109', 'Slovenian' );
INSERT INTO eZAddress_Language VALUES ( '110', 'Somali' );
INSERT INTO eZAddress_Language VALUES ( '111', 'Spanish' );
INSERT INTO eZAddress_Language VALUES ( '112', 'Sudanese' );
INSERT INTO eZAddress_Language VALUES ( '113', 'Swahili' );
INSERT INTO eZAddress_Language VALUES ( '114', 'Swedish' );
INSERT INTO eZAddress_Language VALUES ( '115', 'Tagalog' );
INSERT INTO eZAddress_Language VALUES ( '116', 'Tajik' );
INSERT INTO eZAddress_Language VALUES ( '117', 'Tamil' );
INSERT INTO eZAddress_Language VALUES ( '118', 'Tatar' );
INSERT INTO eZAddress_Language VALUES ( '119', 'Tegulu' );
INSERT INTO eZAddress_Language VALUES ( '120', 'Thai' );
INSERT INTO eZAddress_Language VALUES ( '121', 'Tibetan' );
INSERT INTO eZAddress_Language VALUES ( '122', 'Tigrinya' );
INSERT INTO eZAddress_Language VALUES ( '123', 'Tonga' );
INSERT INTO eZAddress_Language VALUES ( '124', 'Tsonga' );
INSERT INTO eZAddress_Language VALUES ( '125', 'Turkish' );
INSERT INTO eZAddress_Language VALUES ( '126', 'Turkmen' );
INSERT INTO eZAddress_Language VALUES ( '127', 'Twi' );
INSERT INTO eZAddress_Language VALUES ( '128', 'Ukrainian' );
INSERT INTO eZAddress_Language VALUES ( '129', 'Urdu' );
INSERT INTO eZAddress_Language VALUES ( '130', 'Uzbek' );
INSERT INTO eZAddress_Language VALUES ( '131', 'Vietnamese' );
INSERT INTO eZAddress_Language VALUES ( '132', 'Volapuk' );
INSERT INTO eZAddress_Language VALUES ( '133', 'Welsh' );
INSERT INTO eZAddress_Language VALUES ( '134', 'Wolof' );
INSERT INTO eZAddress_Language VALUES ( '135', 'Xhosa' );
INSERT INTO eZAddress_Language VALUES ( '136', 'Yiddish' );
INSERT INTO eZAddress_Language VALUES ( '137', 'Yoruba' );
INSERT INTO eZAddress_Language VALUES ( '138', 'Zulu' );

CREATE TABLE eZAddress_Online (
  ID int NOT NULL,
  URL varchar(255),
  OnlineTypeID int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAddress_OnlineType (
  ID int DEFAULT '0' NOT NULL,
  Name varchar(50),
  ListOrder int DEFAULT '0' NOT NULL,
  URLPrefix varchar(30) DEFAULT '' NOT NULL,
  PrefixLink int DEFAULT '0' NOT NULL,
  PrefixVisual int DEFAULT '0' NOT NULL,
  Removed int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_OnlineType VALUES (1,'Email',1,'mailto:',1,0,0);

CREATE TABLE eZAddress_Phone (
  ID int NOT NULL,
  Number varchar(22),
  PhoneTypeID int,
  PRIMARY KEY (ID)
);

CREATE TABLE eZAddress_PhoneType (
  ID int NOT NULL,
  Name varchar(50),
  ListOrder int DEFAULT '0' NOT NULL,
  Removed int DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO eZAddress_PhoneType VALUES (1,'Telefon',1,0);

