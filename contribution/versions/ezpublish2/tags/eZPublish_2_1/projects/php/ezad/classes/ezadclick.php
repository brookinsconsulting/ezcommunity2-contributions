<?php
// 
// $Id: ezadclick.php,v 1.6 2001/05/09 15:08:19 bf Exp $
//
// Definition of eZAdClick class
//
// Bård Farstad <bf@ez.no>
// Created on: <25-Nov-2000 16:30:05 bf>
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
//! eZAdClick handles banner ad clicks.
/*!

  \sa eZAdCategory  eZAd
*/

/*!TODO

*/

include_once( "classes/ezdb.php" );


class eZAdClick
{
    /*!
      Constructs a new eZAdClick object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZAdClick( $id="", $fetch=true )
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
            $this->Database->query( "INSERT INTO eZAd_Click SET
		                         PageViewID='$this->PageViewID',
		                         AdID='$this->AdID',
                                 ClickPrice='$this->ClickPrice'
                                 " );

			$this->ID = $this->Database->insertID();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZAd_Click SET
		                         PageViewID='$this->PageViewID',
		                         AdID='$this->AdID',
                                 ClickPrice='$this->ClickPrice'
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
                $this->PageViewID =& $ad_array[0][ "PageViewID" ];
                $this->ClickPrice =& $ad_array[0][ "ClickPrice" ];

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
            $this->Database->query( "DELETE FROM eZAd_Click WHERE ID='$this->ID'" );
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
      Returns the click price.
    */
    function &price()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Price;
    }

    /*!
      Returns the click date and time as a eZDateTime object.
    */
    function &clickTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->ClickTime );
       
       return $dateTime;
    }    
    
    /*!
      Sets the click IP.
    */
    function setPageViewID( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->PageViewID = $value;
    }

    /*!
      Sets the click price.
    */
    function setPrice( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ClickPrice = $value;
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
    var $PageViewID;
    var $ClickTime;
    var $ViewPrice;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
