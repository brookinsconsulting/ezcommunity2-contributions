<?
// 
// $Id: ezproducttype.php,v 1.4 2001/04/04 12:03:42 ce Exp $
//
// Definition of eZProductType class
//
// Bård Farstad <bf@ez.no>
// Created on: <20-Dec-2000 13:31:12 bf>
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
//! This class handles different product types.
/*!
  A product type is a group of products with the same special attributes. For example
  a product type could be cars, with the defined attributes: horsepower, weight ...

  \code

  \endcode  
  \sa eZProduct
*/

include_once( "classes/ezdb.php" );
include_once( "eztrade/classes/ezproductattribute.php" );

class eZProductType
{
    /*!
      Constructs a new eZProductType object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function eZProductType( $id=-1, $fetch=true )
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
      Stores a eZProducttype object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTrade_Type SET
		                         Name='$this->Name',
                                 Description='$this->Description'" );
        
            $this->ID = mysql_insert_id();
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Type SET
		                         Name='$this->Name',
                                 Description='$this->Description' WHERE ID='$this->ID'" );
            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Fetches the product type object values from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();

        $ret = false;
        if ( $id != -1  )
        {
            $this->Database->array_query( $type_array, "SELECT * FROM eZTrade_Type WHERE ID='$id'" );
            
            if ( count( $type_array ) > 1 )
            {
                die( "Error: Product type's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $type_array ) == 1 )
            {
                $this->ID =& $type_array[0][ "ID" ];
                $this->Name =& $type_array[0][ "Name" ];
                $this->Description =& $type_array[0][ "Description" ];
                
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
      Retrieves every option from the database.
    */
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $type_array = array();
        
        $this->Database->array_query( $type_array, "SELECT ID FROM eZTrade_Type ORDER BY Name" );
        
        for ( $i=0; $i<count($type_array); $i++ )
        {
            $return_array[$i] = new eZProductType( $type_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Deletes a option from the database.
    */
    function delete()
    {
        $this->dbInit();

        // delete all attributes and values
        $attributes = $this->attributes();
        foreach ( $attributes as $attribute )
        {
            $attribute->delete();
        }

        $this->Database->query( "DELETE FROM eZTrade_ProductTypeLink WHERE TypeID='$this->ID'" );
        $this->Database->query( "DELETE FROM eZTrade_Type WHERE ID='$this->ID'" );
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
      Returns the name of the option.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
        return $this->Name;
    }

    /*!
      Returns the option description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }

    /*!
      Sets the name of the option.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the option.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Returns an array of eZProductAttribute objects which
      are associated with the current product type.
    */
    function attributes( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $attribute_array = array();
       
       $this->Database->array_query( $attribute_array, "SELECT ID
                                                      FROM eZTrade_Attribute
                                                      WHERE TypeID='$this->ID' ORDER BY Placement" );

       for ( $i=0; $i<count($attribute_array); $i++ )
       {
           $return_array[$i] = new eZProductAttribute( $attribute_array[$i]["ID"], false );
       }
       
       return $return_array;       
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
    var $Name;
    var $Description;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
