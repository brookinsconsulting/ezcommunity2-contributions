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

INSERT INTO eZProcurement_BidRank VALUES (0,'Zero','0');

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
INSERT INTO eZProcurement_BidRank VALUES (21,'Twenty-First','21th');
INSERT INTO eZProcurement_BidRank VALUES (22,'Twenty-Second','22th');
INSERT INTO eZProcurement_BidRank VALUES (23,'Twenty-Third','23th');
INSERT INTO eZProcurement_BidRank VALUES (24,'Twenty-Fourth','24th');
INSERT INTO eZProcurement_BidRank VALUES (25,'Twenty-Fifth','25th');
INSERT INTO eZProcurement_BidRank VALUES (26,'Twenty-Sixth','26th');
INSERT INTO eZProcurement_BidRank VALUES (27,'Twenty-Seventh','27th');
INSERT INTO eZProcurement_BidRank VALUES (28,'Twenty-Eighth','28th');
INSERT INTO eZProcurement_BidRank VALUES (29,'Twenty-Nineth','29th');
INSERT INTO eZProcurement_BidRank VALUES (30,'Thirtieth','30th');
INSERT INTO eZProcurement_BidRank VALUES (31,'Thirtieth-First','31th');



