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

