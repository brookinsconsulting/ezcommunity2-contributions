<?php
// 
// $Id: ezwishlistoptionvalue.php,v 1.2 2000/10/21 17:01:09 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Oct-2000 18:08:22 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZTrade
//! eZWishlistOptionValue handles option values.
/*!
  

*/

include_once( "classes/ezdb.php" );

include_once( "eztrade/classes/ezwishlistitem.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );

class eZWishListOptionValue
{
    /*!
      Constructs a new eZWishlistOptionValue object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZWishListOptionValue( $id="", $fetch=true )
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
            $this->Count = 1;
        }
    }

    /*!
      Stores a wishlist option value to the database.
    */
    function store()
    {
        $this->dbInit();
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTrade_WishListOptionValue SET
		                         WishListItemID='$this->WishListItemID',
		                         OptionID='$this->OptionID',
		                         OptionValueID='$this->OptionValueID'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_WishListOptionValue SET
		                         WishListItemID='$this->WishListItemID',
		                         OptionID='$this->OptionID',
		                         OptionValueID='$this->OptionValueID'
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
            $this->Database->array_query( $wishlist_array, "SELECT * FROM eZTrade_WishListOptionValue WHERE ID='$id'" );
            if ( count( $wishlist_array ) > 1 )
            {
                die( "Error: Wishlist's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $wishlist_array ) == 1 )
            {
                $this->ID =& $wishlist_array[0][ "ID" ];
                $this->WishListItemID =& $wishlist_array[0][ "WishListItemID" ];
                $this->OptionID =& $wishlist_array[0][ "OptionID" ];
                $this->OptionValueID =& $wishlist_array[0][ "OptionValueID" ];

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
      Returns the object id.
    */
    function id( )
    {
        return $this->ID;        
    }

    /*!
      Returns the wishlist item object.
    */
    function &wishlistItem()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return new eZWishListItem( $this->WishListItemID );
    }

    /*!
      Returns the option object.
    */
    function &option()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return new eZOption( $this->OptionID );
    }

    /*!
      Returns the option value object.
    */
    function &optionValue()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return new eZOptionValue( $this->OptionValueID );
    }
    
    /*!
      Sets the wishlist item object id.
    */
    function setWishListItem( &$wishlistItem )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $wishlistItem ) == "ezwishlistitem" )
       {
           $this->WishListItemID = $wishlistItem->id();
       }
    }

    /*!
      Sets the option object id.
    */
    function setOption( &$option )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $option ) == "ezoption" )
       {
           $this->OptionID = $option->id();
       }
    }

    /*!
      Sets the option value object id.
    */
    function setOptionValue( &$optionValue )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $optionValue ) == "ezoptionvalue" )
       {
           $this->OptionValueID = $optionValue->id();
       }
    }
    
    /*!
      Private function.
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
    var $WishListItemID;
    var $OptionID;
    var $OptionValueID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
