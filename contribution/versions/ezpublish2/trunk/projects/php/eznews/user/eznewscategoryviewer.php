<?php
// 
// $Id: eznewscategoryviewer.php,v 1.1 2000/10/13 20:55:50 pkej-cvs Exp $
//
// Definition of eZNewsCategoryViewer class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <12-Okt-2000 10:59:00 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsCategoryViewer handles the viewing of categories.
/*!
    This class will fetch the correct category and the correct objects.
 */

include_once( "eznews/classes/eznewscategory.php" );
include_once( "eznews/classes/eznewsarticle.php" );
include_once( "eznews/classes/eznewsoutput.php" );  
include_once( "eznews/user/eznewsviewer.php" );  

#echo "eZNewsCategoryViewer<br />\n";
class eZNewsCategoryViewer extends eZNewsViewer
{
    /*!
        This function renders the page.
        
        NOTE: You will have to overload this in the child object(s),
        and never call this from the child. (Unless that's what you
        intend, of course.
        
        \out
            \$outPage   The text string with the page info
        \return
            Returns true if successful.
     */
    function renderPage( &$outPage )
    {
        $value = false;
        
        if( $isCached == true )
        {
            /// fetch the cached version
        }
        else
        {
        }
        
        return $value;
    }



    /*!
        This function renders the header info.
        
        \out
            \$outPage   The text string with the page header
        \return
            Returns true if successful.
     */
    function renderHead( &$outHead )
    {
        $value = false;
        
        if( $isCached == true )
        {
            /// fetch the cached version
        }
        else
        {
        }
        
        
        return $value;
    }



    /*!
        This function will call upon the correct viewer for the children of this
        object.
        
        \out
            \$outChild  The text string with the child text.
        
        \return
            Returns true if successful.
     */
    function doChildren( &$outChild )
    {
        $value = false;
        return $value;
    }


    /*!
        This function will fill in the information about this category.
        
        \return
            Returns true if successful.
     */
    function doThis()
    {
        $value = true;
            
        $this->IniObject->set_var( "this_id", $this->Item->id() );
        $this->IniObject->set_var( "this_name", $this->Item->name() );

        return $value;
    }
};

?>
