<?
// 
// $Id: ezdate.php,v 1.2 2000/09/08 12:13:53 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <06-Sep-2000 16:20:20 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//


//!! eZCommon
//! The eZDate class provides date functions.
/*!
  
*/

class eZDate
{
    /*!
      Constructs a new eZDate object. If the parameters are set the date
      is set accordingly. If not the current local time is used.
    */
    function eZDate( $year=0, $month=0, $day=0 )
    {
        if ( ( $year == 0 )  && ( $month == 0 ) && ( $day == 0 ) )
        {
            $now = localTime();
            $this->Year = $now["tm_year"];
            $this->Month = $now["tm_mon"];
            $this->Day = $now["tm_mday"];
            
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
      Sets the day value;
    */
    function setDay( $value )
    {
        $this->Day = $value;
        setType( $this->Day, "integer" );
    }

    /*!
      Sets the date according to the MySQL date given as parameter.      
      If the parameter is invalid nothing is set and an error is printed.
    */
    function setMySQLDate( $value )
    {
        if ( ereg( "([0-9]{4})-([0-9]{2})-([0-9]{2})", $value, $valueArray ) )
        {
            $this->setYear( $valueArray[1] );
            $this->setMonth( $valueArray[2] );
            $this->setDay( $valueArray[3] );
        }
        else
        {
            print( "<b>Error:</b> eZDate::setMySQLDate() received wrong MySQL date format." );
        }
    }
        
    var $Year;
    var $Month;
    var $Day;
}
?>
