drop table ezsession_session;
drop table ezsession_sessionvariable;
drop table ezsession_preferences;

CREATE TABLE eZSession_Session(
  ID int NOT NULL,
  Hash varchar(33) default NULL,
  Created int NOT NULL,
  LastAccessed int NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE eZSession_SessionVariable(
  ID int NOT NULL,
  SessionID int not NULL,
  Name varchar(25) not NULL,
  Value varchar(50) not NULL,
  GroupName varchar(50) default NULL,
  PRIMARY KEY (ID)
);


CREATE TABLE eZSession_Preferences (
  ID int NOT NULL,
  UserID int NOT NULL,
  Name varchar(50) default NULL,
  Value varchar(255) default NULL,
  GroupName varchar(50) default NULL,
  PRIMARY KEY (ID)
);


CREATE INDEX Session_Hash ON eZSession_Session (Hash);
CREATE INDEX Session_VarSessID ON eZSession_SessionVariable (SessionID);
CREATE INDEX Session_VarName ON eZSession_SessionVariable (Name);
CREATE INDEX Session_VarValue ON eZSession_SessionVariable (Value);
CREATE INDEX Session_GroupName ON eZSession_SessionVariable (GroupName);

CREATE INDEX Preference_Name ON eZSession_Preferences (Name);
CREATE INDEX Preference_Value ON eZSession_Preferences (Value);
CREATE INDEX Preference_GroupName ON eZSession_Preferences (GroupName);
CREATE INDEX Preference_UserID ON eZSession_Preferences (UserID);
