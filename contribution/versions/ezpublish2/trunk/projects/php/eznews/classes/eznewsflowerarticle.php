<?
// 
// $Id: eznewsflowerarticle.php,v 1.3 2000/10/12 15:09:18 pkej-cvs Exp $
//
// Definition of eZNewsFlowerArticle class
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
//! eZNewsFlowerArticle handles eZNews Flower articles.
/*!
    A dummy class needed for polymorphic operation...
 */
include_once( "eznews/classes/eznewsarticle.php" );

class eZNewsFlowerArticle extends eZNewsArticle
{
    /*!
        Dummy creator, needed...
     */
    function eZNewsFlowerArticle( $inData = "", $fetch = true )
    {
        #echo "eZNewsFlowerArticle::eZNewsFlowerArticle( \$inData = $inData, \$fetch = $fetch )<br>";
        eZNewsArticle::eZNewsArticle( $inData, $fetch );
    }
};

?>
