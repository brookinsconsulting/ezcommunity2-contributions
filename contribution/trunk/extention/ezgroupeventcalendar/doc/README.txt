eZGroupEventCalendar

Adam Fallert <FallertA@umsystem.edu>
Created on: <Oct-2001 14:36:00>

These source files are part of eZ publish, publishing software, 
and developed for the Mobius Consortium Office.
Copyright © 2001 MOBIUS Consortium as

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US

INSTALATION

Step 1: Copy entire directory structure of eZGroupEventCalendar into 
	the documentment root of eZPublish.

Step 2: Execute the sql located in 
        .../ezgroupeventcalendar/sql/ezGroupEventCalendar.sql
        from your command prompt.

	example:  # mysql -uroot -p publish < /ezgroupeventcalendar/sql/ezGroupEventCalendar.sql

        If you are using another database besides MySQL check the 
	sql syntext located in/ezgroupeventcalendar/sql/ezGroupEventCalendar.sql 
	for compatability with your SQL server

Step 3: Add the following lines to site.ini and modify as needed.  For more information
        about these lines please read /ezgroupeventcalendar/site.ini.add

        [eZGroupEventCalendarMain]
	AdminTemplateDir=templates/standard/
	TemplateDir=templates/standard/
	ImageDir=/images/standard/
	Language=en_GB
	DayStartTime=08:00
	DayStopTime=20:00
	DayInterval=00:30
	YearsPrint=19
	Priority=1
	SubGroupSelect=disabled
	TwelveHourSelect=enabled
	MinutesSelectInterval=15

Step 4: To access the Group Event Calendar the URL is as follows 
        http://YourWebsiteUrl/groupeventcalendar/monthview/

	


