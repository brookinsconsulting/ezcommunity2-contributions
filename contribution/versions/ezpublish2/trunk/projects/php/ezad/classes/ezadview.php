<?php
// 
// $Id: ezadview.php,v 1.3 2001/01/22 14:42:59 jb Exp $
//
// Definition of eZAdView class
//
// Bård Farstad <bf@ez.no>
// Created on: <26-Nov-2000 12:17:16 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

//!! eZAd
//! eZAdView handles banner ad views.
/*!

  \sa eZAd eZAdCategory eZAdView
*/

/*!TODO

*/

include_once( "classes/ezdb.php" );

class eZAdView
{
    /*!
      Constructs a new eZAdView object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZAdView( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        
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
      Stores a product to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZAd_View SET
		                         VisitorIP='$this->VisitorIP',
		                         AdID='$this->AdID',
                                 UserID='$this->UserID',
                                 ViewPrice='$this->ViewPrice'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZAd_View SET
		                         VisitorIP='$this->VisitorIP',
		                         AdID='$this->AdID',
                                 UserID='$this->UserID',
                                 ViewPrice='$this->ViewPrice'
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
        return true;
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
            $this->Database->array_query( $ad_array, "SELECT * FROM eZAd_Ad WHERE ID='$id'" );
            if ( count( $ad_array ) > 1 )
            {
                die( "Error: Ad's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $ad_array ) == 1 )
            {
                $this->ID =& $ad_array[0][ "ID" ];
                $this->VisitorIP =& $ad_array[0][ "VisitorIP" ];
                $this->UserID =& $ad_array[0][ "UserID" ];
                $this->ViewPrice =& $ad_array[0][ "ViewPrice" ];

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
      Deletes a eZAd object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZAd_View WHERE ID='$this->ID'" );
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
      Returns the view IP.
    */
    function &visitorIP()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->VisitorIP;
    }

    /*!
      Returns the view price.
    */
    function &price()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Price;
    }

    /*!
      Returns the view date and time as a eZDateTime object.
    */
    function &viewTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->ViewTime );
       
       return $dateTime;
    }    
    
    /*!
      Sets the view IP.
    */
    function setVisitorIP( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->VisitorIP = $value;
    }

    /*!
      Sets the view price.
    */
    function setPrice( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ViewPrice = $value;
    }

    /*!
      Sets the ad ID if a valid eZAd object is given as argument.
    */
    function setAd( $ad )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $ad ) == "ezad" )
       {
           $this->AdID = $ad->id();
       }
    }

    /*!
      Sets the user ID if a valid user is given as argument.
    */
    function setUser( $user )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $user ) == "ezuser" )
       {
           $this->UserID = $user->id();
       }
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
    var $AdID;
    var $VisitorIP;
    var $ViewTime;
    var $UserID;
    var $ViewPrice;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}


?>
