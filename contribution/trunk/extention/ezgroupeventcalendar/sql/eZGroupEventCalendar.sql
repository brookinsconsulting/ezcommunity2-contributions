DROP TABLE IF EXISTS eZGroupEventCalendar_Event;
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
   Name varchar(255),
   Description text,
   Url text default NULL,
   Status int(11) DEFAULT '1' NOT NULL,
   EventAlarmNotice int(11) DEFAULT '0' NOT NULL,
   EventCategoryID int(11) DEFAULT '0' NOT NULL,
   Priority int(11) DEFAULT '1' NOT NULL,
   IsRecurring int default '0',
   RecurFreq int default NULL,
   RecurType varchar(255) default NULL,
   RecurDay varchar(255) default NULL,
   RecurMonthly varchar(255) default NULL,
   RecurMonthlyType varchar(32) default NULL,
   RecurMonthlyTypeInfo varchar(64) default NULL,
   Location int default NULL,
   RepeatForever int(11) DEFAULT '0' NOT NULL,
   RepeatTimes int(11) DEFAULT '0' NOT NULL,
   RepeatUntilDate timestamp(14),
   RecurExceptions text default NULL,
   PRIMARY KEY (ID)
);
DROP TABLE IF EXISTS eZGroupEventCalendar_EventCategory;
#
# Table structure for table 'eZGroupEventCalendar_EventCategory'
#
CREATE TABLE eZGroupEventCalendar_EventCategory (
   ID int(11) NOT NULL auto_increment,
   ParentID int(11) DEFAULT '0' NOT NULL,
   Description text,
   Name varchar(255),
   PRIMARY KEY (ID)
);

#
# Dumping data for table 'ezGroupEventCalendar_Event
#

INSERT INTO eZGroupEventCalendar_EventCategory VALUES (1,0,'Personal Events','Personal');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (2,1,'Birthdays Events','Birthdays');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (3,1,'Vacation Event','Vacation');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (4,1,'Travel Event','Travel');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (5,0,'Business Event','Business');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (6,5,'Business Calls','Calls');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (7,5,'Clients Events','Clients');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (8,5,'Competition Events','Competition');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (9,5,'Customer Events','Customer');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (10,5,'Favorites Events','Favorites');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (11,5,'Follow up Events','Follow up');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (12,5,'Gifts Events','Gifts');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (13,5,'Holidays Events','Holidays');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (14,5,'Ideas Events','Ideas');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (15,5,'Issues Events','Issues');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (16,5,'Miscellaneous Events','Miscellaneous');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (17,5,'Projects Events','Projects');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (18,5,'Public Holiday Events','Public Holiday');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (19,5,'Status Events','Status');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (20,5,'Suppliers Events','Suppliers');
INSERT INTO eZGroupEventCalendar_EventCategory VALUES (21,5,'Travel Events','Travel');


DROP TABLE IF EXISTS eZGroupEventCalendar_EventType;
#
# Table structure for table 'eZGroupEventCalendar_EventType'
#
CREATE TABLE eZGroupEventCalendar_EventType (
   ID int(11) NOT NULL auto_increment,
   ParentID int(11) DEFAULT '0' NOT NULL,
   Description text,
   Name varchar(255),
   PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS eZGroupEventCalendar_GroupEditor;
#'
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

DROP TABLE IF EXISTS eZGroupEventCalendar_GroupNoShow;
#
# Table structure for table 'eZGroupEventCalendar_GroupNoShow'
#
CREATE TABLE eZGroupEventCalendar_GroupNoShow (
   ID int(11) NOT NULL auto_increment,
   GroupID int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS eZGroupEventCalendar_EventForumLink;
#
# Table structure for table 'eZGroupEventCalendar_EventForumLink'
#

CREATE TABLE `eZGroupEventCalendar_EventForumLink` (
  `ID` int(11) NOT NULL default '0',
  `EventID` int(11) NOT NULL default '0',
  `ForumID` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS eZGroupEventCalendar_EventFileLink;
#
# Table structure for table 'eZGroupEventCalendar_EventFileLink'
#

CREATE TABLE `eZGroupEventCalendar_EventFileLink` (
  `ID` int(11) NOT NULL default '0',
  `EventID` int(11) NOT NULL default '0',
  `FileID` int(11) NOT NULL default '0',
  `Created` int(11) NOT NULL,
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;



#
# Dumping data for table 'eZUser_Module'
#

INSERT INTO eZUser_Module (Name) VALUES ('eZGroupEventCalendar');

#
# Dumping data for table 'eZUser_Permission'
#

INSERT INTO eZUser_Permission (ModuleID, Name) SELECT ID AS ModuleID, 'Read' AS Name FROM eZUser_Module WHERE Name='eZGroupEventCalendar';
INSERT INTO eZUser_Permission (ModuleID, Name) SELECT ID AS ModuleID, 'WriteToRoot' AS Name FROM eZUser_Module WHERE Name='eZGroupEventCalendar';
