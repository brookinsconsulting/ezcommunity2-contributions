eZGroupEventCalendar
Version : 2.0.0

Updated By: Graham Brookins : Brookins Consulting : <info|at|brookinsconsulting|dot|com>
Created By: Adam Fallert <FallertA@umsystem.edu>
Updated on: <Nov-2004 11:00:00>
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

Step 3: Add the following lines to your eZ publish's site.ini file and modify the settings as needed.  For more information about these lines please read /ezgroupeventcalendar/site.ini.add

        [eZGroupEventCalendarMain]
	AdminTemplateDir=templates/standard/
	TemplateDir=templates/standard/
	ImageDir=/images/standard/
	Language=en_US
	DayStartTime=00:00
	DayStopTime=24:00
	DayInterval=00:15
	Priority=2
	Status=0
	SubGroupSelect=disabled
	TwelveHourSelect=enabled
	MinutesSelectInterval=15
	UserComments=enabled
	TruncateTitle=enabled
	LinkModules=eZGroupEventCalendar:1000
	YearsPrint=19

Step 4: Add these lines to the head of your sitedesign frame, in the html head section, just below the default eZ publish stylesheet include (in ie: sitedesign/standard/frame.php)

<style type="text/css">
@import url(/ezgroupeventcalendar/user/templates/standard/style.css);
</style>

<script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/overlib/overlib.js"></script>
<script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/dom-drag.js"></script>

<!-- eZGroupEventCalendar:jscalendar style dependancies -->
<link rel="alternate stylesheet" type="text/css" media="all" href="/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar-system.css" title="system" />

<!-- eZGroupEventCalendar:jscalendar script dependancies -->
  <script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar.js"></script>
  <script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar-setup.js"></script>
  <script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/jscalendar/lang/calendar-en.js"></script>
  <script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar-setup-instance.js"></script>

Step 5: To access the Group Event Calendar the URL is as follows 
        http://YourWebsiteUrl/groupeventcalendar/monthview/

Step 6: To Administer the Group Event Calendar (Add,Edit,Delete Events), first login to the eZ publish site with a user account which is in the Administrator Group. Then visit the URL : http://YourWebsiteUrl/groupeventcalendar/monthview/

Step 7: (Optional) Edit the clearcache.sh script and add the ezgroupeventcalendar module name to the list of modules to clear cache directories. Variable:
dirs="
ezad
ezgroupeventcalendar

Alternalty you may use the version provided in ezgroupeventcalendar/doc/clearcache.sh



