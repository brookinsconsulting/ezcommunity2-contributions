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
   Location varchar(255) default NULL,
   Status int(11) DEFAULT '1' NOT NULL,
   EventAlarmNotice int(11) DEFAULT '0' NOT NULL,
   EventCategoryID int(11) DEFAULT '0' NOT NULL,
   Priority int(11) DEFAULT '1' NOT NULL,
   IsRecurring int default '0',
   RecurringDay int default NULL,
   RecurringMonth int default NULL,
   RecurringYear int default NULL,
   RepeatForever int(11) DEFAULT '0' NOT NULL,
   RepeatTimes int(11) DEFAULT '0' NOT NULL,
   RepeatUntilDate timestamp(14) default NULL,
   RepeatExceptionsDates text default NULL,
   PRIMARY KEY (ID)
);


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
# Table structure for table 'eZGroupEventCalendar_EventType'
#

CREATE TABLE eZGroupEventCalendar_EventType (
   ID int(11) NOT NULL auto_increment,
   ParentID int(11) DEFAULT '0' NOT NULL,
   Description text,
   Name varchar(255),
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
# Table structure for table 'eZGroupEventCalendar_EventForumLink'
#

CREATE TABLE `eZGroupEventCalendar_EventForumLink` (
  `ID` int(11) NOT NULL default '0',
  `EventID` int(11) NOT NULL default '0',
  `ForumID` int(11) NOT NULL default '0',
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
