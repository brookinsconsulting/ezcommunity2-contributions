<?
// 
// $Id: eznewsflowercategory.php,v 1.2 2000/10/12 15:09:18 pkej-cvs Exp $
//
// Definition of eZNewsFlowerCategory class
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
//! eZNewsFlowerCategory handles eZNews Flower categories.
/*!
    A dummy class needed for polymorphic operation...
 */
include_once( "eznews/classes/eznewscategory.php" );

class eZNewsFlowerCategory extends eZNewsCategory
{
    /*!
        Constructor. Dummy needed.
     */
    function eZNewsFlowerCategory( $inData = "", $fetch = true )
    {
        #echo "eZNewsFlowerCategory::eZNewsFlowerCategory( \$inData = $inData, \$fetch = $fetch )<br>\n";

        eZNewsCategory::eZNewsCategory( $inData, $fetch );
    }
};
?>
