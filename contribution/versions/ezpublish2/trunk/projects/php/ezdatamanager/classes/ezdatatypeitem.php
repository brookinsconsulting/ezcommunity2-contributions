<?php
// 
// $Id: ezdatatypeitem.php,v 1.4 2002/02/09 15:06:29 br Exp $
//
// Definition of eZDataTypeItem class
//
// Bård Farstad <bf@ez.no>
// Created on: <20-Nov-2001 14:25:57 bf>
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
//! eZDataTypeItem defines data types items for a data type.
/*!
  Example of data types are: FirstName, LastName, Age, WebPageURL.

  \code
  $item = new eZDataTypeItem( );
  $item->setName( "Street" );
  $item->setDataType( $type );
  $item->store();
  \endcode
  
  \sa eZDataType eZDataItem
*/

class eZDataTypeItem
{
    /*!
    */
    function eZDataTypeItem( $id=-1 )
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
        
        $db->begin();

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZDataManager_DataTypeItem" );

            $nextID = $db->nextID( "eZDataManager_DataTypeItem", "ID" );

            $timeStamp =& eZDateTime::timeStamp( true );
            
            $res = $db->query( "INSERT INTO eZDataManager_DataTypeItem
                         ( ID, DataTypeID, ItemType, Name, Created ) VALUES 
                         ( '$nextID',
                           '$this->DataTypeID',
                           '$this->ItemType',
		                   '$name',
                           '$timeStamp' )
                          " );
        
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZDataManager_DataTypeItem SET
		                 Name='$name',
		                 DataTypeID='$this->DataTypeID',
		                 ItemType='$this->ItemType'
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
            $db->array_query( $type_array, "SELECT * FROM eZDataManager_DataTypeItem WHERE ID='$id'" );
            
            if ( count( $type_array ) > 1 )
            {
                die( "Error: Data item types with the same ID was found in the database. " );
            }
            else if ( count( $type_array ) == 1 )
            {
                $this->ID =& $type_array[0][$db->fieldName( "ID" )];
                $this->Name =& $type_array[0][$db->fieldName( "Name" )];
                $this->ItemType =& $type_array[0][$db->fieldName( "ItemType" )];
                $this->DataTypeID =& $type_array[0][$db->fieldName( "DataTypeID" )];
            }
        }
    }

    /*!
      Deletes a data type item from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );
        
        $res = $db->query( "DELETE FROM eZDataManager_DataTypeItem WHERE ID='$this->ID'" );
        $res = $db->query( "DELETE FROM eZDataManager_RelationDefinition WHERE DataTypeItemID='$this->ID'" );

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
      Returns the item type.
    */
    function itemType()
    {
        return $this->ItemType;
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
      Set the ItemType.
    */
    function setItemType( $value )
    {
        $this->ItemType = $value;
    }
    
    /*!
      Sets the data type
    */
    function setDataType( $type )
    {
        if ( get_class( $type ) == "ezdatatype" )
        {
            $this->DataTypeID = $type->id();                        
        }
    }

    /*!
      store the relation to the database.
    */
    function setRelation( $relationID )
    {
        $db =& eZDB::globalDatabase();
        
        $db->array_query( $relation_array, "SELECT ID FROM eZDataManager_RelationDefinition
                                            WHERE DataTypeItemID='$this->ID'" );

        if ( count( $relation_array ) == 0 )
        {
            $db->lock( "eZDataManager_RelationDefinition" );

            $nextID = $db->nextID( "eZDataManager_RelationDefinition", "ID" );
           
            $res = $db->query( "INSERT INTO eZDataManager_RelationDefinition
                         ( ID, DataTypeItemID, DataTypeRelationID ) VALUES 
                         ( '$nextID',
                           '$this->ID',
                           '$relationID' )
                          " );
            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZDataManager_RelationDefinition SET
		                 DataTypeItemID='$this->ID',
		                 DataTypeRelationID='$relationID'
                         WHERE ID='" . $relation_array[0][$db->fieldName( "ID" )]  . "'" );
        }
        if ( $res == false )
            $db->rollback();
        else
            $db->commit();
    }

    function relationID()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $relation_array, "SELECT ID FROM eZDataManager_RelationDefinition
                                            WHERE DataTypeItemID='" . $this->ID . "'" );

        return $relation_array[0][$db->fieldName( "ID" )];
    }
    
    
    /// the ID for the data type item
    var $ID;

    /// the name of the data type item
    var $Name;

    /// the data type for this item. Default 1=text, 2=relation.
    var $ItemType = 1;
    
    /// the ID for the data type this item belongs to
    var $DataTypeID;
}

?>
