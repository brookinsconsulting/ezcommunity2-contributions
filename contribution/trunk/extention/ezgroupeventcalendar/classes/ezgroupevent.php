<?
//
// Definition of eZEvent class
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZEventCalendar
//! eZEvent handles events.
/*!
  
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/eztime.php" );

include_once( "ezgroupeventcalendar/classes/ezgroupnoshow.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeventtype.php" );


class eZGroupEvent
{
    /*!
      Constructs a new eZGroupEvent object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZGroupEvent( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
            }
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZEvent object to the database.
    */
    function store()
    {
        $this->dbInit();

		$Name        = addslashes( $this->Name );
		$Description = addslashes( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZGroupEventCalendar_Event SET
                             Name='$Name',
                             Description='$Description',
                             Date='$this->Date',
                             Duration='$this->Duration',
                             Url='$this->Url',
                             Status='$this->Status',
                             Location='$this->Location',
                             EventAlarmNotice='$this->EventAlarmNotice',
                             EventCategoryID='$this->EventCategoryID',
                             IsRecurring='$this->IsRecurring',
                             RecurringDay='$this->RecurringDay',
                             RecurringMonth='$this->RecurringMonth',
                             RecurringYear='$this->RecurringYear',
                             RepeatTimes='$this->RepeatTimes',
                             RepeatUntilDate='$this->RepeatUntilDate',                             
                             EMailNotice='$this->EMailNotice',
                             IsPrivate='$this->IsPrivate',
                             Priority='$this->Priority',
                             EventTypeID='$this->EventTypeID',
 			     GroupID='$this->GroupID'" );
            
            $this->ID = $this->Database->insertID();
			
        }
        else
        {

	    $this->Database->query( "UPDATE eZGroupEventCalendar_Event SET
                             Name='$Name',
                             Description='$Description',
                             Date='$this->Date',
                             Duration='$this->Duration',
                             Url='$this->Url',
                             Status='$this->Status',
                             Location='$this->Location',
                             EventAlarmNotice='$this->EventAlarmNotice',
                             EventCategoryID='$this->EventCategoryID',
                             IsRecurring='$this->IsRecurring',
                             RecurringDay='$this->RecurringDay',
                             RecurringMonth='$this->RecurringMonth',
                             RecurringYear='$this->RecurringYear',
                             RepeatTimes='$this->RepeatTimes',
                             RepeatUntilDate='$this->RepeatUntilDate',
                             EMailNotice='$this->EMailNotice',
                             IsPrivate='$this->IsPrivate',
                             Priority='$this->Priority',
                             EventTypeID='$this->EventTypeID',
			     GroupID='$this->GroupID'
			     WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZGroupEvent object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZGroupEventCalendar_Event WHERE ID='$this->ID'" );
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $event_array, "SELECT * FROM eZGroupEventCalendar_Event WHERE ID='$id'" );

            if ( count( $event_array ) > 1 )
            {
                die( "Error: Event's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $event_array ) == 1 )
            {

                $this->ID =& $event_array[0][ "ID" ];
                $this->Name =& $event_array[0][ "Name" ];
                $this->Description =& $event_array[0][ "Description" ];
                $this->EventTypeID =& $event_array[0][ "EventTypeID" ];
	        $this->GroupID =& $event_array[0][ "GroupID" ];
                $this->Date =& $event_array[0][ "Date" ];
                $this->Duration =& $event_array[0][ "Duration" ];
                $this->IsPrivate =& $event_array[0][ "IsPrivate" ];
                $this->Priority =& $event_array[0][ "Priority" ];

		$this->Url =& $event_array[0][ "Url" ];
                $this->Location =& $event_array[0][ "Location" ];

                $this->Status =& $event_array[0][ "Status" ];
                $this->EventCategoryID =& $event_array[0][ "EventCategoryID" ];

                $this->EMailNotice =& $event_array[0][ "EventAlarmNotice" ];
                $this->EventAlarmNotice =& $event_array[0][ "EventAlarmNotice" ];

                $this->EventCategoryID =& $event_array[0][ "EventCategoryID" ];
                $this->IsRecurring =& $event_array[0][ "IsRecurring" ];
                $this->RecurringDay =& $event_array[0][ "RecurringDay" ];
                $this->RecurringMonth =& $event_array[0][ "RecurringMonth" ];
                $this->RecurringYear =& $event_array[0][ "RecurringYear" ];
                $this->RepeatTimes =& $event_array[0][ "RepeatTimes" ];
                $this->RepeatUntilDate =& $event_array[0][ "RepeatUntilDate" ];

                $this->State_ = "Coherent";
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the events found in the database.

      The events are returned as an array of eZEvent objects.
    */
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $event_array = array();
        
        $this->Database->array_query( $event_array,
        "SELECT ID FROM eZGroupEventCalendar_Event" );
        
        for ( $i=0; $i<count($event_array); $i++ )
        {
            $return_array[$i] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the events thats belongs to another group found in the database.

      The events are returned as an array of eZEvent objects.
    */
    function &getAllByOthers()
    {
        $this->dbInit();
        
        $return_array = array();
        $event_array = array();
        
        $this->Database->array_query( $event_array,
        "SELECT ID FROM eZGroupEventCalendar_Event WHERE ID='$id' AND IsPrivate='0'" );
        
        for ( $i=0; $i<count($event_array); $i++ )
        {
            $return_array[$i] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }


    /*!
      Returns all the events for the given user on the given date.

      The events are returned as an array of eZEvent objects.
    */
    function &getByDate( $date, $group, $showPrivate=false )
    {
        $ret = array();

        if ( ( get_class( $date ) == "ezdate" ) && ( get_class( $group ) == "ezusergroup" ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $event_array = array();

            $month = $date->month();
            $month = eZDateTime::addZero( $month );

            $day = $date->day();
            $day = eZDateTime::addZero( $day );

            $groupID = $group->id();
            $stamp   = $date->year() . $month . $day;

			include_once( "classes/INIFile.php" );
			$ini =& $GLOBALS["GlobalSiteIni"];
			
			if( $ini->read_var( "eZGroupEventCalendarMain", "SubGroupSelect" ) == "enabled" )
			{
				$i = 0;
				$groupTree = $group->getTree( $groupID );

				$selectGroups = "GroupId='$groupID'";

				foreach( $groupTree as $tree )
				{
					$i++;
					$groupID = $tree[0]->id();
					$selectGroups .= " OR GroupID='$groupID'";
				}

				$selectGroups = "( $selectGroups )";
			}
			else
			{
			        // kracker : Add Support for Events in All Groups
			        $selectGroups = "GroupID='$groupID'";
			        // $selectGroups = "GroupID='$groupID' OR GroupID=0";
			}

			if ( $showPrivate == false )
			{
				$this->Database->array_query( $event_array,
				"SELECT ID FROM eZGroupEventCalendar_Event
				 WHERE ( Date LIKE '$stamp%' AND IsPrivate='0' AND $selectGroups ) OR ( Date LIKE '$stamp%' AND IsPrivate='0' AND GroupID='0' ) ORDER BY Date ASC", true );
			}
			else
			{
				$this->Database->array_query( $event_array,
				"SELECT ID FROM eZGroupEventCalendar_Event
				WHERE ( Date LIKE '$stamp%' AND $selectGroups ) OR ( Date LIKE '$stamp%' AND GroupID='0' ) ORDER BY Date ASC" );
			}
			//			print(" SELECT ID FROM eZGroupEventCalendar_Event WHERE ( Date LIKE '$stamp%' AND GroupID='$groupID' ) ORDER BY Date ASC <br />");
			for ( $i=0; $i<count($event_array); $i++ )
			{
				$return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
			}

            $ret =& $return_array;
        }
        return $ret;
    }

    /*!
      Returns all the events for the given group on the given date and type.

      The events are returned as an array of eZEvent objects.
    */
    function &getByGroupType( $date, $group, $type, $showPrivate=false )
    {
        $ret = array();

        if ( ( get_class( $date ) == "ezdate" ) && ( get_class( $group ) == "ezusergroup" ) && ( get_class( $type ) == "ezgroupeventtype" ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $event_array = array();

            $month   = $date->month();
            $month   = eZDateTime::addZero( $month );

            $day     = $date->day();
            $day     = eZDateTime::addZero( $day );

            $groupID = $group->id();

			include_once( "classes/INIFile.php" );
			$ini =& $GLOBALS["GlobalSiteIni"];
			
			if( $ini->read_var( "eZGroupEventCalendarMain", "SubGroupSelect" ) == "enabled" )
			{
				$i = 0;
				$groupTree = $group->getTree( $groupID );
				
				$selectGroups = "GroupId='$groupID'";

				foreach( $groupTree as $tree )
				{
					$i++;
					$groupID = $tree[0]->id();
					$selectGroups .= " OR GroupID='$groupID'";
				}

				$selectGroups = "( $selectGroups )";
			}
			else
			{
			        // kracker : Add Support for Events in All Groups
			        // $selectGroups = "GroupID='$groupID'";
			        $selectGroups = "GroupID='$groupID' OR GroupID=0";			  
			}

			$typeID  = $type->id(); 
            
            $stamp = $date->year() . $month . $day;

            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' AND IsPrivate='0' AND $selectGroups AND EventTypeID='$typeID' ORDER BY Date ASC", true );
            }
            else
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' AND $selectGroups AND EventTypeID='$typeID' ORDER BY Date ASC" );
            }

            for ( $i=0; $i<count($event_array); $i++ )
            {
                $return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }


    /*!
      Returns all the events for all groups on the given date.

      The events are returned as an array of eZEvent objects.
    */
    function &getAllByDate( $date, $showPrivate=false, $groupNoShow=true )
    {
        $ret = array();

        if ( ( get_class( $date ) == "ezdate" ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $event_array = array();

            $month = $date->month();
            $month = eZDateTime::addZero( $month );

            $day = $date->day();
            $day = eZDateTime::addZero( $day );
            
            $stamp = $date->year() . $month . $day;

            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID, GroupID FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' AND IsPrivate='0'  ORDER BY Date ASC", true );
            }
            else
            {
                $this->Database->array_query( $event_array,
                "SELECT ID, GroupID FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' ORDER BY Date ASC" );
            }

			// groups not to include
			if( $groupNoShow == true )
			{
				$noShowGroup = new eZGroupNoShow();
				$noshow_array = $noShowGroup->getAll();
			}

            for ( $i=0; $i<count($event_array); $i++ )
            {
                if( $groupNoShow == true )
				{
					$displayGroup = true;

					foreach( $noshow_array as $noshow )
					{
						if( $noshow->GroupID == $event_array[$i]["GroupID"] )
							$displayGroup = false;
					}
				}
				else
				{
					$displayGroup = true;
				}

				if( $displayGroup == true )
					$return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }

    /*!
      Returns all the events for all groups by a given type on the given date.

      The events are returned as an array of eZEvent objects.
    */
    function &getAllByType( $date, $type, $showPrivate=false, $groupNoShow=true )
    {
        $ret = array();

        if ( ( get_class( $date ) == "ezdate" ) && ( get_class( $type ) == "ezgroupeventtype" ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $event_array = array();

            $month = $date->month();
            $month = eZDateTime::addZero( $month );

            $day = $date->day();
            $day = eZDateTime::addZero( $day );

			$typeID = $type->id();
            
            $stamp = $date->year() . $month . $day;

            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID, GroupID FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' AND IsPrivate='0' AND EventTypeID='$typeID' ORDER BY Date ASC", true );
            }
            else
            {
                $this->Database->array_query( $event_array,
                "SELECT ID, GroupID FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' AND EventTypeID='$typeID' ORDER BY Date ASC" );
            }

			// groups not to include
			if( $groupNoShow == true )
			{
				$noShowGroup = new eZGroupNoShow();
				$noshow_array = $noShowGroup->getAll();
			}

            for ( $i=0; $i<count($event_array); $i++ )
            {
                if( $groupNoShow == true )
				{
					$displayGroup = true;

					foreach( $noshow_array as $noshow )
					{
						if( $noshow->GroupID == $event_array[$i]["GroupID"] )
							$displayGroup = false;
					}
				}
				else
				{
					$displayGroup = true;
				}

				if( $displayGroup == true )
					$return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }


    /*!
      Returns all the events for the given group.

      The events are returned as an array of eZEvent objects.
    */
    function &getByType( $type, $showPrivate=false )
    {
        $ret = array();

        if ( get_class( $type ) == "ezgroupeventtype" )
        {
            $this->dbInit();

            $return_array = array();
            $event_array = array();

            $typeID = $type->id();

            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE IsPrivate='0' AND EventTypeID='$typeID' ORDER BY Date ASC", true );
            }
            else
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE EventTypeID='$typeID' ORDER BY Date ASC" );
            }

            for ( $i=0; $i<count($event_array); $i++ )
            {
                $return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }


    /*!
      Returns all the events for the given type.

      The events are returned as an array of eZEvent objects.
    */
    function &getByGroup( $group, $showPrivate=false )
    {
        $ret = array();

        if ( get_class( $group ) == "ezusergroup" )
        {
            $this->dbInit();

            $return_array = array();
            $event_array = array();

            $groupID = $group->id();

            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE IsPrivate='0' AND GroupID='$groupID' ORDER BY Date ASC", true );
            }
            else
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE GroupID='$groupID' ORDER BY Date ASC" );
            }

            for ( $i=0; $i<count($event_array); $i++ )
            {
                $return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }


    /*!
      Returns the object ID to the event. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }


    /*!
      Returns the name of the event.
    */
    function name( $htmlchars=true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $htmlchars == true )
        {           
            return htmlspecialchars( $this->Name );
        }
        else
        {
            return $this->Name;
        }
    }

    /*!
      Returns the type description.
    */
    function description( $htmlchars=true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $htmlchars == true )
        {           
            return htmlspecialchars( $this->Description );
        }
        else
        {
            return $this->Description;
        }
    }


    /*!
      Returns the type location.
    */
    function location( $htmlchars=true )
      {
        if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

        if ( $htmlchars == true )
	  {
            return htmlspecialchars( $this->Location );
	  }
        else
	  {
            return $this->Location;
	  }
      }

    /*!
      Returns the type url.
    */
    function url( $htmlchars=true )
      {
        if ( $this->State_ == "Dirty" )
          $this->get( $this->ID );

        if ( $htmlchars == true )
          {
            return htmlspecialchars( $this->Url );
          }
        else
          {
            return $this->Url;
          }
      }


    /*!
      Returns the date and time of the event.
    */
    function dateTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $date = new eZDateTime();
       $date->setMySQLTimeStamp( $this->Date );

       return $date;
    }

    /*!
      Returns the duration of the event as an eZTime object.
    */
    function duration()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $time = new eZTime();
       $time->setMySQLTime( $this->Duration );

       return $time;
    }

    /*!
      Returns the start time of the event.
    */
    function &startTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $date = new eZDateTime();
       $date->setMySQLTimeStamp( $this->Date );

       $time = new eZTime();
       $time->setHour( $date->hour() );
       $time->setMinute( $date->minute() );
       $time->setSecond( 0 );

       return $time;
    }

    /*!
      Returns the end time of the event.
    */
    function &stopTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $date = new eZDateTime();
       $date->setMySQLTimeStamp( $this->Date );

       $start = $this->startTime();
       $time = $start->add( $this->duration() );

       return $time;
    }

    /*!
      Returns the priority, can be 0,1,2.
    */
    function priority()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Priority;
    }

    /*!
      Returns the status, can be 0,1,2.
    */
    function status()
      {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

	return $this->Status;
      }

    /*!
      Sets the event type.
    */
    function type( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $type = new eZGroupEventType( $this->EventTypeID );
       return $type;
    }

    /*!
      Sets the event category.
    */
    function category( )
      {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

	$category = new eZGroupEventCategory( $this->EventCategoryID );
	return $category;
      }

    /*!
      Returns true if the event is private.
    */
    function &isPrivate()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->IsPrivate == 0 )
           $ret = false;
       else
           $ret = true;
       
       return $ret;
    }    

    /*!
      Sets the name of the event.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the event.
    */
    function setDescription( $value )
      {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

        $this->Description = $value;
      }

    /*!
      Sets the location of the event.
    */
    function setLocation( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Location = $value;
    }

    /*!
      Sets the url of the event.
    */
    function setUrl( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Url = $value;
    }


    /*!
      Sets the priority of the event.
    */
    function setPriority( $value )
      {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

        $this->Priority = $value;
      }

    /*!
      Sets the status of the event.
    */
    function setStatus( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Status = $value;
    }
    
    /*!
      Sets the event date and time. Takes an eZDateTime object
      as argument.
    */
    function setDateTime( $dateTime )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $dateTime ) == "ezdatetime" )
       {
           $this->Date = $dateTime->mysqlTimeStamp();
       }
    }

    /*!
      Sets the event type.
    */
    function setType( $type )
      {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

	if ( get_class( $type ) == "ezgroupeventtype" )
	  {
	    $this->EventTypeID = $type->id();
	  }
      }

    /*!
      Sets the event category.
    */
    function setCategory( $category )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $category ) == "ezgroupeventcategory" )
       {
           $this->EventCategoryID = $category->id();
       }
    }

    /*!
      Sets the event Group.
    */
    function setGroup( $group )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $group ) == "ezusergroup" )
       {
           $this->GroupID = $group->id();
       }
    }
    
    /*!
      Returns the group ID of the event group.
    */
    function groupID()
    {
        return $this->GroupID;
    }
    
    /*!
     Sets the event to private or not. 
    */
    function setIsPrivate( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsPrivate = 1;
       }
       else
       {
           $this->IsPrivate = 0;
       }
    }    
    
    /*!
      Sets the event duration.
    */
    function setDuration( $time )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $time ) == "eztime" )
       {
           $this->Duration = $time->mysqlTime();
       }
    }
    
    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    /*!
      Returns the comments for the event.
    */
    function forum( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT ForumID FROM
                                            eZGroupEventCalendar_EventForumLink
                                            WHERE EventID='$this->ID'" );
        $forum = false;
        if ( count( $res ) == 1 )
	{
          if ( $as_object )
	    $forum = new eZForum( $res[0][$db->fieldName( "ForumID" )] );
          else
	    $forum = $res[0][$db->fieldName( "ForumID" )];
	}
        else
	  { 
            $forum = new eZForum();
            $forum->setName( $db->escapeString( $this->Name ) );

	    // NOTE:  Comment out the below line if you want to disable anonymous (not logged in) comments!!!
	    $forum->setIsAnonymous(true);

	    // NOTE:  Comment out the below line if you want moderated comments!!!
	    $forum->setIsModerated(false);

	    // NOTE:  Uncomment the below lines if you want moderated comments!!!
	    //$forum->setIsModerated(true);
	    //      $moderatorgroup = new eZUserGroup( 1 ); //should be a variable - administrator groupid
	    //            $forum->setModerator( $moderatorgroup );

            $forum->store();
	    $ini =& INIFile::globalINI();
	    $linkModules = $ini->read_var( "eZForumMain", "LinkModules" );
	    $module_array = explode(',', $linkModules );
	    unset ($linkModules);
	    foreach ( $module_array as $module)
	    {
	      $moduleSubArray = explode( ':', $module );
	      list($module_name, $forum_id) = $moduleSubArray;
	      $linkModules[$module_name] = $forum_id;
	    }
            $category = new eZForumCategory( $linkModules['eZGroupEventCalendar'] );  
	    $category->addForum( $forum );
            $forumID = $forum->id();
            $db->begin( );
            $db->lock( "eZGroupEventCalendar_EventForumLink" );
            $nextID = $db->nextID( "eZGroupEventCalendar_EventForumLink", "ID" );
            $res = $db->query( "INSERT INTO eZGroupEventCalendar_EventForumLink
                                ( ID, EventID, ForumID )
                                VALUES
                                ( '$nextID', '$this->ID', '$forumID' )" );
            $db->unlock();
            if ( $res == false )
	      $db->rollback( );
            else
	      $db->commit();
            if ( $as_object )
	      $forum = new eZForum( $forumID );
            else
	      $forum = $forumID;
	}
      return $forum;
    }

    /*!
      Returns the event which a review is connected to.
    */
    function eventIDFromForum( $ForumID )
    {
        $db =& eZDB::globalDatabase();
        $EventID = 0;
        $db->array_query( $result, "SELECT EventID FROM
                                    eZGroupEventCalendar_EventForumLink
                                    WHERE ForumID='$ForumID' GROUP BY EventID" );
        if( count( $result ) > 0 )
	{
          $EventID = $result[0][$db->fieldName("EventID")];
	}
      return $EventID;
    }

    
    var $ID;
    var $Name;
    var $Description;

    var $URL;
    var $Location;
    var $EventCategoryID;
    
    var $GroupID;
    var $Date;
    var $Duration;
    var $EventTypeID;
    var $EMailNotice;

    var $EventAlarmNotice;

    /// boolean stored as an int
    var $IsPrivate;

    /// boolean stored as an int
    var $IsRecurring;
    var $RecurringDay;
    var $RecurringMonth;
    var $RecurringYear;
    var $RepeatForever;
    var $RepeatTimes;
    var $RepeatUntilDate;

    /// The priority of the event, values can be 0, 1, 2 where 1 is normal.
    var $Priority;
    
    /// The status of the event, values can be 0, 1, 2 where 1 is normal.
    var $Status;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>

