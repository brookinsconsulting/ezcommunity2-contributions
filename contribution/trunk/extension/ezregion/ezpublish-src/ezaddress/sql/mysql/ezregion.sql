#
# Update Table structure for table 'eZAddress_Address'
#

Alter Table eZAddress_Address add column RegionID int(11) null

#
# Update Table records data for table 'eZAddress_Address'
#

Update eZAddress_Address Set RegionID = 0 Where ID;

#
# Table structure for table 'eZAddress_Region'
#

CREATE TABLE eZAddress_Region (
  ID int(11) NOT NULL auto_increment,
  CountryID int(11),
  Abbreviation char(10),
  Name char(100),
  UserAdded int(1) DEFAULT '0' NOT NULL,
  Removed int(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZAddress_Region'
#

INSERT INTO eZAddress_Region VALUES (1,240,'AL','Alabama',0,0);
INSERT INTO eZAddress_Region VALUES (2,240,'AK','Alaska',0,0);
INSERT INTO eZAddress_Region VALUES (3,240,'AZ','Arizona',0,0);
INSERT INTO eZAddress_Region VALUES (4,240,'AR','Arkansas',0,0);
INSERT INTO eZAddress_Region VALUES (5,240,'CA','California',0,0);
INSERT INTO eZAddress_Region VALUES (6,240,'CO','Colorado',0,0);
INSERT INTO eZAddress_Region VALUES (7,240,'CT','Connecticut',0,0);
INSERT INTO eZAddress_Region VALUES (8,240,'DE','Delaware',0,0);
INSERT INTO eZAddress_Region VALUES (9,240,'FL','Florida',0,0);
INSERT INTO eZAddress_Region VALUES (10,240,'GA','Georgia',0,0);
INSERT INTO eZAddress_Region VALUES (11,240,'HI','Hawaii',0,0);
INSERT INTO eZAddress_Region VALUES (12,240,'ID','Idaho',0,0);
INSERT INTO eZAddress_Region VALUES (13,240,'IL','Illinois',0,0);
INSERT INTO eZAddress_Region VALUES (14,240,'IN','Indiana',0,0);
INSERT INTO eZAddress_Region VALUES (15,240,'IA','Iowa',0,0);
INSERT INTO eZAddress_Region VALUES (16,240,'KS','Kansas',0,0);
INSERT INTO eZAddress_Region VALUES (17,240,'KY','Kentucky',0,0);
INSERT INTO eZAddress_Region VALUES (18,240,'LA','Louisiana',0,0);
INSERT INTO eZAddress_Region VALUES (19,240,'ME','Maine',0,0);
INSERT INTO eZAddress_Region VALUES (20,240,'MD','Maryland',0,0);
INSERT INTO eZAddress_Region VALUES (21,240,'MA','Massachusetts',0,0);
INSERT INTO eZAddress_Region VALUES (22,240,'MI','Michigan',0,0);
INSERT INTO eZAddress_Region VALUES (23,240,'MN','Minnesota',0,0);
INSERT INTO eZAddress_Region VALUES (24,240,'MS','Mississippi',0,0);
INSERT INTO eZAddress_Region VALUES (25,240,'MO','Missouri',0,0);
INSERT INTO eZAddress_Region VALUES (26,240,'MT','Montana',0,0);
INSERT INTO eZAddress_Region VALUES (27,240,'NE','Nebraska',0,0);
INSERT INTO eZAddress_Region VALUES (28,240,'NV','Nevada',0,0);
INSERT INTO eZAddress_Region VALUES (29,240,'NH','New Hampshire',0,0);
INSERT INTO eZAddress_Region VALUES (30,240,'NJ','New Jersey',0,0);
INSERT INTO eZAddress_Region VALUES (31,240,'NY','New Mexico',0,0);
INSERT INTO eZAddress_Region VALUES (32,240,'NY','New York',0,0);
INSERT INTO eZAddress_Region VALUES (33,240,'NC','North Carolina',0,0);
INSERT INTO eZAddress_Region VALUES (34,240,'ND','North Dakota',0,0);
INSERT INTO eZAddress_Region VALUES (35,240,'OH','Ohio',0,0);
INSERT INTO eZAddress_Region VALUES (36,240,'OK','Oklahoma',0,0);
INSERT INTO eZAddress_Region VALUES (37,240,'OR','Oregon',0,0);
INSERT INTO eZAddress_Region VALUES (38,240,'PA','Pennsylvania',0,0);
INSERT INTO eZAddress_Region VALUES (39,240,'RI','Rhode Island',0,0);
INSERT INTO eZAddress_Region VALUES (40,240,'SC','South Carolina',0,0);
INSERT INTO eZAddress_Region VALUES (41,240,'SD','South Dakota',0,0);
INSERT INTO eZAddress_Region VALUES (42,240,'TN','Tennessee',0,0);
INSERT INTO eZAddress_Region VALUES (43,240,'TX','Texas',0,0);
INSERT INTO eZAddress_Region VALUES (44,240,'UT','Utah',0,0);
INSERT INTO eZAddress_Region VALUES (45,240,'VT','Vermont',0,0);
INSERT INTO eZAddress_Region VALUES (46,240,'VA','Virginia',0,0);
INSERT INTO eZAddress_Region VALUES (47,240,'WA','Washington',0,0);
INSERT INTO eZAddress_Region VALUES (48,240,'WV','West Virginia',0,0);
INSERT INTO eZAddress_Region VALUES (49,240,'WI','Wisconsin',0,0);
INSERT INTO eZAddress_Region VALUES (50,240,'WY','Wyoming',0,0);
