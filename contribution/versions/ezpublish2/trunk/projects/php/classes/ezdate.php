<?
// 
// $Id: ezdate.php,v 1.4 2000/09/13 09:57:41 bf-cvs Exp $
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
  Example:
  \code
  // Create new eZDate objects.
  $date = new eZDate( 2000, 9, 2 );
  $date2 = new eZDate( );
  $date2->setMySQLDate( "2000-12-02" );

  // print out the current date
  print( $date->year() . " " . $date->month() . " " . $date->day() );
  \endcode  
  \sa eZDateTime eZTime eZLocale
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
            $now = getdate();
            $this->setYear( $now["year"] );
            $this->setMonth( $now["mon"] );
            $this->setDay( $now["mday"] );
            
        }
        else
        {        
            $this->setYear( $year );
            $this->setMonth( $month );
            $this->setDay( $day );
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
