<?
// 
// $Id: ezproductattribute.php,v 1.5 2001/04/17 14:26:02 ce Exp $
//
// Definition of eZProductAttribute class
//
// Bård Farstad <bf@ez.no>
// Created on: <20-Dec-2000 13:43:02 bf>
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
//! This class handles different product attributes.
/*!

  \code
  
  $attribute = new eZProductAttribute();
  $attribute->setType( $type );
  $attribute->setName( "Doors" );
  $attribute->store();

  \endcode  
  \sa eZProduct
*/

include_once( "classes/ezdb.php" );
include_once( "eztrade/classes/ezproducttype.php" );

class eZProductAttribute
{
    /*!
      Constructs a new eZProductAttribute object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function eZProductAttribute( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
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
      Stores a eZProductattribute object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {

            $this->Database->array_query( $attribute_array, "SELECT Placement FROM eZTrade_Attribute" );

            if ( count ( $attribute_array ) > 0 )
            {
                $place = max( $attribute_array );
                $place = $place["Placement"];
                $place++;
            }
            
            $this->Database->query( "INSERT INTO eZTrade_Attribute SET
		                         Name='$this->Name',
		                         TypeID='$this->TypeID',
		                         AttributeType='$this->AttributeType',
		                         Placement='$place',
		                         Unit='$this->Unit',
		                         Created=now()" );
        
            $this->ID = mysql_insert_id();
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Attribute SET
		                         Name='$this->Name',
		                         Created=Created,
		                         AttributeType='$this->AttributeType',
		                         Unit='$this->Unit',
		                         TypeID='$this->TypeID' WHERE ID='$this->ID'" );

            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Fetches the product attribute object values from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != -1  )
        {
            $this->Database->array_query( $attribute_array, "SELECT * FROM eZTrade_Attribute WHERE ID='$id'" );
            
            if ( count( $attribute_array ) > 1 )
            {
                die( "Error: Product attribute's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $attribute_array ) == 1 )
            {
                $this->ID =& $attribute_array[0][ "ID" ];
                $this->Name =& $attribute_array[0][ "Name" ];
                $this->TypeID =& $attribute_array[0][ "TypeID" ];
                $this->AttributeType =& $attribute_array[0][ "AttributeType" ];
                $this->Placement =& $attribute_array[0][ "Placement" ];
                $this->Unit =& $attribute_array[0][ "Unit" ];
                
                $this->State_ = "Coherent";                
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Retrieves every option from the database.
    */
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $attribute_array = array();
        
        $this->Database->array_query( $attribute_array, "SELECT ID FROM eZTrade_Attribute ORDER BY Created" );
        
        for ( $i=0; $i<count($attribute_array); $i++ )
        {
            $return_array[$i] = new eZProductAttribute( $attribute_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Deletes a option from the database.
    */
    function delete()
    {
        $this->dbInit();

        $this->Database->query( "DELETE FROM eZTrade_AttributeValue WHERE AttributeID='$this->ID'" );
        
        $this->Database->query( "DELETE FROM eZTrade_Attribute WHERE ID='$this->ID'" );
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       return $this->ID;
    }

    /*!
      Returns the name of the attribute.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
        return $this->Name;
    }

    /*!
      Returns the measuring unit of the attribute.
    */
    function unit()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
        return $this->Unit;
    }

    /*!
      Returns the class of the attribute.

      1 = normal attribute
      2 = header
    */
    function attributeType()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->AttributeType;
    }


    /*!
      Returns the type of the attribute.
    */
    function type()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $type = new eZProductType( $this->TypeID );
 
       return $type;
    }


    /*!
      Sets the name of the attribute.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the measuring unit of the attribute.
    */
    function setUnit( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Unit = $value;
    }

    /*!
      Sets the type of the attribute.
    */
    function setType( $type )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $type ) == "ezproducttype" )
       {
           $this->TypeID = $type->id();
       }
    }

    /*!
      Sets the type of the attribute.

      1 = normal attribute
      2 = header
    */
    function setAttributeType( $attributeType )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $attributeType == 2 )
       {
           $this->Database->query( "DELETE FROM eZTrade_AttributeValue WHERE AttributeID='$this->ID'" );
       }
       
       $this->AttributeType = $attributeType;
       
    }

    /*!
      Sets the attribute value for the given product.
    */
    function setValue( $product, $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $product ) == "ezproduct" )
       {
           $productID = $product->id();

           // check if the attribute is already set, if so update
           $this->Database->array_query( $value_array,
           "SELECT ID FROM eZTrade_AttributeValue WHERE ProductID='$productID' AND AttributeID='$this->ID'" );

           if ( count( $value_array ) > 0 )
           {
               $valueID = $value_array[0]["ID"];
               
               $this->Database->query( "UPDATE eZTrade_AttributeValue SET
                                 Value='$value'
                                 WHERE ID='$valueID'" );
           }
           else
           {
               $this->Database->query( "INSERT INTO eZTrade_AttributeValue SET
		                         ProductID='$productID',
                                 AttributeID='$this->ID',
                                 Value='$value'" );
           }
       }
    }

    /*!
      Returns the attribute value to the given product.
    */
    function value( $product )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = "";
       if ( get_class( $product ) == "ezproduct" )
       {
           $productID = $product->id();

           // check if the attribute is already set, if so update
           $this->Database->array_query( $value_array,
           "SELECT Value FROM eZTrade_AttributeValue WHERE ProductID='$productID'
           AND AttributeID='$this->ID'" );

           if ( count( $value_array ) > 0 )
           {
               $ret = $value_array[0]["Value"];
           }    
       }
       return $ret;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, Placement FROM eZTrade_Attribute
                                  WHERE Placement<'$this->Placement' ORDER BY Placement DESC LIMIT 1" );
        $listorder = $qry["Placement"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZTrade_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZTrade_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, Placement FROM eZTrade_Attribute
                                  WHERE Placement>'$this->Placement' ORDER BY Placement ASC LIMIT 1" );
        $listorder = $qry["Placement"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZTrade_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZTrade_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );
    }

    
    /*!
      Private function.
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
    var $TypeID;
    var $Name;
    var $AttributeType;
    var $Placement;
    var $Unit;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
