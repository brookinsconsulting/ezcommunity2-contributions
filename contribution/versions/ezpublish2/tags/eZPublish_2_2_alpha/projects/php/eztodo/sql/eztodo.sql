#
# Table structure for table 'eZTodo_Category'
#
DROP TABLE IF EXISTS eZTodo_Category;
CREATE TABLE eZTodo_Category (
  Description text,
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(30),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTodo_Category'
#

INSERT INTO eZTodo_Category VALUES (NULL,1,'Bugfix');
INSERT INTO eZTodo_Category VALUES (NULL,2,'Programming');

#
# Table structure for table 'eZTodo_Priority'
#
DROP TABLE IF EXISTS eZTodo_Priority;
CREATE TABLE eZTodo_Priority (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(30),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTodo_Priority'
#

INSERT INTO eZTodo_Priority VALUES (1,'Low');
INSERT INTO eZTodo_Priority VALUES (2,'Medium');
INSERT INTO eZTodo_Priority VALUES (3,'High');

#
# Table structure for table 'eZTodo_Todo'
#
DROP TABLE IF EXISTS eZTodo_Todo;
CREATE TABLE eZTodo_Todo (
  Category int(11),
  Priority int(11),
  Permission enum('Public','Private') DEFAULT 'Private',
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11),
  OwnerID int(11),
  Name varchar(30),
  Date timestamp(14),
  Due timestamp(14),
  Description text,
  Status int(11) DEFAULT '0',
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTodo_Todo'
#

INSERT INTO eZTodo_Todo VALUES (2,1,'Private',1,27,27,'This is a test Todo',20010116142211,00000000000000,'Please add this feature.',1);

#
# Table structure for table 'eZTodo_Status'
#
DROP TABLE IF EXISTS eZTodo_Status;
CREATE TABLE eZTodo_Status (
  Description text,
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  Name varchar(30),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZTodo_Status'
#

INSERT INTO eZTodo_Status VALUES (NULL,1,'Not done');
INSERT INTO eZTodo_Status VALUES (NULL,2,'Done');
