#
# Table structure for table 'eZCalendar_Appointment'
#
DROP TABLE IF EXISTS eZCalendar_Appointment;
CREATE TABLE eZCalendar_Appointment (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  UserID int(11) DEFAULT '0' NOT NULL,
  Date timestamp(14),
  Duration time,
  AppointmentTypeID int(11) DEFAULT '0' NOT NULL,
  EMailNotice int(11) DEFAULT '0',
  IsPrivate int(11),
  Name varchar(200),
  Description text,
  Priority int(11) DEFAULT '1' NOT NULL,
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZCalendar_Appointment'
#




#
# Table structure for table 'eZCalendar_AppointmentType'
#
DROP TABLE IF EXISTS eZCalendar_AppointmentType;
CREATE TABLE eZCalendar_AppointmentType (
  ID int(11) DEFAULT '0' NOT NULL auto_increment,
  ParentID int(11) DEFAULT '0' NOT NULL,
  Description text,
  Name varchar(200),
  PRIMARY KEY (ID)
);

#
# Dumping data for table 'eZCalendar_AppointmentType'
#




