<?
// 
// $Id: eztime.php,v 1.3 2000/11/17 13:43:17 ce-cvs Exp $
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

    var $Hour;
    var $Minute;
    var $Second;
}

