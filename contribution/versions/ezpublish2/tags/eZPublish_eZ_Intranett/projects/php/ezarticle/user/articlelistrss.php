<?php
// 
// $Id: articlelistrss.php,v 1.6 2001/07/19 12:19:21 jakobn Exp $
//
// Created on: <11-Dec-2000 09:44:51 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "classes/INIFile.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );

$ini =& INIFile::globalINI();
$Title = $ini->read_var( "eZArticleRSS", "Title" );
$Link = $ini->read_var( "eZArticleRSS", "Link" );
$Description = $ini->read_var( "eZArticleRSS", "Description" );
$Language = $ini->read_var( "eZArticleRSS", "Language" );

// clear what might be in the output buffer
ob_end_clean();

// xml header
print( "<?xml version=\"1.0\"?>\n\n" );

// rss header
print( "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\" \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\n\n" );

print( "<rss version=\"0.91\">\n" );

print( "<channel>\n" );


print( "<title></title>\n" );
print( "<link>$Link</link>\n" );
print( "<description>$Description</description>\n" );
print( "<language>$Language</language>\n" );

$article = new eZArticle();
$articleList = $article->articles( $SortMode, false, 0, 10 );

$locale = new eZLocale( $Language );
foreach ( $articleList as $article )
{
    $articleID = $article->id();
    $headerInfo = ( getallheaders() );
    
    print( "<item>\n" );
    print( "<title>" . substr( $article->name(), 0, 99 ) . "</title>\n" );
    print( "<link>http://" . $headerInfo["Host"] . "/article/view/$articleID/</link>\n" );
    
//      $published = $article->published();
//      print( $locale->format( $published ) );

    $renderer = new eZArticleRenderer( $article );
    $description = $renderer->renderIntro();
    
    print( "<description>" . substr( trim( strip_tags( $description ) ), 0, 499 ). "</description>\n" );    

    print( "</item>\n" );
}

print( "\n</channel>\n" );
print( "\n</rss>\n" );

exit();

?>

