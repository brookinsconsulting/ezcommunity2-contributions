<?
// 
// $Id: eznewsadmin.php,v 1.3 2000/09/28 08:27:14 pkej-cvs Exp $
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
    This class is fire and forget, just make sure that you create an instance
    using new.
    
    Inside the class an URL path is decompositet, and then decoded. Based on the
    second part of the url an include is generated if the part corresponds to
    a similar name in the database. (Via eZNewsItemType.)
    
    If you want to create a class that works with this system you have to first
    create the class file (in eznews/classes/), the corresponding editor (in
    eznews/admin/) and you also have to register the class and create the needed
    database tables in the database (see example 1 in the code section).
    
    We assume that the constructor in your class will take care of all work from
    this point onwards.
    
    Example code:
    \code
    // Example 1:
    // Create the class info in the database.
    INSERT INTO eZNews_ItemType (ID, ParentID, Name, eZClass, eZTable) VALUES ('6', '4', 'NITF',  'eZNewsArticleNITF',  'eZNews_ArticleNITF');

    // Example 2:
    // Include the class.    
    include_once( "eznews/classes/eznewsadmin.php" );
    
    // Create an object.
    new eZNewsAdmin();
    
    // Lean back, the rest is taken care of.
    \endcode
    
    \sa eZNewsItemType
 */
 
class eZNewsAdmin
{
    /*!
        The constructor just creates an array which is passed on for
        further use in the class.
     */
    function eZNewsAdmin()
    {
        global $REQUEST_URI;
        global $QUERY_STRING;
        $url_array = explode( "/", $REQUEST_URI );

        $this->decodeTopLevel( $url_array );
        $title_string = ereg_replace('/$', '', $REQUEST_URI);
        $title_string = ereg_replace('^/', '', $title_string);
        $title_string = ereg_replace('^news', '', $title_string);
        $title_string = ereg_replace('/', ' : ', $title_string);
    }
    
    /*!
       This function decodes the path and includes the correct class editor
       based on that info.
     */
    function decodeTopLevel( $url_array )
    {
        if( !empty( $url_array[2] ) )
        {
            include_once( "eznews/classes/eznewsitemtype.php" );

            $classpartial = strtolower( $url_array[2] );            
            $it = new eZNewsItemType();

            if( $it->exists( $classpartial ) == true )
            {
                $className = $it->eZClass() . "Editor";
                
                $includePath = "eznews/admin/" . strtolower( $className ) . ".php";
               
                include_once( $includePath );
                $ourObject = new $className( $url_array );
            }
            else
            {
                die( "no such type of object balh" );
            }
        }
    }
};

