<?php
// 
// $Id: eznewscustomerhh.php,v 1.1 2000/09/28 13:07:22 pkej-cvs Exp $
//
// Definition of eZNewsCommand class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <28-Sep-2000 13:03:00 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//


//!! eZNews
//! $openTags handles the XML open tags.
/*
    This is the array of opening tags. All character data between an opening
    and closing tag is stored in the global $parsedXML array, with the name
    of the tag as the key to the data.
 */

$openTags = array
(
    'ezflower' => '<ezflower>',
    'price' => '<price>',
    'name' => '<name>',
    'description' => '<description>',
    'pictureid' => '<pictureid/>',
    'categoryid' => '<categoryid/>',
    'product' => '<product>',
    'id' => '<id/>'
);

//!! eZNews
//! $closeTags handles the XML close tags.
/*
    This is the array of closing tags. All character data between an opening
    and closing tag is stored in the global $parsedXML array, with the name
    of the tag as the key to the data.
 */
$closeTags = array
(
    'ezflower' => '</ezflower>',
    'price' => '</price>',
    'name' => '</name>',
    'description' => '</description>',
    'product' => '</product>'
);

//!! eZNews
//! eZNewsCustomerHH handles parsing for our customer Heistad Hagesenter.
/*!
 */

include_once( "eznews/classes/eznewsxml.php" );
include_once( "eznews/classes/eznewsitem.php" );

class eZNewsCustomerHH extends eZNewsXML
{
    function eZNewsCustomerHH( $URLArray )
    {
        eZNewsXML::eZNewsXML();
        $itemInfo = $URLArray[2];
        
        switch( $itemInfo )
        {
            default:
                $item = new eZNewsItem( $itemInfo, true );
                break;
        }
        
        if( $item->ItemTypeID == 
    }
};

?>
