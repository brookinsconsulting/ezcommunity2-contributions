<?
// 
// $Id: ezdate.php,v 1.1 2000/09/07 15:44:44 bf-cvs Exp $
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
    //! constructor
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

    //! Returns the year value.
    /*!
      The year is returned in Y2K compatible format.      
    */
    function year()
    {
        return $this->Year;
    }

    //! Returns the month value.
    /*!
      The month value is returned.      
    */
    function month()
    {
        return $this->Month;
    }

    //! Returns the day value.
    /*!
      Returns the day of the month.
    */
    function day()
    {
        return $this->Day;
    }
    
    
    var $Year;
    var $Month;
    var $Day;
}
?>
