-- eZ Procurement (Adendum SQL)
-- #####################################################################

--
-- Table structure for table `eZProcurement_ProcurementHolderDefinition`
--

CREATE TABLE eZProcurement_ProcurementHolderDefinition (
  ID int(11) NOT NULL default '0',
  PersonID int(11) NOT NULL default '0',
  UserID int(11) default NULL,
  ProcurementID int(11) NOT NULL,
  PRIMARY KEY  (ID,PersonID,ProcurementID)
) TYPE=MyISAM;

--
-- Dumping data for table `eZProcurement_ProcurementHolderDefinition`
--


INSERT INTO eZProcurement_ProcurementHolderDefinition VALUES (1,2,3,1);
INSERT INTO eZProcurement_ProcurementHolderDefinition VALUES (2,2,3,2);
INSERT INTO eZProcurement_ProcurementHolderDefinition VALUES (3,3,21,3);
INSERT INTO eZProcurement_ProcurementHolderDefinition VALUES (4,3,21,2);
INSERT INTO eZProcurement_ProcurementHolderDefinition VALUES (5,5,4,3);
INSERT INTO eZProcurement_ProcurementHolderDefinition VALUES (6,3,21,1);


--
-- Table structure for table `eZProcurement_Bid`
--

CREATE TABLE eZProcurement_Bid (
  ID int(11) NOT NULL,
  ProcurementID int(11) NOT NULL,
  RankID int(11) default NULL,
  UserID int(11) default NULL,
  PersonID int(11) NOT NULL,
  CompanyID int(11) default NULL,
  Amount float(10,5) default NULL,
  Winner int(11) NOT NULL default '0',
  Removed int(11) NOT NULL default '0',
  PRIMARY KEY  (ID,PersonID,ProcurementID)
) TYPE=MyISAM;

--
-- Dumping data for table `eZProcurement_Bid`
--

INSERT INTO eZProcurement_Bid VALUES (0,0,1,0,0,0,5000.02,0,0);
INSERT INTO eZProcurement_Bid VALUES (1,2,1,3,2,1,4000.04,0,0);

--
-- Table structure for table `eZProcurement_BidRank`
--

CREATE TABLE eZProcurement_BidRank (
  ID int(11) NOT NULL,
  Name varchar(250) NOT NULL,
  AlphaNumericName varchar(250) NOT NULL,
  PRIMARY KEY  (ID,Name)
) TYPE=MyISAM;

--
-- Dumping data for table `eZProcurement_BidRank`
--

INSERT INTO eZProcurement_BidRank VALUES (0,'Zero','');

INSERT INTO eZProcurement_BidRank VALUES (1,'First','1st');
INSERT INTO eZProcurement_BidRank VALUES (2,'Second','2nd');
INSERT INTO eZProcurement_BidRank VALUES (3,'Third','3rd');
INSERT INTO eZProcurement_BidRank VALUES (4,'Fourth','4th');
INSERT INTO eZProcurement_BidRank VALUES (5,'Fifth','5th');
INSERT INTO eZProcurement_BidRank VALUES (6,'Sixth','6th');
INSERT INTO eZProcurement_BidRank VALUES (7,'Seventh','7th');
INSERT INTO eZProcurement_BidRank VALUES (8,'Eighth','8th');
INSERT INTO eZProcurement_BidRank VALUES (9,'Nineth','9th');
INSERT INTO eZProcurement_BidRank VALUES (10,'Tenth','10th');

INSERT INTO eZProcurement_BidRank VALUES (11,'Eleventh','11th');
INSERT INTO eZProcurement_BidRank VALUES (12,'Twelfth','12th');
INSERT INTO eZProcurement_BidRank VALUES (13,'Thirteenth','13th');
INSERT INTO eZProcurement_BidRank VALUES (14,'Fourteenth','14th');
INSERT INTO eZProcurement_BidRank VALUES (15,'Fifteenth','15th');
INSERT INTO eZProcurement_BidRank VALUES (16,'Sixteenth','16th');
INSERT INTO eZProcurement_BidRank VALUES (17,'Seventeenth','17th');
INSERT INTO eZProcurement_BidRank VALUES (18,'Eighteenth','18th');
INSERT INTO eZProcurement_BidRank VALUES (19,'Nineteenth','19th');

