<?php
// 
// $Id: ezsourcesite.php,v 1.6 2001/05/04 16:37:25 descala Exp $
//
// Definition of eZSourceSite class
//
// Bård Farstad <bf@ez.no>
// Created on: <19-Nov-2000 11:26:48 bf>
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
//! eZSourceSite handles news feed source sites' import parameters.
/*!
  \sa eZNewsCategory eZNews
*/

/*!TODO

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZSourceSite
{
    /*!
      Constructs a new eZSourceSite object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZSourceSite( $id="", $fetch=true )
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
      Stores an object to the database. 
    */
    function store()
    {
        $this->dbInit();
        $name = addslashes( $this->Name );
        $login = addslashes( $this->Login );
        $password = addslashes( $this->Password );
        $url = addslashes( $this->URL );
                
        $ret = false;
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZNewsFeed_SourceSite SET
		                         Name='$name',
                                 URL='$url',
                                 Login='$login',
                                 Password='$password',
                                 CategoryID='$this->CategoryID',
                                 IsActive='$this->IsActive',
                                 Decoder='$this->Decoder',
                                 AutoPublish='$this->AutoPublish'
                                 " );

			$this->ID = $this->Database->insertID();

            $this->State_ = "Coherent";
            $ret = true;
            
        }
        else
        {
            $this->Database->query( "UPDATE eZNewsFeed_SourceSite SET
		                         Name='$name',
                                 URL='$url',
                                 Login='$login',
                                 Password='$password',
                                 CategoryID='$this->CategoryID',
                                 Decoder='$this->Decoder',
                                 IsActive='$this->IsActive',
                                 AutoPublish='$this->AutoPublish'
                                 WHERE ID='$this->ID'
                                 " );

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
            $this->Database->array_query( $news_array, "SELECT * FROM eZNewsFeed_SourceSite WHERE ID='$id'" );
            if ( count( $news_array ) > 1 )
            {
                die( "Error: News's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $news_array ) == 1 )
            {
                $this->ID =& $news_array[0][ "ID" ];
                $this->Name =& $news_array[0][ "Name" ];
                $this->URL =& $news_array[0][ "URL" ];
                $this->Login =& $news_array[0][ "Login" ];
                $this->Password =& $news_array[0][ "Password" ];
                $this->CategoryID =& $news_array[0][ "CategoryID" ];
                $this->Decoder =& $news_array[0][ "Decoder" ];
                $this->IsActive =& $news_array[0][ "IsActive" ];
                $this->AutoPublish =& $news_array[0][ "AutoPublish" ];

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
      Deletes a eZSourceSite object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZNewsFeed_SourceSite WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Returns every source site objects in the database.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $source_site_array = array();
        
        $this->Database->array_query( $source_site_array, "SELECT ID FROM eZNewsFeed_SourceSite ORDER BY Name" );
        
        for ( $i=0; $i<count($source_site_array); $i++ )
        {
            $return_array[$i] = new eZSourceSite( $source_site_array[$i]["ID"], 0 );
        }
        
        return $return_array;
        
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the source site name.
    */
    function &name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the url to the source site.
    */
    function &url()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->URL;
    }

    /*!
      Returns the login to the source site.
    */
    function &login()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Login;
    }

    /*!
      Returns the password to the source site.
    */
    function &password()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Password;
    }

    /*!
      Returns the decoder to use on the source site.
    */
    function &decoder()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Decoder;
    }

    /*!
      Returns the category to import the news to.
    */
    function &category()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = new eZNewsCategory( $this->CategoryID );

       return $ret;
    }

    /*!
      Returns the isActive, return true if succsessfull.
    */
    function &isActive()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->IsActive == "true" )
           $ret = true;

       return $ret;
    }
    
    /*!
      Sets the source site name.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Name = $value;
    }

    /*!
      Sets the url of the source site.
    */
    function setURL( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->URL = $value;
    }

    /*!
      Sets the login to the source site.
    */
    function setLogin( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Login = $value;
    }

    /*!
      Sets the password to the news site.
    */
    function setPassword( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Password = $value;
    }

    /*!
      Sets the category to import news to.
    */
    function setCategory( $category )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $category ) == "eznewscategory" )
       {
           $this->CategoryID = $category->id();
       }
    }

    /*!
      Sets the decoder to use.
    */
    function setDecoder( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Decoder = $value;
    }

    /*!
      Sets the IsActive.
    */
    function setIsActive( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->IsActive = $value;

    }

    /*!
      Sets the source to automatically publish new fetched news or not.
    */
    function setAutoPublish( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( $value == true )
        {
            $this->AutoPublish = 1;
        }
        else
        {
            $this->AutoPublish = 0;
        }
    }

    /*!
      Returns true if the news from this site is automatically
      published.
    */
    function autoPublish()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->AutoPublish == 1 )
           $ret = true;
       else
           $ret = false;
       
       return $ret;
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
    var $URL;
    var $Login;
    var $Password;    
    var $CategoryID;
    var $Decoder;
    var $IsActive;
    
    /// bool represented as an int. For automatically publishing of articles.
    var $AutoPublish;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}


?>

 