#
# Table structure for table 'eZPoll_MainPoll'
#
DROP TABLE IF EXISTS eZPoll_MainPoll;
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
DROP TABLE IF EXISTS eZPoll_Poll;
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
DROP TABLE IF EXISTS eZPoll_PollChoice;
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
DROP TABLE IF EXISTS eZPoll_Vote;
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



