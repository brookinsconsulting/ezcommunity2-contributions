<?php
// 
// $Id: eznewsarticleviewer.php,v 1.1 2000/10/13 20:55:50 pkej-cvs Exp $
//
// Definition of eZNewsArticleViewer class
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
//! eZNewsArticleViewer handles the viewing of articles.
/*!
    This class will fetch the correct category and the correct objects.
 */

include_once( "eznews/classes/eznewsarticle.php" );
include_once( "eznews/classes/eznewsoutput.php" );  
include_once( "eznews/user/eznewsviewer.php" );  

#echo "eZNewsArticleViewer<br />\n";
class eZNewsArticleViewer extends eZNewsViewer
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