INSERT INTO eZProcurement_BidRank VALUES (20,'Twentieth','20th');
INSERT INTO eZProcurement_BidRank VALUES (21,'Twenty-First','21st');
INSERT INTO eZProcurement_BidRank VALUES (22,'Twenty-Second','22nd');
INSERT INTO eZProcurement_BidRank VALUES (23,'Twenty-Third','23rd');
INSERT INTO eZProcurement_BidRank VALUES (24,'Twenty-Fourth','24th');
INSERT INTO eZProcurement_BidRank VALUES (25,'Twenty-Fifth','25th');
INSERT INTO eZProcurement_BidRank VALUES (26,'Twenty-Sixth','26th');
INSERT INTO eZProcurement_BidRank VALUES (27,'Twenty-Seventh','27th');
INSERT INTO eZProcurement_BidRank VALUES (28,'Twenty-Eighth','28th');
INSERT INTO eZProcurement_BidRank VALUES (29,'Twenty-Nineth','29th');

INSERT INTO eZProcurement_BidRank VALUES (30,'Thirtieth','30th');
INSERT INTO eZProcurement_BidRank VALUES (31,'Thirty-First','31st');
INSERT INTO eZProcurement_BidRank VALUES (32,'Thirty-Second','32nd');
INSERT INTO eZProcurement_BidRank VALUES (33,'Thirty-Third','33rd');
INSERT INTO eZProcurement_BidRank VALUES (34,'Thirty-Fourth','34th');
INSERT INTO eZProcurement_BidRank VALUES (35,'Thirty-Fifth','35th');
INSERT INTO eZProcurement_BidRank VALUES (36,'Thirty-Sixth','36th');
INSERT INTO eZProcurement_BidRank VALUES (37,'Thirty-Seventh','37th');
INSERT INTO eZProcurement_BidRank VALUES (38,'Thirty-Eighth','38th');
INSERT INTO eZProcurement_BidRank VALUES (39,'Thirty-Nineth','39th');

INSERT INTO eZProcurement_BidRank VALUES (40,'Fortieth','40th');
INSERT INTO eZProcurement_BidRank VALUES (41,'Forty-First','41st');
INSERT INTO eZProcurement_BidRank VALUES (42,'Forty-Second','42nd');
INSERT INTO eZProcurement_BidRank VALUES (43,'Forty-Third','43rd');
INSERT INTO eZProcurement_BidRank VALUES (44,'Forty-Fourth','44th');
INSERT INTO eZProcurement_BidRank VALUES (45,'Forty-Fifth','45th');
INSERT INTO eZProcurement_BidRank VALUES (46,'Forty-Sixth','46th');
INSERT INTO eZProcurement_BidRank VALUES (47,'Forty-Seventh','47th');
INSERT INTO eZProcurement_BidRank VALUES (48,'Forty-Eighth','48th');
INSERT INTO eZProcurement_BidRank VALUES (49,'Forty-Nineth','49th');

INSERT INTO eZProcurement_BidRank VALUES (50,'Fiftieth','50th');
INSERT INTO eZProcurement_BidRank VALUES (51,'Fifty-First','51st');
INSERT INTO eZProcurement_BidRank VALUES (52,'Fifty-Second','52nd');
INSERT INTO eZProcurement_BidRank VALUES (53,'Fifty-Third','53rd');
INSERT INTO eZProcurement_BidRank VALUES (54,'Fifty-Fourth','54th');
INSERT INTO eZProcurement_BidRank VALUES (55,'Fifty-Fifth','55th');
INSERT INTO eZProcurement_BidRank VALUES (56,'Fifty-Sixth','56th');
INSERT INTO eZProcurement_BidRank VALUES (57,'Fifty-Seventh','57th');
INSERT INTO eZProcurement_BidRank VALUES (58,'Fifty-Eighth','58th');
INSERT INTO eZProcurement_BidRank VALUES (59,'Fifty-Nineth','59th');

