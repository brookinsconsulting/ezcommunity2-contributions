CREATE TABLE eZQuiz_Alternative (
  ID int NOT NULL,
  QuestionID int default '0',
  Name char(100) default NULL,
  IsCorrect int default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

INSERT INTO eZQuiz_Alternative VALUES (1,1,'',0);
INSERT INTO eZQuiz_Alternative VALUES (2,2,'test 1',1);
INSERT INTO eZQuiz_Alternative VALUES (3,2,'test 2',0);

CREATE TABLE eZQuiz_Answer (
  ID int NOT NULL,
  UserID int default '0',
  AlternativeID int default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZQuiz_Game (
  ID int NOT NULL auto_increment,
  Name varchar(30) default NULL,
  Description text,
  StartDate date default NULL,
  StopDate date default NULL,
  PRIMARY KEY (ID)
) TYPE=MyISAM;

INSERT INTO eZQuiz_Game VALUES (1,'test','wegwegweg','2001-12-12','0000-00-00');

CREATE TABLE eZQuiz_Question (
  ID int NOT NULL auto_increment,
  Name char(100) default NULL,
  GameID int default '0',
  Placement int default '0',
  Score int default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

INSERT INTO eZQuiz_Question VALUES (1,'hei å hå',1,0,0);
INSERT INTO eZQuiz_Question VALUES (2,'',1,1,0);

CREATE TABLE eZQuiz_Score (
  ID int NOT NULL auto_increment,
  GameID int default '0',
  UserID int default '0',
  TotalScore int default '0',
  LastQuestion int default '0',
  FinishedGame int default '1',
  PRIMARY KEY (ID)
) TYPE=MyISAM;

CREATE TABLE eZQuiz_AllTimeScore (
  ID int NOT NULL auto_increment,
  UserID int default '0',
  TotalScore int default '0',
  GamesPlayed int default '0',
  PRIMARY KEY (ID)
) TYPE=MyISAM;
