<?
// 
// $Id: ezdatetime.php,v 1.2 2000/09/08 13:00:51 bf-cvs Exp $
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
    
    var $Year;
    var $Month;
    var $Day;
}
?>