INSERT INTO eZProcurement_BidRank VALUES (60,'Sixtieth','60th');
INSERT INTO eZProcurement_BidRank VALUES (61,'Sixty-First','61st');
INSERT INTO eZProcurement_BidRank VALUES (62,'Sixty-Second','62nd');
INSERT INTO eZProcurement_BidRank VALUES (63,'Sixty-Third','63rd');
INSERT INTO eZProcurement_BidRank VALUES (64,'Sixty-Fourth','64th');
INSERT INTO eZProcurement_BidRank VALUES (65,'Sixty-Fifth','65th');
INSERT INTO eZProcurement_BidRank VALUES (66,'Sixty-Sixth','66th');
INSERT INTO eZProcurement_BidRank VALUES (67,'Sixty-Seventh','67th');
INSERT INTO eZProcurement_BidRank VALUES (68,'Sixty-Eighth','68th');
INSERT INTO eZProcurement_BidRank VALUES (69,'Sixty-Nineth','69th');


INSERT INTO eZProcurement_BidRank VALUES (70,'Seventieth','70th');
INSERT INTO eZProcurement_BidRank VALUES (71,'Seventy-First','71st');
INSERT INTO eZProcurement_BidRank VALUES (72,'Seventy-Second','72nd');
INSERT INTO eZProcurement_BidRank VALUES (73,'Seventy-Third','73rd');
INSERT INTO eZProcurement_BidRank VALUES (74,'Seventy-Fourth','74th');
INSERT INTO eZProcurement_BidRank VALUES (75,'Seventy-Fifth','75th');
INSERT INTO eZProcurement_BidRank VALUES (76,'Seventy-Sixth','76th');
INSERT INTO eZProcurement_BidRank VALUES (77,'Seventy-Seventh','77th');
INSERT INTO eZProcurement_BidRank VALUES (78,'Seventy-Eighth','78th');
INSERT INTO eZProcurement_BidRank VALUES (79,'Seventy-Nineth','79th');


INSERT INTO eZProcurement_BidRank VALUES (80,'Eightieth','80th');
INSERT INTO eZProcurement_BidRank VALUES (81,'Eighty-First','81st');
INSERT INTO eZProcurement_BidRank VALUES (82,'Eighty-Second','82nd');
INSERT INTO eZProcurement_BidRank VALUES (83,'Eighty-Third','83rd');
INSERT INTO eZProcurement_BidRank VALUES (84,'Eighty-Fourth','84th');
INSERT INTO eZProcurement_BidRank VALUES (85,'Eighty-Fifth','85th');
INSERT INTO eZProcurement_BidRank VALUES (86,'Eighty-Sixth','86th');
INSERT INTO eZProcurement_BidRank VALUES (87,'Eighty-Seventh','87th');
INSERT INTO eZProcurement_BidRank VALUES (88,'Eighty-Eighth','88th');
INSERT INTO eZProcurement_BidRank VALUES (89,'Eighty-Nineth','89th');


INSERT INTO eZProcurement_BidRank VALUES (90,'Ninetieth','90th');
INSERT INTO eZProcurement_BidRank VALUES (91,'Ninety-First','91st');
INSERT INTO eZProcurement_BidRank VALUES (92,'Ninety-Second','92nd');
INSERT INTO eZProcurement_BidRank VALUES (93,'Ninety-Third','93rd');
INSERT INTO eZProcurement_BidRank VALUES (94,'Ninety-Fourth','94th');
INSERT INTO eZProcurement_BidRank VALUES (95,'Ninety-Fifth','95th');
INSERT INTO eZProcurement_BidRank VALUES (96,'Ninety-Sixth','96th');
INSERT INTO eZProcurement_BidRank VALUES (97,'Ninety-Seventh','97th');
INSERT INTO eZProcurement_BidRank VALUES (98,'Ninety-Eighth','98th');
INSERT INTO eZProcurement_BidRank VALUES (99,'Ninety-Nineth','99th');

INSERT INTO eZProcurement_BidRank VALUES (100,'Hundredth','100th');


