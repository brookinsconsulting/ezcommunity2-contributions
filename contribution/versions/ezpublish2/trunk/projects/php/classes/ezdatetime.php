<?
// 
// $Id: ezdatetime.php,v 1.4 2000/09/13 09:48:49 ce-cvs Exp $
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
            $this->Year = $year;
            $this->Month = $month;
            $this->Day = $day;
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
