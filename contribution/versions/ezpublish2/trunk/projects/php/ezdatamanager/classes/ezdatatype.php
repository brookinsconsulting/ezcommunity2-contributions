<?php
// 
// $Id: ezdatatype.php,v 1.1 2001/11/21 14:49:02 bf Exp $
//
// Definition of eZDataType class
//
// Bård Farstad <bf@ez.no>
// Created on: <20-Nov-2001 14:15:12 bf>
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


//!! eZDataManager
//! eZDataType defines data types used in the data manager.
/*!
  Example of data types are: Address, House, Car etc.

  \code
  $type = new eZDataType( 1);
  $type->setName( "Address" );
  $type->store();
  \endcode
  
  \sa eZDataTypeItem eZDataItem
*/

include_once( "ezdatamanager/classes/ezdatatypeitem.php" );
include_once( "ezdatamanager/classes/ezdataitem.php" );

class eZDataType
{
    /*!
    */
    function eZDataType( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a new data type to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = $db->escapeString( $this->Name );
        
        $db->begin( );

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZDataManager_DataType" );

            $nextID = $db->nextID( "eZDataManager_DataType", "ID" );
            
            $res = $db->query( "INSERT INTO eZDataManager_DataType 
                         ( ID, Name ) VALUES 
                         ( '$nextID',
		                   '$name' )
                          " );
        
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZDataManager_DataType SET
		                 Name='$name'
                         WHERE ID='$this->ID'" );
        }

        $db->unlock();
    
        if ( $res == false )
            $db->rollback();
        else
            $db->commit();
        
        return true;
    }

    /*!
      Fetches the data type from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != -1 )
        {
            $db->array_query( $type_array, "SELECT * FROM eZDataManager_DataType WHERE ID='$id'" );
            
            if ( count( $type_array ) > 1 )
            {
                die( "Error: Data types with the same ID was found in the database. " );
            }
            else if ( count( $type_array ) == 1 )
            {
                $this->ID =& $type_array[0][$db->fieldName( "ID" )];
                $this->Name =& $type_array[0][$db->fieldName( "Name" )];
            }
        }
    }

    /*!
      Retrieves all the data types from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $type_array = array();
        
        $db->array_query( $type_array, "SELECT ID, Name FROM eZDataManager_DataType ORDER BY Name" );
        
        for ( $i = 0; $i < count( $type_array ); $i++ )
        { 
            $return_array[$i] = new eZDataType( $type_array[$i][$db->fieldName( "ID" )], 0 );
        }
        
        return $return_array;
    }

    /*!
      Deletes a data type with all items from the database.
    */
    function delete()
    {
        $item = $this->items();
        
        foreach ( $item as $item )
        {
            $item->delete();
        }
        
        $db =& eZDB::globalDatabase();

        $db->begin( );
        
        $res = $db->query( "DELETE FROM eZDataManager_DataType WHERE ID='$this->ID'" );        

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();        
    }
    
    
    /*!
      Returns the object id
    */
    function id( )
    {
        return $this->ID;
    }
    
    /*!
      Sets the name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Returns the name
    */
    function name( )
    {
        return $this->Name;
    }

    /*!
      Returns all data type items for this type.
    */
    function &typeItems()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $item_array = array();
        
        $db->array_query( $item_array, "SELECT ID, Created FROM eZDataManager_DataTypeItem WHERE DataTypeID='$this->ID' ORDER BY Created" );
        
        for ( $i = 0; $i < count( $item_array ); $i++ )
        { 
            $return_array[$i] = new eZDataTypeItem( $item_array[$i][$db->fieldName( "ID" )], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns all data items for this type.
    */
    function &items()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $item_array = array();
        
        $db->array_query( $item_array, "SELECT ID FROM eZDataManager_Item WHERE DataTypeID='$this->ID' ORDER BY Name" );
        
        for ( $i = 0; $i < count( $item_array ); $i++ )
        { 
            $return_array[$i] = new eZDataItem( $item_array[$i][$db->fieldName( "ID" )], 0 );
        }
        
        return $return_array;
    }

   
    /// the ID for the data type
    var $ID;

    /// the name of the data type
    var $Name;
}

?>
