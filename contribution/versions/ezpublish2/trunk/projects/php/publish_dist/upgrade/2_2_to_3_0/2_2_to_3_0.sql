CREATE TABLE eZBug_Support (
  ID int(11) NOT NULL default '0',
  Name varchar(150),
  Email varchar(100) NOT NULL,
  ExpiryDate int(11),
  PRIMARY KEY (ID)
);
