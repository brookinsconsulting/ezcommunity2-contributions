<?
// 
// $Id: eztime.php,v 1.1 2000/09/08 12:14:10 bf-cvs Exp $
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

    }

    /*!
      Returns the hour value in 24
    */
    function hour()
    {
    }

    var $Hour;
    var $Minute;
    var $Second;
}

