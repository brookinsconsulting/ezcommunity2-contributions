<?php
// 
// $Id: eznews.php,v 1.15 2001/05/05 11:16:04 bf Exp $
//
// Definition of eZNews class
//
// Bård Farstad <bf@ez.no>
// Created on: <13-Nov-2000 12:51:40 bf>
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
//! eZNews handles news items in the database.
/*!
  Example code:
  \code
  $news = new eZNews( );

  $news->setName( "eZ publish released" );
  $news->setIntro( "Is publish is a ....." );
  $news->setIsPublished( true );
  
  $news->setKeywords( "PHP GPL Object Oriented" );
  $news->setOrigin( "ez.no" );
  $news->setURL( "http://ez.no" );
  
  $dateTime = new eZDateTime( 2000, 11, 13, 14, 0, 15 );
  print( $dateTime->mysqlTimeStamp() );
  
  $news->setOriginalPublishingDate( $dateTime );
  
  $news->store();
  \endcode
  \sa eZNewsCategory
*/

/*!TODO

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZNews
{
    /*!
      Constructs a new eZNews object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZNews( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        // default value
        $this->IsPublished = "false";
        
        if ( $id != "" )
        {

            $this->ID = $id;
            if ( $fetch == true )
            {                
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
                
            }
        }
        else
        {
            $this->State_ = "New";
            
        }
    }

    /*!
      Stores a news to the database. This function will not store two similar news items
      to the database. If a news with the same title, description and url are already
      stored the news is skipped.
    */
    function store()
    {
        $this->dbInit();

        $name = addslashes( $this->Name );
        $intro = addslashes( $this->Intro );
        $url = addslashes( $this->URL );
        $keywords = addslashes( $this->KeyWords );
        $origin = addslashes( $this->Origin );
        
        $ret = false;
        if ( !isset( $this->ID ) )
        {
            // check if the news is already stored.
            $this->Database->array_query( $ret, "SELECT ID FROM eZNewsFeed_News WHERE
		                         Name='$name' AND
                                 Intro='$intro' AND
                                 URL='$url'
                                 " );
            
            if ( count( $ret ) == 0 )
            {
                $this->Database->query( "INSERT INTO eZNewsFeed_News SET
		                         Name='$name',
                                 Intro='$intro',
                                 IsPublished='$this->IsPublished',
                                 PublishingDate=now(),
                                 OriginalPublishingDate='$this->OriginalPublishingDate',
                                 KeyWords='$keywords',
                                 Origin='$this->Origin',
                                 URL='$url'
                                 " );

				$this->ID = $this->Database->insertID();
                $ret = true;
                $this->State_ = "Coherent";
            }
            else
            {
                $ret = false;
            }
        }
        else
        {
            $this->Database->array_query( $res, "SELECT ID FROM eZNewsFeed_News WHERE IsPublished='false' AND ID='$this->ID'" );
            
            if ( ( count( $res ) > 0 ) && ( $this->IsPublished == "true" ) )
            {
                $this->Database->query( "UPDATE eZNewsFeed_News SET
		                         Name='$name',
                                 Intro='$intro',
                                 IsPublished='true',
                                 PublishingDate=now(),
                                 OriginalPublishingDate='$this->OriginalPublishingDate',
                                 KeyWords='$keywords',
                                 Origin='$origin',
                                 URL='$url'
                                 WHERE ID='$this->ID'
                                 " );
            }
            else
            {
                $this->Database->query( "UPDATE eZNewsFeed_News SET
		                         Name='$name',
                                 Intro='$intro',
                                 IsPublished='$this->IsPublished',
                                 PublishingDate=PublishingDate,
                                 OriginalPublishingDate='$this->OriginalPublishingDate',
                                 KeyWords='$keywords',
                                 Origin='$origin',
                                 URL='$url'
                                 WHERE ID='$this->ID'
                                 " );
            }

            $this->State_ = "Coherent";
            $ret = true;
        }
        
        return $ret;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $news_array, "SELECT * FROM eZNewsFeed_News WHERE ID='$id'" );
            if ( count( $news_array ) > 1 )
            {
                die( "Error: News's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $news_array ) == 1 )
            {
                $this->ID =& $news_array[0][ "ID" ];
                $this->Name =& $news_array[0][ "Name" ];
                $this->Intro =& $news_array[0][ "Intro" ];
                $this->IsPublished =& $news_array[0][ "IsPublished" ];
                $this->PublishingDate =& $news_array[0][ "PublishingDate" ];
                $this->OriginalPublishingDate =& $news_array[0][ "OriginalPublishingDate" ];
                $this->Origin =& $news_array[0][ "Origin" ];
                $this->KeyWords =& $news_array[0][ "KeyWords" ];
                $this->URL =& $news_array[0][ "URL" ];

                $this->State_ = "Coherent";
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
      Deletes a eZNews object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZNewsFeed_NewsCategoryLink WHERE NewsID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZNewsFeed_News WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the news name / title.
    */
    function &name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the news contents.
    */
    function &intro()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Intro;
    }

    /*!
      Returns the url to the news.
    */
    function &url()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->URL;
    }

    /*!
      Returns the publishing time of the news.

      The time is returned as a eZDateTime object.
    */
    function &publishingDate()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->PublishingDate );
       
       return $dateTime;
    }

    /*!
      Returns the original publishing time of the news.

      The time is returned as a eZDateTime object.
    */
    function &originalPublishingDate()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->OriginalPublishingDate );
       
       return $dateTime;
    }
    
    /*!
      Returns the origin of the news.
    */
    function origin( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Origin;
    }

    /*!
      Returns the keywords of an news.
    */
    function keywords( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->KeyWords;
    }
    
    /*!
      Returns true if the news is published false if not.
    */
    function isPublished()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->IsPublished == "true" )
       {
           $ret = true;
       }
       return $ret;
    }
      
    
    /*!
      Sets the news name.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Name = $value;
    }

    /*!
      Sets the intro of the news.
    */
    function setIntro( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Intro = $value;
    }

    /*!
      Sets the url to the news.
    */
    function setURL( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->URL = $value;
    }

    /*!
      Sets the origin of the news.
    */
    function setOrigin( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Origin = $value;
    }
    

    /*!
      Sets the keywords of the article.
    */
    function setKeyWords( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->KeyWords = $value;
    }
    
    /*!
      Sets the original publishing date.

      It takes an eZDateTime object as argument.
    */
    function setOriginalPublishingDate( $time)
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $time ) == "ezdatetime" )
       {
           $this->OriginalPublishingDate = $time->mysqlTimeStamp();
       }
    }
    
    /*!
     Sets the news to published or not. 
    */
    function setIsPublished( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsPublished = "true";
       }
       else
       {
           $this->IsPublished = "false";           
       }
    }
    

    /*!
      Returns the categrories an news is assigned to.

      The categories are returned as an array of eZNewsCategory objects.
    */
    function categories()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $ret = array();
       $this->Database->array_query( $category_array, "SELECT * FROM eZNewsFeed_NewsCategoryLink WHERE NewsID='$this->ID'" );

       foreach ( $category_array as $category )
       {
           $ret[] = new eZNewsCategory( $category["CategoryID"] );
       }

       return $ret;
    }
    
    /*!
      Removes every category assignments from the current news.
    */
    function removeFromCategories()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZNewsFeed_NewsCategoryLink WHERE NewsID='$this->ID'" );       
        
    }

    
    /*!
      Returns true if the product is assigned to the category given
      as argument. False if not.
     */
    function existsInCategory( $category )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( get_class( $category ) == "eznewscategory" )
       {
           $this->dbInit();
           $catID = $category->id();

           $this->Database->array_query( $ret_array, "SELECT ID FROM eZNewsFeed_NewsCategoryLink
                                    WHERE NewsID='$this->ID' AND CategoryID='$catID'" );

           if ( count( $ret_array ) == 1 )
           {
               $ret = true;
           }           
       }
       return $ret;
    }

    /*!
      Does a search in the news archive and returns the result as an array
      of eZNews objects.
    */
    function &search( $queryText, $fetchNonPublished=false, $offset=0, $limit=20 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();


       if ( $fetchNonPublished == true )
       {
           $fetchText = "eZNewsFeed_News.IsPublished = 'true' AND";
       }
       else
       {           
           $fetchText = "";
       }

       $return_array = array();
       $news_array = array();

       $this->Database->array_query( $news_array,
                    "SELECT eZNewsFeed_News.ID AS NewsID, eZNewsFeed_News.Name
                    FROM eZNewsFeed_News
                    WHERE 
                    ( 
                    eZNewsFeed_News.Name LIKE '%$queryText%' OR
                    eZNewsFeed_News.Intro LIKE '%$queryText%'
                    )
                    ORDER BY PublishingDate LIMIT $offset,$limit" );
 
       for ( $i=0; $i<count($news_array); $i++ )
       {
           $return_array[$i] = new eZNews( $news_array[$i]["NewsID"], false );
       }
       
       return $return_array;
    }

    /*!
      Does a search in the news archive and returns the number of hits.
    */
    function &searchCount( $queryText, $fetchNonPublished=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       if ( $fetchNonPublished == true )
       {
           $fetchText = "eZNewsFeed_News.IsPublished = 'true' AND";
       }
       else
       {           
           $fetchText = "";
       }

       $return_array = array();
       $news_array = array();

       $this->Database->array_query( $news_array,
                    "SELECT count( eZNewsFeed_News.ID ) AS Count
                    FROM eZNewsFeed_News
                    WHERE 
                    ( 
                    eZNewsFeed_News.Name LIKE '%$queryText%' OR
                    eZNewsFeed_News.Intro LIKE '%$queryText%'
                    )
                    " );
 
       
       return $news_array[0]["Count"];
    }
    
    /*!
      Returns every news in every category sorted by time.
    */
    function newsList( $sortMode=time,
                       $fetchNonPublished=false,
                       $offset=0,
                       $limit=25 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $OrderBy = "eZNewsFeed_News.PublishingDate DESC";
       switch( $sortMode )
       {
           case "alpha" :
           {
               $OrderBy = "eZNewsFeed_News.Name DESC";
           }
           break;
       }

       
       $return_array = array();
       $news_array = array();

       if ( $fetchNonPublished == true )
       {
          $this->Database->array_query( $news_array, "
                    SELECT eZNewsFeed_News.ID AS NewsID, eZNewsFeed_News.Name
                    FROM eZNewsFeed_News
                    GROUP BY eZNewsFeed_News.ID ORDER BY $OrderBy
                    LIMIT $offset,$limit" );
       }
       else
       {
           $this->Database->array_query( $news_array, "
                    SELECT eZNewsFeed_News.ID AS NewsID, eZNewsFeed_News.Name
                    FROM eZNewsFeed_News
                    WHERE 
                    eZNewsFeed_NewsCategoryLink.NewsID = eZNewsFeed_News.ID
                    AND
                    eZNewsFeed_News.IsPublished = 'true'
                    GROUP BY eZNewsFeed_News.ID ORDER BY $OrderBy
                    LIMIT $offset,$limit" );
       }

       for ( $i=0; $i<count($news_array); $i++ )
       {
           $return_array[$i] = new eZNews( $news_array[$i]["NewsID"], false );
       }
       
       return $return_array;
    }
    
    /*!
      \private
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Intro;
    var $PublishingDate;
    var $OriginalPublishingDate;
    var $IsPublished;
    var $KeyWords;
    var $Origin;
    var $URL;
    
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}


?>
