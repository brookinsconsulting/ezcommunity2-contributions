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

/* spectrum: I'm sticking these functions in at the top.
They are date difference functions, and extremely useful for 
calculating recurring events,
and why write code other people have already written?
Still, I may go back and either implement these into this class,
or possibly the eZDate class.
*/
/*
    License:  do whatever you want with this code
    
    Disclaimer:  This code works well on my system, but may not on yours.  Use
    with circumspection and trepidation.  If this code blows up your system,
    I recommend vituperation.
*/



/*
    function smoothdate simply takes a year, month, and a day, and
    concatenates them in the form YYYYMMDD
    
    the function date_difference uses this function
*/

function smoothdate ($year, $month, $day)
{
    return sprintf ('%04d', $year) . sprintf ('%02d', $month) . sprintf ('%02d', $day);
}


/*
    function date_difference calculates the difference between two dates in
    years, months, and days.  There is a ColdFusion funtion called, I
    believe, date_diff() which performs a similar function.
    
    It does not make use of 32-bit unix timestamps, so it will work for dates
    outside the range 1970-01-01 through 2038-01-19.  This function works by
    taking the earlier date finding the maximum number of times it can
    increment the years, months, and days (in that order) before reaching
    the second date.  The function does take yeap years into account, but does
    not take into account the 10 days removed from the calendar (specifically
    October 5 through October 14, 1582) by Pope Gregory to fix calendar drift.
    
    As input, it requires two associative arrays of the form:
    array (    'year' => year_value,
            'month' => month_value.
            'day' => day_value)
    
    The first input array is the earlier date, the second the later date.  It
    will check to see that the two dates are well-formed, and that the first
    date is earlier than the second.
    
    If the function can successfully calculate the difference, it will return
    an array of the form:
    array (    'years' => number_of_years_different,
            'months' => number_of_months_different,
            'days' => number_of_days_different)
            
    If the function cannot calculate the difference, it will return FALSE.
    
*/

