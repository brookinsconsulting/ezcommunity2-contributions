<?
// 
// $Id: ezdatetime.php,v 1.16 2001/01/12 14:25:36 gl Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Sep-2000 15:20:51 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

include_once( "classes/eztime.php" );

//!! eZCommon
//! The eZDateTime class provides date and functions.
/*!
  Example:
  \code
  //Create a new eZDateTime object, and set the date and time from a MySQL date time.
  $datetime = new eZDateTime();
  $datetime->setMySQLDateTime( "2000-10-07 16:45:32" );

  // print the date and time in localized format
  print( "Date and time:" . $locale->format( $datetime ) . "<br>" );
  \endcode  
  \sa eZDate eZTime eZLocale
*/

/*!TODO
*/


class eZDateTime
{
    /*!
      Constructs a new eZDateTime object. If the parameters are set the date and
      time is set accordingly. If not the current local time and date are used.
    */
    function eZDateTime( $year=0, $month=0, $day=0, $hour=0, $minute=0, $second=0 )
    {
        if ( ( $year == 0 )  && ( $month == 0 ) && ( $day == 0 ) && ( $hour == 0 ) && ( $minute == 0 ) && ( $second == 0 ) )
        {
            $now = getdate();
            $this->Year = $now[ "year" ];
            $this->Month = $now[ "mon" ];
            $this->Day = $now[ "mday" ];
            $this->Time = new eZTime( $now[ "hours" ], $now[ "minutes" ], $now[ "seconds" ] );
        }
        else
        {        
            $this->setYear( $year );
            $this->setMonth( $month );
            $this->setDay( $day );
            $this->Time = new eZTime( $hour, $minute, $second );
        }
    }

    /*!
      The year is returned in Y2K compatible format.      
    */
    function year()
    {
        return $this->Year;
    }

    /*!
      The month value is returned.      
    */
    function month()
    {
        return $this->Month;
    }

    /*!
      Returns the day of the month.
    */
    function day()
    {
        return $this->Day;
    }

    /*!
      Returns the hours.
    */
    function hour()
    {
        return $this->Time->hour();
    }

    /*!
      Returns the minutes.
    */
    function minute()
    {
        return $this->Time->minute();
    }


    /*!
      Returns the seconds.
    */
    function second()
    {
        return $this->Time->second();
    }

    
    /*!
      Get the current datetime in MySQL format.
    */
    function mySQLDateTime( )
    {
        if ( $this->month() < "10" )
        {
            $month = ( "0" . $this->month() );
        }
        else
        {
            $month = $this->month();
        }
        
        if ( $this->day() < "10" )
        {
            $day = ( "0" . $this->day() );
        }
        else
        {
            $day = $this->day();
        }

        if ( $this->hour() < "10" )
        {
            $hour = ( "0" . $this->hour() );
        }
        else
        {
            $hour = $this->hour();
        }

        if ( $this->minute() < "10" )
        {
            $minute = ( "0" . $this->minute() );
        }
        else
        {
            $minute = $this->minute();
        }

        if ( $this->second() < "10" )
        {
            $second = ( "0" . $this->second() );
        }
        else
        {
            $second = $this->second();
        }
        $current = ( $this->year() . "-" .  $month . "-" . $day . " " .  $hour . ":" . $minute . ":" . $second );
        return $current;
    }

    /*!
      Sets the year value.
    */
    function setYear( $value )
    {
        $this->Year = $value;
        setType( $this->Year, "integer" );
    }

    /*!
      Sets the month value.
    */
    function setMonth( $value )
    {
        $this->Month = $value;
        setType( $this->Month, "integer" );
    }

    /*!
      Sets the day value.
    */
    function setDay( $value )
    {
        $this->Day = $value;
        setType( $this->Day, "integer" );
    }

    /*!
      Sets the Hour value.
    */
    function setHour( $value )
    {
        $this->Time->setHour( $value );
    }

    /*!
      Sets the minute value.
    */
    function setMinute( $value )
    {
        $this->Time->setMinute( $value );
    }

    /*!
      Sets the second value.
    */
    function setSecond( $value )
    {
        $this->Time->setSecond( $value );
    }

    /*!
      Returns the date component of the date time object
      as an eZDate object.
    */
    function &date()
    {
        include_once( "classes/ezdate.php" );

        $date = new eZDate( $this->year(), $this->month(), $this->day() );
        return $date;
    }
    
    /*!
      Returns the time component of the date time object
      as an eZTime object.
    */
    function &time()
    {
        return $Time;
    }
    
