<?
// 
// $Id: ezdatetime.php,v 1.7 2000/09/14 14:43:15 ce-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Sep-2000 15:20:51 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//


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
            $this->Hour = $now[ "hours" ];
            $this->Minute = $now[ "minutes" ];
            $this->Second = $now[ "seconds" ];
        }
        else
        {        
            $this->setYear( $year );
            $this->setMonth( $month );
            $this->setDay( $day );
            $this->setHour( $hour );
            $this->setMinute( $minute );
            $this->setSecond( $second );
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
        return $this->Hour;
    }

    /*!
      Returns the minutes.
    */
    function minute()
    {
        return $this->Minute;
    }


    /*!
      Returns the seconds.
    */
    function second()
    {
        return $this->Second;
    }

    
    /*!
      Get the current datetime in MySQL format.
    */
    function mySQLDateTime( )
    {
        if ( $this->Month < "10" )
        {
            $month = ( "0" . $this->Month() );
        }
        else
        {
            $month = $this->Month();
        }
        
        if ( $this->Day < "10" )
        {
            $day = ( "0" . $this->Day() );
        }
        else
        {
            $day = $this->Day();
        }

        if ( $this->Hour < "10" )
        {
            $hour = ( "0" . $this->Hour() );
        }
        else
        {
            $hour = $this->Hour();
        }

        if ( $this->Minute < "10" )
        {
            $minute = ( "0" . $this->Minute() );
        }
        else
        {
            $minute = $this->Minute();
        }

        if ( $this->Second < "10" )
        {
            $second = ( "0" . $this->Second() );
        }
        else
        {
            $second = $this->Second();
        }
        $current = ( $this->Year . "-" .  $month . "-" . $day . " " .  $hour . ":" . $minute . ":" . $second );
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
        $this->Hour = $value;
        setType( $this->Hour, "integer" );
    }

    /*!
      Sets the minute value.
    */
    function setMinute( $value )
    {
        $this->Minute = $value;
        setType( $this->Minute, "integer" );
    }

    /*!
      Sets the second value.
    */
    function setSecond( $value )
    {
        $this->Second = $value;
        setType( $this->Second, "integer" );
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
    
    var $Year;
    var $Month;
    var $Day;
    var $Hour;
    var $Minute;
    var $Second;
}
?>
