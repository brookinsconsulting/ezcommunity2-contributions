<?
// 
// $Id: ezwishlist.php,v 1.3 2001/01/06 16:21:01 bf Exp $
//
// Definition of eZWishList class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Oct-2000 18:06:52 bf>
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
//!! eZTrade
//! eZWishlist handles a shopping wishlist
/*!

  Example:
  \code

  // Create a new wishlist
  $wishlist = new eZWishlist();
  $wishlist->setUser( $user );

  // Store the wishlist to the database
  $wishlist->store();
  
  // Fetch all wishlist items
  $items = $wishlist->items();
  
  // print contents of the wishlist if it exists
  if  ($items )
  {
      foreach ( $items as $item )
      {
          $product = $item->product();
          print( $product->name() . "<br>");
      }
  }

  \endcode
  \sa eZWishlistItem eZProductCategory eZOption
*/

include_once( "classes/ezdb.php" );

include_once( "eztrade/classes/ezwishlistitem.php" );

class eZWishList
{
    /*!
      Constructs a new eZWishlist object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZWishList( $id="", $fetch=true )
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
      Stores a wishlist to the database.
    */
    function store()
    {
        $this->dbInit();

        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTrade_WishList SET
		                         UserID='$this->UserID'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_WishList SET
		                         UserID='$this->UserID'
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
            $this->Database->array_query( $wishlist_array, "SELECT * FROM eZTrade_WishList WHERE ID='$id'" );
            if ( count( $wishlist_array ) > 1 )
            {
                die( "Error: WishList's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $wishlist_array ) == 1 )
            {
                $this->ID = $wishlist_array[0][ "ID" ];
                $this->UserID = $wishlist_array[0][ "UserID" ];

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
      Returns a eZWishlist object. 
    */
    function getByUser( $user  )
    {
        $this->dbInit();

        $ret = false;
        if ( get_class( $user ) == "ezuser" )
        {        
            $sid = $user->id();
            $this->Database->array_query( $wishlist_array, "SELECT * FROM
                                                    eZTrade_WishList
                                                    WHERE UserID='$sid'" );

            if ( count( $wishlist_array ) == 1 )
            {
                $ret = new eZWishList( $wishlist_array[0]["ID"] );
            }

        }
        return $ret;
    }

    /*!
      Deletes a eZWishlist object from the database.

    */
    function delete()
    {
        $this->dbInit();

        $items = $this->items();

        if  ( $items )
        {
            $i = 0;
            foreach ( $items as $item )
            {
                $item->delete();
            }
        }
            
        $this->Database->query( "DELETE FROM eZTrade_WishList WHERE ID='$this->ID'" );
            
        return true;
    }

    /*!
      Returns the object ID.
    */
    function id( )
    {
        return $this->ID;
    }

    /*!
      Sets the user the wishlist belongs to.

      Return false if the applied argument is not and eZUser object.
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
      Returns all the wishlist items in the wishlist.

      An array of eZWishlistItem objects are retunred if successful, an empty array.
    */
    function items( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = array();
       
       $this->dbInit();

       $this->Database->array_query( $wishlist_array, "SELECT * FROM
                                                    eZTrade_WishListItem
                                                    WHERE WishListID='$this->ID'" );

       if ( count( $wishlist_array ) > 0 )
       {
           $return_array = array();
           foreach ( $wishlist_array as $item )
           {
               $return_array[] = new eZWishlistItem( $item["ID"] );               
           }
           $ret = $return_array;
       }

       return $ret;       
    }

    /*!
      Empties out the wishlist.
    */
    function clear()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $items = $this->items();

       // delete the option values and wishlist items
       foreach ( $items as $item )
       {
           $itemID = $item->id();
           $this->Database->query( "DELETE FROM
                                eZTrade_WishListOptionValue
                                WHERE WishlistItemID='$itemID'" );

           $this->Database->query( "DELETE FROM
                                eZTrade_WishListItem
                                WHERE ID='$itemID'" );
       }

       $this->delete();       
    }
    
    /*!
      \private
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $UserID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
