<?
// 
// $Id: ezlocale.php,v 1.1 2000/09/07 15:44:44 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Sep-2000 14:33:48 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//


//!! eZCommon
//! The eZLocale class provides locale functions.
/*!
  eZLocale handles locale information and formats time, date, and currency
  information to the locale format.
  \break
  Example of usage:
*/

class eZLocale
{
    //! constructor
    /*!
      Constructs a new eZLocale object. If an ISO code is given as
      an argument the regional file for that language is used. Otherwise
      the default regional settings are used.
    */
    function eZLocale( $iso="" )
    {
        $item = get_included_files();
        print( "::::::::::::::." . $item[0] .  "<br>" );
        print( "::::::::::::::." . $item[1] .  "<br>" );
        
        print( "---------->" . $GLOBALS[SCRIPT_FILENAME] . "<br>" );
    }

    //! Formats an object to locale format.
    /*!
      Returns a nicely formatted string. This function automatically finds
      the appropriate format to use based on locale information and the type
      of object passed as an argument. 
    */
    function format( $obj )
    {
        $returnString = "<b>Locale error</b>: object or type not supported.";
        print( get_class( $obj ) );
        switch ( get_class( $obj ) )
        {
            case "ezdate" :
            {
                $returnString = $obj->day() . "." . $obj->month() . "." . $obj->year();
                break;
            }
            case "eztime" :
            {
                
                break;
            }
            case "ezdatetime" :
            {
                
                break;
            }
            case "ezcurrency" :
            {

                break;
            }
        }
        return $returnString;
    }
}


?>
