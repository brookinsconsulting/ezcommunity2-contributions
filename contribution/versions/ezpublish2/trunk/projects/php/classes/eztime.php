<?
// 
// $Id: eztime.php,v 1.4 2001/01/09 17:00:07 bf Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <08-Sep-2000 13:54:17 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
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
            $this->setHour( $now["hours"] );
            $this->setMinute( $now["minutes"] );
            $this->setSecond( $now["seconds"] );
        }
        else
        {
            $this->setHour( $hour );
            $this->setMinute( $minute );
            $this->setSecond( $second ); 
        }
    }

    /*!
      Returns the hour value in 24 hour format.
    */
    function hour()
    {
        return $this->Hour;
    }

    /*!
      Returns the minute value in 0..59.
    */
    function minute()
    {
        return $this->Minute;
    }

    /*!
      Return the second value in 0..59.
    */
    function second()
    {
        return $this->Second;
    }

    /*!
      Sets the hour value.
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
      Adds the value of the given eZTime object to the internal time.

      Returns false if the requested object was not a eZTime object.
    */
    function add( $time )
    {
        $ret = false;
        if ( get_class( $time ) == "eztime" )
        {
            $tmpTime = new eZTime( $this->hour(), $this->minute(), $this->second() );

            $second = ( $this->second() + $time->second() ) % 60;
            
            $minute = ( ( ( $this->minute() + $time->minute() ) +
                 ( ( $this->second() + $time->second() ) / 60 ) ) % 60 );
            
            $hour = $this->hour() + $time->hour() +  ( ( ( $this->minute() + $time->minute() ) +
                 ( ( $this->second() + $time->second() ) / 60 ) ) / 60 );

            $tmpTime->setHour( $hour );
            $tmpTime->setMinute( $minute );
            $tmpTime->setSecond( $second );
            
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
            if ( $time->hour() > $this->hour() )
            {
                $ret = true;
            }
            else if ( $time->hour() == $this->hour() )
            {
                if ( $time->minute() > $this->minute() )
                {
                    $ret = true;
                }
                else if ( $time->minute() == $this->minute() )
                {
                    if ( $equal == false )
                    {
                        if ( $time->second() > $this->minute() )
                        {
                            $ret = true;
                        }
                    }
                    else
                    {                        
                        if ( $time->second() >= $this->minute() )
                        {
                            $ret = true;
                        }
                    }                    
                }
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
            $this->setHour( $valueArray[1] );
            $this->setMinute( $valueArray[2] );
            $this->setSecond( $valueArray[3] );
        }
        else
        {
            print( "<b>Error:</b> eZDate::setMySQLDate() received wrong MySQL date format." );
        }
    }

    /*!
      Returns the MySQL times equivalent to the  time stored
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

    var $Hour;
    var $Minute;
    var $Second;
}

