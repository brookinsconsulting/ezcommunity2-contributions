<?
// 
// $Id: ezoptionvalue.php,v 1.27 2001/05/31 13:44:45 ce Exp $
//
// Definition of eZOptionValue class
//
// Bård Farstad <bf@ez.no>
// Created on: <12-Sep-2000 15:52:19 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
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
//! Handles product option values.
/*!

  Example:
  \code
  // Create a new eZOptionValue object and store it to the database
  $value = new eZOptionValue();
  $value->setName( "Red" );
  $value->store();

  // Fetch a value from the database, and print out the contents.
  $value->get( 2 );

  print( $value->name() );
    
  \endcode
  \sa eZProductCategory eZOption
*/

include_once( "classes/ezdb.php" );
include_once( "eztrade/classes/ezoption.php" );

class eZOptionValue
{
    /*!
      Constructs a new eZOptionValue object.
    */
    function eZOptionValue( $id=-1, $fetch=true )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZOptionValue object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $price = $this->Price == "" ? "NULL" : "'$this->Price'";

        $GLOBALS["DEBUG"] = true;
        if ( !isset( $this->ID ) )
        {
            $db->array_query( $qry_array,
                              "SELECT Placement FROM eZTrade_OptionValue
                               WHERE OptionID='$this->OptionID' ORDER BY Placement DESC LIMIT 1" );
            $placement = count( $qry_array ) == 0 ? 1 : $qry_array[0]["Placement"] + 1;
            $db->query( "INSERT INTO eZTrade_OptionValue SET
		                         Price=$price,
                                 Placement='$placement',
                                 RemoteID='$this->RemoteID',
                                 OptionID='$this->OptionID'" );

			$this->ID = $db->insertID();
        }
        else
        {
            $db->query( "UPDATE eZTrade_OptionValue SET
		                         Price=$price,
                                 RemoteID='$this->RemoteID',
                                 OptionID='$this->OptionID'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Fetches the option object values from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "-1" )
        {
            $db->array_query( $optionValue_array, "SELECT * FROM eZTrade_OptionValue WHERE ID='$id'" );
            if ( count( $optionValue_array ) > 1 )
            {
                die( "Error: OptionValue's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $optionValue_array ) == 1 )
            {
                $this->ID =& $optionValue_array[0][ "ID" ];
                $this->Price =& $optionValue_array[0][ "Price" ];
                if ( $this->Price == "NULL" )
                    $this->Price = false;
                $this->OptionID =& $optionValue_array[0][ "OptionID" ];
                $this->RemoteID =& $optionValue_array[0][ "RemoteID" ];
            }
        }
    }

    /*!
      Returns every optionValue stored in the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        $return_array = array();
        $optionValue_array = array();
        
        $db->array_query( $optionValue_array, "SELECT ID FROM eZTrade_OptionValue
                                                           ORDER BY Placement ASC" );

        for ( $i=0; $i < count($optionValue_array); $i++ )            
        {
            $return_array[$i] = new eZOptionValue( $optionValue_array[$i]["ID"], 0 );            
        }
        
        return $return_array;
    }

    /*!
      Returns every optionValue connected to a certain Option.

      The values are sorted by name. Returns 0 if no values are found.
    */
    function &getByOption( &$value, $as_object = true )
    {
        if ( get_class( $value ) == "ezoption" )
        {        
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $optionValue_array = array();

            $id = $value->id(); 

            $db->array_query( $optionValue_array,
            "SELECT ID FROM eZTrade_OptionValue WHERE OptionID='$id' ORDER BY Placement ASC" );

            for ( $i=0; $i < count($optionValue_array); $i++ )
            {
                $return_array[$i] = $as_object ? new eZOptionValue( $optionValue_array[$i]["ID"], true ) :
                                                 $optionValue_array[$i]["ID"];
            }
            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Sets the total quantity of the option value.
    */
    function setTotalQuantity( $quantity )
    {
        $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array,
                          "SELECT Q.ID
                           FROM eZTrade_Quantity AS Q, eZTrade_ValueQuantityDict AS VQD
                           WHERE Q.ID=VQD.QuantityID AND ValueID='$id'" );
        $db->query( "DELETE FROM eZTrade_ValueQuantityDict WHERE ValueID='$id'" );
        foreach( $qry_array as $row )
        {
            $q_id = $row["ID"];
            $db->query( "DELETE FROM eZTrade_Quantity WHERE ID='$q_id'" );
        }
        if ( is_bool( $quantity ) and !$quantity )
            return;
        $db->query( "INSERT INTO eZTrade_Quantity VALUES('','$quantity')" );
        $q_id = $db->insertID();
        $db->query( "INSERT INTO eZTrade_ValueQuantityDict VALUES('$id','$q_id')" );
    }

    /*!
      \static
      Returns the total quantity of this value.
    */
    function totalQuantity( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array,
                          "SELECT Q.Quantity
                           FROM eZTrade_Quantity AS Q, eZTrade_ValueQuantityDict AS VQD
                           WHERE Q.ID=VQD.QuantityID AND ValueID='$id'" );
        $quantity = 0;
        if ( count( $qry_array ) > 0 )
        {
            foreach( $qry_array as $row )
            {
                if ( $row["Quantity"] == "NULL" )
                    return false;
                $quantity += $row["Quantity"];
            }
        }
        else
            return false;
        return $quantity;
    }

    /*!
      \static
      Returns true if the option value has some sort of quantity which can be bought.
    */
    function hasQuantity( $require = true, $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $quantity = eZOptionValue::totalQuantity( $id );
        if ( (is_bool($quantity) and !$quantity) or
             !$require or ( $require and $quantity > 0 ) )
            return true;
        return false;
    }

    /*!
      Returns all descriptions connected to this option value.
      It is returned as an array with strings.
    */
    function &descriptions( $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;
        $db->array_query( $qry_array,
                          "SELECT DISTINCT(Value) FROM eZTrade_OptionValueContent
                           WHERE ValueID='$id' ORDER BY Placement ASC" );
        $ret = array();
        foreach( $qry_array as $row )
        {
            $ret[] = $row["Value"];
        }
        return $ret;
    }

    /*!
      Removes all descriptions for this option value.
    */
    function removeDescriptions( $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;
        $db->query( "DELETE FROM eZTrade_OptionValueContent WHERE ValueID='$id'" );
    }

    /*!
      Adds a new description to this option value.
    */
    function addDescription( $description, $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;
        if ( !is_array( $description ) )
            $description = array( $description );
        $db->array_query( $qry_array,
                          "SELECT Placement FROM eZTrade_OptionValueContent
                           WHERE ValueID='$id' ORDER BY Placement DESC LIMIT 1", 0, 1 );
        $placement = count( $qry_array ) == 0 ? 1 : $qry_array[0]["Placement"] + 1;
        foreach( $description as $desc )
        {
            $db->query( "INSERT INTO eZTrade_OptionValueContent
                         SET Value='$desc', ValueID='$id', Placement='$placement'" );
            $placement++;
        }
    }

    /*!
      Deletes a option from the database.
    */
    function delete( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->array_query( $option_array, "DELETE FROM eZTrade_OptionValue
                                                      WHERE ID='$id'" );
        $db->array_query( $option_array, "DELETE FROM eZTrade_ProductPriceLink
                                                      WHERE ValueID='$id'" );
        $db->array_query( $option_array, "DELETE FROM eZTrade_OptionValueContent
                                                      WHERE ValueID='$id'" );
    }

    /*!
      Check if there are a value where RemoteID == $id. Return the value if true.
    */
    function getByRemoteID( $id )
    {
        $db =& eZDB::globalDatabase();
        
        $value = false;
        
        $db->array_query( $res, "SELECT ID FROM
                                            eZTrade_OptionValue
                                            WHERE RemoteID='$id'" );
        if ( count( $res ) == 1 )
        {
            $value = new eZOptionValue( $res[0]["ID"] );
        }
        
        return $value;
    }


    /*!
      Returns the id of the optionvalue.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the price of the option value.
    */
    function price()
    {
        return $this->Price;
    }

    /*!
      Returns the remoteID of the option value.
    */
    function remoteID()
    {
        return $this->RemoteID;
    }
    
    /*!
      Returns the option connected to the value.
    */
    function option()
    {
        return new eZOption( $this->OptionID );
    }
    
    /*!
      Sets the price of the option value.
    */
    function setPrice( $value )
    {
        $this->Price = $value;
    }

    /*!
      Sets the remoteID of the option value.
    */
    function setRemoteID( $value )
    {
        $this->RemoteID = $value;
    }
    
    /*!
      
    */
    function setOptionID( $value )
    {
       $this->OptionID = $value;       
       setType( $this->OptionID, "integer" );
    }
    
    var $ID;
    var $Price;
    var $RemoteID;
    var $OptionID;

}

?>
