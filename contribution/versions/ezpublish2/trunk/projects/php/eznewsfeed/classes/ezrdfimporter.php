<?php
// 
// $Id: ezrdfimporter.php,v 1.8 2001/05/05 11:16:04 bf Exp $
//
// Definition of ezrdfimporter class
//
// Bård Farstad <bf@ez.no>
// Created on: <13-Nov-2000 17:07:56 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//!! eZNewsFeed
//! eZRDFImporter handles importing of news bullets from rdf driver sites like freshmeat.net.
/*!
  Example code:
  \sa eZNews eZNewsCategory eZNewsImporter
*/

/*!TODO

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "eznewsfeed/classes/eznews.php" );

class eZRDFImporter
{
    /*!
      Constructor.
    */
    function eZRDFImporter( $site, $login="", $password="" )
    {
        $this->Site = $site;
        $this->Login = $login;
        $this->Password = $password;
    }

    /*!
      Returns the news items as an array.
    */
    function &news( )
    {
        $return_array = array();
        $fp = fopen( $this->Site, "r" );
        $output = fread ( $fp, 10000000 );
        fclose( $fp );

        $doc = qdom_tree( $output );
        if ( count( $doc->children ) > 0 )
        foreach ( $doc->children as $child )
        {
            if ( $child->name == "rss" )
            {
                foreach ( $child->children as $channel )
                {
                    if ( $channel->name == "channel" )
                    {
                        foreach ( $channel->children as $item )
                        {
                            if ( $item->name == "item" )
                            {
                                $title = "";
                                $link = "";
                                $description = "";
                                
                                foreach ( $item->children as $value )
                                {
                                    $contentValue = "";
                                    foreach ( $value->children as $content )
                                    {
                                        if ( $content->name = "#text" )
                                        {
                                            $contentValue = $content->content;
                                        }
                                    }

                                    switch ( $value->name )
                                    {
                                        case "title" :
                                        {
                                            $title = $contentValue;
                                        }
                                        break;

                                        case "link" :
                                        {
                                            $link = $contentValue;
                                        }
                                        break;

                                        case "description" :
                                        {
                                            $description = $contentValue;
                                        }
                                        break;                                        
                                    }
                                }

                                $news = new eZNews(  );
                                $news->setName( addslashes( $title ) );
                                $news->setIntro( addslashes( $description ) );
                                $news->setURL( addslashes($link ) );

                                $dateTime = new eZDateTime( );
                                $news->setOriginalPublishingDate( $dateTime );
                                
                                $return_array[] = $news;
                            }
                        }
                    }
                }
            }
        }
        
        return $return_array;
    }

    var $Site;
    var $Login;
    var $Password;    
}

?>