    /*!
      Sets the data according to the MySQL date given as paramenter.
      If the paramenter is invalid nothing is set and an error is printed.
    */
    function setMySQLDateTime( $value )
    {
        if ( ereg( "([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})", $value, $valueArray ) )
        {
            $this->setYear( $valueArray[1] );
            $this->setMonth( $valueArray[2] );
            $this->setDay( $valueArray[3] );
            $this->setHour( $valueArray[4] );
            $this->setMinute( $valueArray[5] );
            $this->setSecond( $valueArray[6] );
        }
        else
        {
            print( "<b>Error:</b> eZDateTime::setMySQLDate() received wrong MySQL date format." );
        }
    }

    /*!
      Sets the data according to the MySQL timestamp given as parameter.
    */
    function setMySQLTimeStamp( $value )
    {
        if ( ereg( "([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})", $value, $valueArray ) )
        {
            $this->setYear( $valueArray[1] );
            $this->setMonth( $valueArray[2] );
            $this->setDay( $valueArray[3] );
            $this->setHour( $valueArray[4] );
            $this->setMinute( $valueArray[5] );
            $this->setSecond( $valueArray[6] );
        }
        else
        {
            print( "<b>Error:</b> eZDateTime::setMySQLTimeStamp() received wrong MySQL timestamp format." );
        }
    }

    /*!
      Returns the MySQL timestamp equivalent to the date and time stored
      in the object.
    */
    function mysqlTimeStamp()
    {
        $year = $this->year();
        
        $month = $this->addZero( $this->month() );
        $day = $this->addZero( $this->day() );
        
        $hour = $this->addZero( $this->hour() );
        $minute = $this->addZero( $this->minute() );
        $second = $this->addZero( $this->second() );                

        return $year . $month . $day . $hour . $minute . $second;
    }

    /*!
      \private
      Adds a "0" infront of the value if it's below 10.
    */
    function addZero( $value )
    {
        $ret = $value;
        if ( $ret < 10 )
        {
            $ret = "0". $ret;
        }
        
        return $ret;
    }

    /*!
      Returns the number of days in the current month.
    */
    function daysInMonth( )
    {
        $lastday = mktime( 2, 0, 0, $this->Month + 1, 0, $this->Year );

        
        return strftime(  "%d", $lastday );
    }

    /*!
      Returns the day of week. ( 1..7 )
    */
    function dayOfWeek( )
    {
        $weekday = date ( "w", mktime ( 2, 0, 0, $this->month(), $this->day(), $this->year() ) );

        if ( $weekday == 0 )
            $weekday = 7;
        return $weekday;
    }

    /*!
      Returns the name of the weekday in three letters.
    */
    function dayName( )
    {
        $day = "unknown";

        switch( $this->dayOfWeek() )
        {
            case 1 :
            {
                $day = "mon";
            }
            break;

            case 2 :
            {
                $day = "tue";
            }
            break;

            case 3 :
            {
                $day = "wed";
            }
            break;

            case 4 :
            {
                $day = "thu";
            }
            break;

            case 5 :
            {
                $day = "fri";
            }
            break;

            case 6 :
            {
                $day = "sat";
            }
            break;

            case 7 :
            {
                $day = "sun";
            }
            break;
        }
        return $day;
    }


    /*!
      Returns the name of the month in three letters.
    */
    function monthName( )
    {
        $month = "unknown";

        switch( $this->month() )
        {
            case 1 :
            {
                $month = "jan";
            }
            break;

            case 2 :
            {
                $month = "feb";
            }
            break;

            case 3 :
            {
                $month = "mar";
            }
            break;

            case 4 :
            {
                $month = "apr";
            }
            break;

            case 5 :
            {
                $month = "may";
            }
            break;

            case 6 :
            {
                $month = "jun";
            }
            break;

            case 7 :
            {
                $month = "jul";
            }
            break;

            case 8 :
            {
                $month = "aug";
            }
            break;

            case 9 :
            {
                $month = "sep";
            }
            break;

            case 10 :
            {
                $month = "oct";
            }
            break;

            case 11 :
            {
                $month = "nov";
            }
            break;

            case 12 :
            {
                $month = "dec";
            }
            break;
        }
        return $month;
    }
    
    

    /*!
      Returns true if the current date is valid.
    */
    function isValid()
    {
        return checkdate( $this->month(), $this->day(), $this->year() );
    }


    var $Year;
    var $Month;
    var $Day;

    var $Time;
}
?>
