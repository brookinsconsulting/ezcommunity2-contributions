<?
// 
// $Id: eznewsadmin.php,v 1.1 2000/09/22 09:28:42 pkej-cvs Exp $
//
// Definition of eZNewsAdmin class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <20-Sep-2000 13:03:00 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsAdmin handles administrative commands
/*!

    Example URLs:
    \code
    \endcode
 */
 
class eZNewsAdmin
{
    function eZNewsAdmin()
    {
        global $REQUEST_URI;
        $url_array = explode( "/", $REQUEST_URI );

        $this->decodeTopLevel( $url_array );
        $title_string = ereg_replace('/$', '', $REQUEST_URI);
        $title_string = ereg_replace('^/', '', $title_string);
        $title_string = ereg_replace('^news', '', $title_string);
        $title_string = ereg_replace('/', ' : ', $title_string);
    }
    
    function eZNewsAdmin2()
    {
        global $QUERY_STRING;
        global $url_array;
        global $REQUEST_URI;
        echo $REQUEST_URI . "<br>";
        echo $QUERY_STRING . "<br>";
        echo $url_array[1] . "<br>";
        $title_string = ereg_replace('/$', '', $REQUEST_URI);
        $title_string = ereg_replace('^/news/', '', $title_string);
        $title_string = ereg_replace('/', ' : ', $title_string);
    }
    
    function decodeTopLevel( $url_array )
    {
        switch( $url_array[2] )
        {
            case "category":
                include_once( "eznews/admin/ezcategoryeditor.php" );
                new eZCategoryEditor( $url_array );
                break;
            case "article":
                break;
            default:
                break;
        }
    }
};

