CREATE TABLE eZTodo_Category (
  Description text,
  ID int(11) NOT NULL,
  Name varchar(30),
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Priority (
  ID int(11) NOT NULL,
  Name varchar(30),
  PRIMARY KEY (ID)
);


CREATE TABLE eZTodo_Todo (
  Category int(11),
  Priority int(11),
  Permission int(11) default 0,
  ID int(11) NOT NULL,
  UserID int(11),
  OwnerID int(11),
  Name varchar(30),
  Date int(11),
  Due int(11),
  Description text,
  Status int(11) DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Status (
  Description text,
  ID int(11) NOT NULL,
  Name varchar(30),
  PRIMARY KEY (ID)
);

INSERT INTO eZTodo_Status (Description, ID, Name ) VALUES (NULL,1,'Not done');
INSERT INTO eZTodo_Status (Description, ID, Name ) VALUES (NULL,2,'Done');

INSERT INTO eZTodo_Priority (ID, Name ) VALUES (1,'Low');
INSERT INTO eZTodo_Priority (ID, Name ) VALUES (2,'Medium');
INSERT INTO eZTodo_Priority (ID, Name ) VALUES (3,'High');
