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

INSERT INTO eZLink_Hit VALUES (1,1,20001101194225,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (2,1,20001101194230,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (3,1,20001101194347,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (4,1,20001101194442,'10.0.2.3');
INSERT INTO eZLink_Hit VALUES (5,1,20001101201636,'10.0.2.3');

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

INSERT INTO eZLink_Link VALUES (1,'eZ systems as','Linux and open source solutions made easy.',1,'linux open source',20001101115659,'Y','2000-11-01 11:56:59','ez.no');

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

INSERT INTO eZLink_LinkGroup VALUES (1,0,'Cool links');
INSERT INTO eZLink_LinkGroup VALUES (2,0,'Not so cool links');
