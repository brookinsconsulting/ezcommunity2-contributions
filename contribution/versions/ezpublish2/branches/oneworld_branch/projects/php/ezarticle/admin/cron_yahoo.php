<?php
//
// $Id: cron_yahoo.php,v 1.1.2.1 2002/06/03 07:27:13 pkej Exp $
//
// Created on: <28-May-2002 13:16:33 pkej>
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

include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticletool.php" );
include_once( "classes/ezdatetime.php" );

$ini =& INIFile::globalINI();
$now =& new eZDateTime();
$TimeStampNow =& $now->timeStamp();

$SiteURL = $ini->read_var( "site", "SiteURL" );
$SiteTitle = $ini->read_var( "site", "SiteTitle" );

$GrayScaleImageList = $ini->read_var( "eZArticleMain", "GrayScaleImageList" );

$YahooProvider = $ini->read_var( "eZArticleMain", "YahooProvider" );
$YahooCategory = $ini->read_var( "eZArticleMain", "YahooCategory" );
$YahooXMLDirectory = $ini->read_var( "eZArticleMain", "YahooXMLDirectory" );

$article = new eZArticle();
$articles =& $article->getAllNotExportedYahoo( true );

$articleCount = count ( $articles );

foreach( $articles as $article )
{
    $ArticleName = $article->name();
    $ArticleID =  $article->id();
    $ArticleRanking =  round( ( $article->ranking() ) / 2);
    
    if ( $ArticleRanking < 1 )
        $ArticleRanking = 1;
 
    $category = $article->categoryDefinition();
    $CategoryID = $category->id();
    $CategoryName = $category->name();


    $author =  $article->author();
    $AuthorID =  $author->id();
    $AuthorName = $article->authorText();

    $AuthorURL = "http://$SiteURL/article/author/view/$AuthorID/";
    $ArticleURL = "http://$SiteURL/article/articleview/$ArticleID/1/$CategoryID/";
    
    $published = $article->published();
    $PublihsedTimeStamp =& $published->timeStamp();

    $renderer = new eZArticleRenderer( $article );
    $page = $renderer->renderPage();
    $Description = substr( trim( strip_tags( $page[0] ) ), 0, 499 );
    $Story = $page[1];

    $thumbnailImage =& $article->thumbnailImage();

    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;        

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZArticleMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZArticleMain", "ThumbnailImageHeight" ), $convertToGray );
    
        $ThumbnailImagePath = "http://$SiteURL/" . $variation->imagePath();
        $ThumbnailImageCaption = $thumbnailImage->caption();

        $ImageString = "<image>$ThumbnailImagePath</image><caption>$ThumbnailImageCaption</caption>";
    }

$YahooListing = <<<EOD
<?xml version="1.0"?>
<story>
    <provider>$YahooProvider</provider>
    <cat>$YahooCategory</cat>
    <byline>
        By $AuthorName <a href="$AuthorURL">$SiteTitle</a>
    </byline>
    <timestamp>$PublihsedTimeStamp</timestamp>
    <title>$ArticleName</title>
    <title2></title2>
    <originalurl>$ArticleURL<originalurl>
    <rank>$ArticleRanking</rank>
    <summary>$Description</summary>
    <body>$Story</body>
    <linkbox></linkbox>
    <other>
        <storyid>$ArticleID/$CategoryID/$TimeStampNow</storyid>
        <tickers></tickers>
        $ImageString
        <table-info></table-info>
    </other>
</story>
EOD;

    $article->setExportedYahoo( $now );
    $article->store();
    
    $outputFile = "$YahooXMLDirectory/$ArticleID-$CategoryID-$TimeStampNow.xml";

    $fp = eZFile::fopen( $outputFile, "w+");
    fwrite ( $fp, $YahooListing );
    fclose( $fp );
}

?>
