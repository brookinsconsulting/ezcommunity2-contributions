CREATE TABLE eZTodo_Category (
  Description text,
  ID int NOT NULL,
  Name varchar(30),
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Priority (
  ID int NOT NULL,
  Name varchar(30),
  PRIMARY KEY (ID)
);


CREATE TABLE eZTodo_Todo (
  Category int,
  Priority int,
  Permission int default 0,
  ID int NOT NULL,
  UserID int,
  OwnerID int,
  Name varchar(30),
  Date int,
  Due int,
  Description text,
  Status int DEFAULT '0',
  PRIMARY KEY (ID)
);

CREATE TABLE eZTodo_Status (
  Description text,
  ID int NOT NULL,
  Name varchar(30),
  PRIMARY KEY (ID)
);

INSERT INTO eZTodo_Status (Description, ID, Name ) VALUES (NULL,1,'Not done');
INSERT INTO eZTodo_Status (Description, ID, Name ) VALUES (NULL,2,'Done');

INSERT INTO eZTodo_Priority (ID, Name ) VALUES (1,'Low');
INSERT INTO eZTodo_Priority (ID, Name ) VALUES (2,'Medium');
INSERT INTO eZTodo_Priority (ID, Name ) VALUES (3,'High');