function dateDiff ($first, $second)
{
    $month_lengths = array (31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $retval = FALSE;

    if (    checkdate($first['month'], $first['day'], $first['year']) &&
            checkdate($second['month'], $second['day'], $second['year'])
        )
    {
        $start = smoothdate ($first['year'], $first['month'], $first['day']);
        $target = smoothdate ($second['year'], $second['month'], $second['day']);
                            
        if ($start <= $target)
        {
            $add_year = 0;
            while (smoothdate ($first['year']+ 1, $first['month'], $first['day']) <= $target)
            {
                $add_year++;
                $first['year']++;
            }
                                                                                                            
            $add_month = 0;
            while (smoothdate ($first['year'], $first['month'] + 1, $first['day']) <= $target)
            {
                $add_month++;
                $first['month']++;
                
                if ($first['month'] > 12)
                {
                    $first['year']++;
                    $first['month'] = 1;
                }
            }
                                                                                                                                                                            
            $add_day = 0;
            while (smoothdate ($first['year'], $first['month'], $first['day'] + 1) <= $target)
            {
                if (($first['year'] % 100 == 0) && ($first['year'] % 400 == 0))
                {
                    $month_lengths[1] = 29;
                }
                else
                {
                    if ($first['year'] % 4 == 0)
                    {
                        $month_lengths[1] = 29;
                    }
                }
                
                $add_day++;
                $first['day']++;
                if ($first['day'] > $month_lengths[$first['month'] - 1])
                {
                    $first['month']++;
                    $first['day'] = 1;
                    
                    if ($first['month'] > 12)
                    {
                        $first['month'] = 1;
                    }
                }
                
            }
                                                                                                                                   // spectrum : adding a line that calculates weeks                                                         
	    $weeks = floor($add_day / 7);
	    // add a day to put us in the correct week (we don't want to use 0 as a week)
	    $weeks++;
            $retval = array ('years' => $add_year, 'months' => $add_month, 'weeks' => $weeks, 'days' => $add_day);
        }
    }
                                                                                                                                                                                                                                                                                
    return $retval;
} 

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
    // spectrum : commenting out all the recurring stuff until the UI is implemented
        $this->dbInit();

		$Name        = addslashes( $this->Name );
		$Description = addslashes( $this->Description );

        if ( !isset( $this->ID ) )
        {
           // $this->Database->query
	   print( "INSERT INTO eZGroupEventCalendar_Event SET
                             Name='$Name',
                             Description='$Description',
                             Date='$this->Date',
                             Duration='$this->Duration',
                             Url='$this->Url',
                             Status='$this->Status',
                             Location='$this->Location',
                             EventAlarmNotice='$this->EventAlarmNotice',
                             EventCategoryID='$this->EventCategoryID',".
			     /*
                             IsRecurring='$this->IsRecurring',
                             RecurExceptions='$this->RecurExceptions',
                             RecurDay='$this->RecurDay',
                             RecurMonthly='$this->RecurMonthly',
                             RecurMonthlyType='$this->RecurMonthlyType',
                             RecurMonthlyTypeInfo='$this->RecurMonthlyTypeInfo',
                             RecurType='$this->RecurType',
                             RecurFreq='$this->RecurFreq',
                             RepeatForever='$this->RepeatForever',
                             RepeatTimes='$this->RepeatTimes',
                             RepeatUntilDate='$this->RepeatUntilDate',
			     RecurFinishDate='$this->RecurFinishDate',                            
                             */
			     "EMailNotice='$this->EMailNotice',
                             IsPrivate='$this->IsPrivate',
                             Priority='$this->Priority',
                             EventTypeID='$this->EventTypeID',
 			     GroupID='$this->GroupID'" );
            
            $this->ID = $this->Database->insertID();
			
        }
        else
        {

	    //$this->Database->query
	    print( "UPDATE eZGroupEventCalendar_Event SET
                             Name='$Name',
                             Description='$Description',
                             Date='$this->Date',
                             Duration='$this->Duration',
                             Url='$this->Url',
                             Status='$this->Status',
                             Location='$this->Location',
                             EventAlarmNotice='$this->EventAlarmNotice',
                             EventCategoryID='$this->EventCategoryID',".
                             /*
                             IsRecurring='$this->IsRecurring',
                             RecurExceptions='$this->RecurExceptions',
                             RecurDay='$this->RecurDay',
                             RecurMonthly='$this->RecurMonthly',
                             RecurMonthlyType='$this->RecurMonthlyType',
                             RecurMonthlyTypeInfo='$this->RecurMonthlyTypeInfo',
                             RecurType='$this->RecurType',
                             RecurFreq='$this->RecurFreq',
                             RepeatForever='$this->RepeatForever',
                             RepeatTimes='$this->RepeatTimes',
                             RepeatUntilDate='$this->RepeatUntilDate'			     
                             RecurFinishDate='$this->RecurFinishDate',                            
                             */   
                             "EMailNotice='$this->EMailNotice',
                             IsPrivate='$this->IsPrivate',
                             Priority='$this->Priority',
                             EventTypeID='$this->EventTypeID',
                             GroupID='$this->GroupID'
                             WHERE ID='$this->ID'" );
        }
        die();
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
                $this->RecurDay =& $event_array[0][ "RecurDay" ];
                $this->RecurFreq =& $event_array[0][ "RecurFreq" ];
                $this->RecurForever =& $event_array[0][ "RecurForever" ];
                $this->RecurMonthly =& $event_array[0][ "RecurMonthly" ];
                $this->RecurMonthlyType =& $event_array[0][ "RecurMonthlyType" ];
                $this->RecurMonthlyTypeInfo =& $event_array[0][ "RecurMonthlyTypeInfo" ];
                $this->RecurType =& $event_array[0] [ "RecurType" ];
                $this->RecurExceptions =& $event_array[0][ "RecurExceptions" ];
                $this->RepeatForever =& $event_array[0][ "RepeatForever" ];
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
			// spectrum: implementing recurring event sorting
				if ($event_array[$i]["IsRecurring"]) {
				
				  /* 
				  there are four things we must check to
				  decide if a recurring event should be included
				  the bool value IsRecurring (above)
				  With any recurring events using RepeatUntilDate or RepeatTimes,
				  we store the last date this event can occur in the column RecurFinishDate.
				  The sql statement checks to see if the current date object is past RecurFinishDate,
				  and also if the current date is in the Exceptions table for each recurring event.
				  Then finally after we have filtered these, we check the RecurType.
				  If the RecurType is day, we check to see if the day matches, 
				  then we check the RecurFreq and calculate from the day started.
				  If the RecurType is week, we check RecurFreq to see if it will be this week, 
				  then we check which days have been selected, and if that day is today, include it. 
				  If the RecurType is month, we check RecurFreq to see if this month is 
				  skipped, then we check RecurMonthType 
				  If RecurMonthType is daily(26th of the month), we simply check to see 
				  if this day matches RecurMonthTypeInfo
				  If RecurMonthType is weekdaynum( Last Tuesday of the month), we first check 
				  to see if it is the day in question in RecurMonthTypeInfo, 
				  then if it is, we check to see if the week matches.
				  If RecurMonthType numdayname(Second Friday of the month), we check to see if 
				  the day matches, then check to see if what number day it is and see if they match.
				  Finally, if RecurType is yearly, check RecurFreq first of course, and then 
				  find out if the month matches, then move to day.
				  */
				  // for each of these cases we will be needing dates to compare. 
				  // The first has already been created at the beginning of this method 
				  // as an object, $date. The second is a set of strings made from the
				  // timestamp $event_array[$i]['Date'];
				  // we will get the year, month, and day substr function
				  $rYear == substr($event_array[$i]['Date'], '0', '4');
				  $rMonth == substr($event_array[$i]['Date'], '4', '6');
				  $rDay == substr($event_array[$i]['Date'], '6', '8');
				  // now lets make the eZDate object
				  $rDate = new eZDate( $rYear, $rMonth, $rDay );
				  $rType = $event_array[$i]["RecurType"];
				  
				  // for readability and ease of use, define extra variables
				  $rFreq = $event_array[$i]['RecurFreq'];
				  // This switch statement switches out the recurType, because
				  // we need to know what type of recurring event it is to
				  // do proper
				  // first we do a check to see if recurFreq is more than one, because if
				  // it is, we will need to run the dateDiff function which will help decide
				  // if we need to do further checks or just remove the key from the array
				  
				    if ($rFreq > 1) {
				     // so let's create a couple arrays that the dateDiff function can read
				     $firstDate = array('year' => $rDate->year(), 'month' => $rDate->month(), 'day' => $rDate->day());
				     $secondDate = array('year' => $date->year(), 'month' => $date->month(), 'day' => $date->day());
				     // This dateDiff function returns an array that holds
				     // the date differences in year, month, and day 
				     $arrDiff = dateDiff($firstDate, $secondDate);
				    }
				  switch($rType) {
				    // if the type is day...
				    case 'day':
				    // if $arrDiff is set, we know that $rFreq is more than 1
				    // and therefore should check to see if $rFreq renders this date
				    // invalid.
				    if (is_array($arrDiff)) {
				    // set $diffDiv var as the difference of days (minus one because
				    // the current day isn't counted) divided by the
				    // recurrance frequency.
				     $diffDiv = ($arrDiff['days'] - 1 ) / $rFreq;
				    // this checks to see if the number has a decimal in it
				    // (ie check to see if it is a whole number)
				    // if this does NOT return false, it is NOT a whole number
				     if ( strstr( $diffDiv, '.' ) ) {
				     // it's not a whole number, so this date shouldn't be considered
				     // let's remove it from the array
				      unset($event_array[$i]);
				     }  
				    }
				    // if the recur freq is 1, then we will be repeating every day
				    // so we leave this event in the array 
				    break;
				    case 'week':
				    // to understand most of this section, see the day section calculations
				    if (is_array($arrDiff)) {
				    // note we don't have to subtract 1 here
				     $diffDiv = $arrDiff['weeks'] / $rFreq;
				     if ( strstr( $diffDiv, '.' ) ) unset($event_array[$i]);
				    }
				    // this next section we will be doing week specific checks
				    // involving the RecurDay string.
				    // this line is fairly simple, it does a case insentive check
				    // to see if the name of the current day is in the RecurDay string
				    if ( !stristr( $RecurDay, $date->dayName() ) ) {
				      // if it's not, unset the array
				      unset($event_array[$i]);
				     }
				    break;
				    case 'month':
				    // to understand most of this section, see the day section calculations
				    if ( is_array( $arrDiff ) ) {
				    $diffDiv = $arrDiff['months'] / $rFreq;
				    if ( strstr( $diffDiv, '.' ) ) unset($event_array[$i]);
				    }
				    // this next section isn't pretty, and it's rather complex
				    // and was generally just a pain in the neck
				    $RecurMonthlyType = $event_array[$i]['RecurMonthlyType'];
				    if ( $RecurMonthlyType == 'daily') {
				    // daily is the easy part. if it is not the same day, unset the array
				     if ( $date->day() != $rDate->day() ) unset($event_array[$i]);
				     // we may want to add some extra logic to get the amount
				     // of days in the month and if a day is over the 28th check
				     // and see if we need to put the event on the closest
				     // day to the end of the month
				    
				    }
				    if ( $RecurMonthlyType == 'numdayname' ) {
				     // in this section we assign the $weekNum var
				     // with first, second, third, fourth, or last
				     if ( $date->day() < 8) { $weekNum = 'first'; }
				     elseif ( $date->day() < 15) { $weekNum = 'second'; } 
				     elseif ( $date->day() < 22) { $weekNum = 'third'; }
				     elseif ( $date->day() < 29) { $weekNum = 'fourth'; }
				     // we only offer options up to the fourth week, otherwise they
				     // must check last, so that's why we don't need to worry
				     // about anything over the 28th in this section
				     else { unset($event_array[$i]); } 
				      switch( $event_array[$i]['RecurMonthlyTypeInfo'] ) {
				      // we see if these match. If they don't, unset array
				      // (there must be a better way of doing this)
				      case 'first':
				      if ($weekNum != 'first') unset($event_array[$i]);
				      break;
				      case 'second':
				      if ($weekNum != 'second') unset($event_array[$i]);
				      break; 
				      case 'third':
				      if ($weekNum != 'third') unset($event_array[$i]);
				      break;
				      case 'fourth':
				      if ($weekNum != 'fourth') unset($event_array[$i]);
				      break;
				      }
				      // finally we have to check
				      if ( $date->dayName() != $rDate->dayName() ) unset($event_array[$i]);
				      }
				   if ( $RecurMonthlyType == 'weekdayname' ) {
				    // the only value this will ever be is 'last'
				    // we have to find the amount of days in this month
				    // then calculate when the last one will happen
				    // the first thing to do is check if the day is over the 21st
				    // otherwise it is definitely not the last
				    if ( $date->day > 21 ) { unset($event_array[$i]); }

				    // finally got it, if the day plus seven is less than or equal to
				    // the number of days in the month, it is not the last day.
				     elseif ( ( $date->day + 7 ) <= 31 ) unset($event_array[$i]); 
				    }
				    break;
				    case 'year':
				    if (is_array($arrDiff)) {
				     $diffDiv = $arrDiff['years'] / $rFreq;
				     if ( strstr( $diffDiv, '.' ) ) unset($event_array[$i]);
				     }
				     // if at this point the month and day match, we are golden
				     if ( ( $date->month() . $date->day() ) != ( $rDate->month() . $rDate->day() ) ) 
				       unset($event_array[$i]);    
				    break;
				  }
				}
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
    var $RecurExceptions;
    var $RecurDay;
    var $RecurMonthly;
    var $RecurMonthlyType;
    var $RecurMonthlyTypeInfo;
    var $RecurType;
    var $RecurFreq;
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

