#
# Table structure for table 'eZGroupEventCalendar_Event'
#

CREATE TABLE eZGroupEventCalendar_Event (
   ID int(11) NOT NULL auto_increment,
   GroupID int(11) DEFAULT '0' NOT NULL,
   Date timestamp(14),
   Duration time,
   EventTypeID int(11) DEFAULT '0' NOT NULL,
   EMailNotice int(11) DEFAULT '0',
   IsPrivate int(11),
   Name varchar(200),
   Description text,
   Priority int(11) DEFAULT '1' NOT NULL,
   PRIMARY KEY (ID)
);

#
# Table structure for table 'eZGroupEventCalendar_EventType'
#

CREATE TABLE eZGroupEventCalendar_EventType (
   ID int(11) NOT NULL auto_increment,
   ParentID int(11) DEFAULT '0' NOT NULL,
   Description text,
   Name varchar(200),
   PRIMARY KEY (ID)
);

#
# Table structure for table 'eZGroupEventCalendar_GroupEditor'
#

CREATE TABLE eZGroupEventCalendar_GroupEditor (
   ID int(11) NOT NULL auto_increment,
   UserID int(11),
   GroupID int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (ID),
   UNIQUE ID (ID),
   KEY ID_2 (ID)
);

#
# Table structure for table 'eZGroupEventCalendar_GroupNoShow'
#

CREATE TABLE eZGroupEventCalendar_GroupNoShow (
   ID int(11) NOT NULL auto_increment,
   GroupID int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZUser_Module'
#

INSERT INTO eZUser_Module (Name) VALUES ('eZGroupEventCalendar');

#
# Dumping data for table 'eZUser_Permission'
#

INSERT INTO eZUser_Permission (ModuleID, Name) SELECT ID AS ModuleID, 'Read' AS Name FROM eZUser_Module WHERE Name='eZGroupEventCalendar';
INSERT INTO eZUser_Permission (ModuleID, Name) SELECT ID AS ModuleID, 'WriteToRoot' AS Name FROM eZUser_Module WHERE Name='eZGroupEventCalendar';