<?
// 
// $Id: eztime.php,v 1.8 2001/02/19 12:26:27 gl Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <08-Sep-2000 13:54:17 bf>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//


//!! eZCommon
//! The eZTime class provides time functions.
/*!
  eZTime handles 24 hour time format.

  \sa eZLocale eZTimeDate eZDate  
*/

class eZTime
{
    /*!
      Constructs a new eZTime object.
    */
    function eZTime( $hour=0, $minute=0, $second=0 )
    {
        if ( ( $hour == 0 )  && ( $minute == 0 ) && ( $second == 0 ) )
        {
            $now = getdate();
            $this->setSecondsElapsedHMS( $now["hours"], $now["minutes"], $now["seconds"] );
        }
        else
        {
            $this->setSecondsElapsedHMS( $hour, $minute, $second ); 
        }
    }

    /*!
      Returns the hour value in 24 hour format.
    */
    function hour()
    {
        $value = $this->SecondsElapsed;

        $second = $value % 60;
        $value = ( $value - $second ) / 60;

        $minute = $value % 60;
        $value = ( $value - $minute ) / 60;

        $hour = $value % 24;

        return $hour;
    }

    /*!
      Returns the minute value in 0..59.
    */
    function minute()
    {
        $value = $this->SecondsElapsed;

        $second = $value % 60;
        $value = ( $value - $second ) / 60;

        $minute = $value % 60;

        return $minute;
    }

    /*!
      Return the second value in 0..59.
    */
    function second()
    {
        $value = $this->SecondsElapsed;

        $second = $value % 60;

        return $second;
    }

    /*!
      Returns the number of seconds elapsed since midnight.
    */
    function secondsElapsed()
    {
        return $this->SecondsElapsed;
    }

    /*!
      Sets the hour value.
    */
    function setHour( $value )
    {
        $this->SecondsElapsed = ( ( $value * 3600 ) + ( $this->minute() * 60 ) + $this->second() );
        setType( $this->SecondsElapsed, "integer" );
    }

    /*!
      Sets the minute value.
    */
    function setMinute( $value )
    {
        $this->SecondsElapsed = ( ( $this->hour() * 3600 ) + ( $value * 60 ) + $this->second() );
        setType( $this->SecondsElapsed, "integer" );
    }
    
    /*!
      Sets the second value.
    */
    function setSecond( $value )
    {
        $this->SecondsElapsed = ( ( $this->hour() * 3600 ) + ( $this->minute() * 60 ) + $value );
        setType( $this->SecondsElapsed, "integer" );
    }

    /*!
      Sets the number of seconds elapsed since midnight.
    */
    function setSecondsElapsed( $value )
    {
        $this->SecondsElapsed = $value;
        setType( $this->SecondsElapsed, "integer" );
    }

    /*!
      Sets the number of seconds elapsed since midnight.
    */
    function setSecondsElapsedHMS( $hour, $minute, $second )
    {
        $this->SecondsElapsed = ( ( $hour * 3600 ) + ( $minute * 60 ) + $second );
        setType( $this->SecondsElapsed, "integer" );
    }

    /*!
      Adds the value of the given eZTime object to the internal time,
      and returns the result. Does not change the internal time.

      Returns false if the requested object was not an eZTime object.
    */
    function add( $time )
    {
        $ret = false;
        if ( get_class( $time ) == "eztime" )
        {
            $tmpTime = new eZTime( $this->hour(), $this->minute(), $this->second() );

            $secondsElapsed = ( $this->SecondsElapsed + $time->secondsElapsed() ) % 86400;
            $tmpTime->setSecondsElapsed( $secondsElapsed );

            $ret = $tmpTime;
        }
        
        return $ret;
    }

    /*!
      Subtracts the value of the given eZTime object from the internal time,
      and returns the result. Does not change the internal time.

      Returns false if the requested object was not an eZTime object.
      If the given time is greater than the internal time, the subtraction will
      wrap around midnight.
    */
    function subtract( $time )
    {
        $ret = false;
        if ( get_class( $time ) == "eztime" )
        {
            $tmpTime = new eZTime( $this->hour(), $this->minute(), $this->second() );

            $secondsElapsed = ( ( 86400 + $this->SecondsElapsed ) - $time->secondsElapsed() ) % 86400;
            $tmpTime->setSecondsElapsed( $secondsElapsed );

            $ret = $tmpTime;
        }
        
        return $ret;
    }

    /*!
      Returns true if the eZTime object given as argument is
      greater than the internal values.

      If $equal is set to true then true is returned if the time
      is greater than or equal.

      Returns false is the object is not a eZTime object.      
    */
    function isGreater( &$time, $equal=false )
    {
        $ret = false;

        if ( get_class( $time ) == "eztime" )
        {
            if ( $equal == false )
            {
                if ( $time->secondsElapsed() > $this->SecondsElapsed )
                {
                    $ret = true;
                }
            }
            else
            {
                if ( $time->secondsElapsed() >= $this->SecondsElapsed )
                {
                    $ret = true;
                }
            }
        }
        return $ret;
    }

    /*!
      Returns true if the eZTime object given as argument is
      equals to the internal values.

      Returns false is the object is not a eZTime object.      
    */
    function equals( &$time )
    {
        $ret = false;

        if ( get_class( $time ) == "eztime" )
        {
            if ( $time->secondsElapsed() == $this->SecondsElapsed )
            {
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Sets the time according to the MySQL time given as a
      parameter. If the value is invalid an error message is
      printed and no values are set.
    */
    function setMySQLTime( $value )
    {
        if ( ereg( "([0-9]{2}):([0-9]{2}):([0-9]{2})", $value, $valueArray ) )
        {
            $this->setSecondsElapsedHMS( $valueArray[1], $valueArray[2], $valueArray[3] );
        }
        else
        {
            print( "<b>Error:</b> eZTime::setMySQLTime() received wrong MySQL time format." );
        }
    }

    /*!
      Returns the MySQL times equivalent to the time stored
      in the object.
    */
    function mysqlTime()
    {
        $hour = $this->addZero( $this->hour() );
        $minute = $this->addZero( $this->minute() );
        $second = $this->addZero( $this->second() );

        return $hour . ":" . $minute . ":" . $second;
    }

    /*!
      \private
      Adds a "0" in front of the value if it's below 10.
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

    var $SecondsElapsed;
}

