<?php
// 
// $Id: ezpricegroup.php,v 1.8 2001/06/28 08:14:54 bf Exp $
//
// Definition of eZPriceGroup class
//
// Jan Borsodi <jb@ez.no>
// Created on: <23-Feb-2001 12:57:01 amos>
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
//! The class eZPriceGroup handles price groups and prices for products and options
/*!

*/

include_once( "classes/ezdb.php" );

class eZPriceGroup
{
    function eZPriceGroup( $id = false )
    {
        if ( is_array( $id ) )
            $this->fill( $id );
        else if ( $id )
            $this->get( $id );
    }

    function get( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->query_single( $array, "SELECT ID, Name, Description, Placement FROM eZTrade_PriceGroup
                                    WHERE ID='$id'" );
        $this->fill( $array );
    }

    function store()
    {
        $query_type = is_numeric( $this->ID ) ? "UPDATE" : "INSERT INTO";
        $id_type = is_numeric( $this->ID ) ? "WHERE" : ",";

        $db =& eZDB::globalDatabase();
        if ( !isset( $this->Placement ) or !is_numeric( $this->Placement ) )
        {
            $db->query_single( $place, "SELECT max( Placement ) AS Placement
                                        FROM eZTrade_PriceGroup" );
            $this->Placement = $place["Placement"] == "NULL" ? 1 : $place["Placement"] + 1;
        }

        $db =& eZDB::globalDatabase();
        $db->query( "$query_type eZTrade_PriceGroup SET
                     Name='$this->Name',
                     Description='$this->Description',
                     Placement='$this->Placement'
                     $id_type ID='$this->ID'" );
        if ( !is_numeric( $this->ID ) )
			$this->ID = $db->insertID();
    }

    function delete( $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZTrade_ProductPriceLink WHERE PriceID='$id'" );
        $db->query( "DELETE FROM eZTrade_GroupPriceLink WHERE PriceID='$id'" );
        $db->query( "DELETE FROM eZTrade_PriceGroup WHERE ID='$id'" );
    }

    function fill( $array )
    {
        $this->ID = $array["ID"];
        $this->Name = $array["Name"];
        $this->Description = $array["Description"];
        $this->Placement = $array["Placement"];
    }

    /*!
      Returns all product groups from db.
    */
    function &getAll( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $array, "SELECT ID FROM eZTrade_PriceGroup
                                   ORDER BY Placement" );
        $ret = array();
        foreach( $array as $row )
        {
            $ret[] = $as_object ? new eZPriceGroup( $row["ID"] ) : $row["ID"];
        }
        return $ret;
    }

    /*!
      Returns all user groups which are connected to a specific price group.
    */
    function &userGroups( $id = false, $as_object = true )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->array_query( $array, "SELECT GroupID AS ID FROM eZTrade_GroupPriceLink
                                   WHERE PriceID='$id'" );
        $ret = array();
        foreach( $array as $row )
        {
            $ret[] = $as_object ? new eZUserGroup( $row["ID"] ) : $row["ID"];
        }
        return $ret;
    }

    /*!
      Adds a user group to a price group.
    */
    function addUserGroup( $group_id, $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        if ( get_class( $group_id ) == "ezusergroup" )
            $group_id = $group_id->id();

        $db =& eZDB::globalDatabase();
        $db->query( "INSERT INTO eZTrade_GroupPriceLink SET
                     GroupID='$group_id',
                     PriceID='$id'" );
    }

    /*!
      Removes all user groups from a price group.
    */
    function removeUserGroups( $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZTrade_GroupPriceLink WHERE PriceID='$id'" );
    }

    /*!
      Returns the price group which the user group is connected to or false if none.
    */
    function correctPriceGroup( $group_id )
    {
        $db =& eZDB::globalDatabase();
        if ( is_array( $group_id ) )
        {
            $first = true;
            foreach( $group_id as $group )
            {
                $first ? $group_text = "GroupID='$group'" : $group_text .= "OR GroupID='$group'";
                $first = false;
            }
            $group_text = "( $group_text )";
        }
        else
        {
            $group_text = "GroupID='$group_id'";
        }
        $db->array_query( $array, "SELECT PriceID
                                   FROM eZTrade_GroupPriceLink, eZTrade_PriceGroup
                                   WHERE PriceID=ID AND $group_text
                                   ORDER BY Placement LIMIT 1" );
        if ( count( $array ) == 1 )
            return $array[0]["PriceID"];
        return false;
    }

    /*!
      Returns the price of a product according to it's price group, option and value type.
    */
    function correctPrice( $productid, $priceid, $optionid = 0, $valueid = 0 )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $array, "SELECT Price FROM eZTrade_ProductPriceLink
                                   WHERE ProductID='$productid' AND PriceID='$priceid'
                                     AND OptionID='$optionid' AND ValueID='$valueid'" );
        if ( count( $array ) == 1 )
            return $array[0]["Price"];
        return false;
    }

    /*!
      Returns all prices for a specific product with a specific option and value id.
    */
    function prices( $productid, $optionid = 0, $valueid = 0 )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $array, "SELECT PriceID, Price FROM eZTrade_ProductPriceLink
                                   WHERE ProductID='$productid' AND OptionID='$optionid'
                                         AND ValueID='$valueid'" );
        return $array;
    }

    /*!
      Adds a price to a product with the specific option and value id.
    */
    function addPrice( $productid, $priceid, $price, $optionid = 0, $valueid = 0 )
    {
        $db =& eZDB::globalDatabase();

        $db->query( "INSERT INTO eZTrade_ProductPriceLink SET
                     PriceID='$priceid', ProductID='$productid',
                     Price='$price', OptionID='$optionid', ValueID='$valueid'" );
    }

    /*!
      Removes all prices from a product with the specific option and value id.
    */
    function removePrices( $productid, $optionid = 0, $valueid = 0 )
    {
        $db =& eZDB::globalDatabase();
        if ( $optionid >= 0 )
            $option_text = "AND OptionID='$optionid'";
        if ( $valueid >= 0 )
            $value_text = "AND ValueID='$valueid'";
        $db->query( "DELETE FROM eZTrade_ProductPriceLink
                     WHERE ProductID='$productid' $option_text $value_text" );
    }

    /*!
      Returns the id of the group.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the group.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the description of the group.
    */
    function description()
    {
        return $this->Description;
    }

    /*!
      Returns the placement of the group in the list.
    */
    function placement()
    {
        return $this->Placement;
    }

    /*!
      Sets the name of the group.
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Sets the description of the group.
    */
    function setDescription( $desc )
    {
        $this->Description = $desc;
    }

    /*!
      Sets the placement of the group in the list.
    */
    function setPlacement( $place )
    {
        $this->Placement = $place;
    }

    var $ID;
    var $Name;
    var $Description;
    var $Placement;
}

?>
