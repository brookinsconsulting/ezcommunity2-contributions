<?php
// 
// $Id: eznewsviewer.php,v 1.2 2000/10/16 13:48:20 pkej-cvs Exp $
//
// Definition of eZNewsViewer class
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
//! eZNewsViewer handles the basic tasks for viewing.
/*!
    This class will mainly keep track of information needed for all
    base classes
    
    It also defines the minimum set of functions needed for this class.
 */

include_once( "eznews/classes/eznewsitem.php" );
include_once( "eznews/classes/eznewsoutput.php" );  

#echo "eZNewsViewer<br />\n";
class eZNewsViewer
{
    /*!
        The constructor, just initializes the private members.
        
        NOTE: Do not use constructor in inheriting objects.
     */
    function eZNewsViewer( &$inItem, &$inIniObject, &$inURLObject )
    {
        $this->IniObject = $inIniObject;
        $this->URLObject = $inURLObject;
        $this->Item = $inItem;
    }



    /*!
        This function renders the page.
        
        NOTE: You will have to overload this in the child object(s),
        and never call this from the child.
        
        \out
            \$outPage   The text string with the page info
        \return
            Returns true if successful.
     */
    function renderPage( &$outPage )
    {
        echo "eZNewsArticleViewer::renderPage()<br />\n";
        die( "This method shouldn't be called. You need to overload it in the classes which inherits from this.");
    }



    /*!
        This function renders the header info.
        
        NOTE: You will have to overload this in the child object(s),
        and never call this from the child.
        
        \out
            \$outHead   The text string with the page header
        \return
            Returns true if successful.
     */
    function renderHead( &$outHead )
    {
        echo "eZNewsArticleViewer::renderPage()<br />\n";
        die( "This method shouldn't be called. You need to overload it in the classes which inherits from this.");
    }



    /*
        This function will return true if the object should check the cache.
        
        \return
            Returns true if we should check the cache.
     */
    function checkCashe()
    {
        return $isCashed;
    }


    // Private members
    
    /// The item which was called.
    var $Item;
    
    /// The global initalization file, usually "site.ini"
    var $IniObject;

    /// The object which decodes the url and url query.
    var $URLObject;
    
    /// This is used to determine if we should look up a cached version of the page.
    var $isCashed = false;
};

?>
