<?php
// 
// $Id: ezdataitem.php,v 1.8 2002/02/21 14:50:52 jhe Exp $
//
// Definition of eZDataItem class
//
// Bård Farstad <bf@ez.no>
// Created on: <20-Nov-2001 18:12:34 bf>
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
//! eZDataItem
/*!
  \sa eZDataTypeItem eZDataItem
*/

include_once( "ezdatamanager/classes/ezdatatypeitem.php" );

class eZDataItem
{
    /*!
    */
    function eZDataItem( $id=-1 )
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
            $db->lock( "eZDataManager_Item" );

            $nextID = $db->nextID( "eZDataManager_Item", "ID" );
            
            $res = $db->query( "INSERT INTO eZDataManager_Item
                         ( ID, DataTypeID, OwnerGroupID, Name ) VALUES 
                         ( '$nextID',
                           '$this->DataTypeID',
                           '$this->OwnerGroupID',
		                   '$name',
                           '$this->Image')" );
        
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZDataManager_Item SET
		                 Name='$name',
                         DataTypeID='$this->DataTypeID',
                         OwnerGroupID='$this->OwnerGroupID',
                         Image='$this->Image'
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
            $db->array_query( $type_array, "SELECT * FROM eZDataManager_Item WHERE ID='$id'" );
            
            if ( count( $type_array ) > 1 )
            {
                die( "Error: Data items with the same ID was found in the database. " );
            }
            else if ( count( $type_array ) == 1 )
            {
                $this->ID =& $type_array[0][$db->fieldName( "ID" )];
                $this->Name =& $type_array[0][$db->fieldName( "Name" )];
                $this->DataTypeID =& $type_array[0][$db->fieldName( "DataTypeID" )];
                $this->OwnerGroupID =& $type_array[0][$db->fieldName( "OwnerGroupID" )];
                $this->Image =& $type_array[0][$db->fieldName( "Image" )];
            }
        }
    }

    /*!
      \static
      Returns all data items with the given ID, if ID is false all items are returned.
    */
    function &getAll( $id = false )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $typeItemArray = array();

        if ( is_numeric( $id ) )
        {
            $db->array_query( $typeItemArray, "SELECT ID
                                          FROM eZDataManager_Item WHERE DataTypeID='$id'
                                          " );
        }
        else
        {
            $db->array_query( $typeItemArray, "SELECT ID
                                          FROM eZDataManager_Item
                                          " );
        }
        

        for ( $i = 0; $i < count( $typeItemArray ); $i++ )
        {
            $returnArray[$i] = new eZDataItem( $typeItemArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }
    
    /*!
      Deletes a data type with all items from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $this->deleteImage();
        $res = $db->query( "DELETE FROM eZDataManager_ItemValue WHERE ItemID='$this->ID'" );
        $res = $db->query( "DELETE FROM eZDataManager_Item WHERE ID='$this->ID'" );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();        
    }
    
    /*!
      Returns the object id
    */
    function id()
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
    function name()
    {
        return $this->Name;
    }

    /*!
      Sets the owner group id.
    */
    function setOwnerGroup( $value )
    {
        $this->OwnerGroupID = $value;
    }

    /*!
      Returns the owner group as object or id.
    */
    function ownerGroup( $as_object=true )
    {
        if ( $as_object == true )
        {
            include_once( "ezuser/classes/ezusergroup.php" );
            return new eZUserGroup( $this->OwnerGroupID );
        }
        else
            return $this->OwnerGroupID;
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
      Returns the datatype as a eZDataType object.
    */
    function &dataType()
    {
        $type = new eZDataType( $this->DataTypeID );
        return $type;
    }

    function image()
    {
        if ( $this->Image > 0 )
            return new eZImage( $this->Image );
        else
            return false;
    }

    function setImage( $image )
    {
        $this->Image = $image->id();
    }

    function deleteImage()
    {
        $this->Image = '';
    }
    
    /*!
      Sets a value for the given data type item
    */
    function setItemValue( $typeItem, $value )
    {
        if ( get_class( $typeItem ) == "ezdatatypeitem" )
        {         
            // get old value id if exists
            $db =& eZDB::globalDatabase();
            $typeItemID = $typeItem->id();
        
            $db->array_query( $value_array, "SELECT ID
                                                FROM eZDataManager_ItemValue
                                                WHERE ItemID='$this->ID'
                                                AND DataTypeItemID='$typeItemID'" );

            $value =& $db->escapeString( $value );

            $db->begin( );
            
            if ( count( $value_array ) > 0 )
            {
                $valueID = $value_array[0][$db->fieldName( "ID" )];

                $res = $db->query( "UPDATE eZDataManager_ItemValue SET
		                 Value='$value'
                         WHERE ID='$valueID'" );
                
            }
            else
            {
                $db->lock( "eZDataManager_ItemValue" );

                $nextID = $db->nextID( "eZDataManager_ItemValue", "ID" );
            
                $res = $db->query( "INSERT INTO eZDataManager_ItemValue
                         ( ID, DataTypeItemID, Value, ItemID ) VALUES 
                         ( '$nextID',
                           '$typeItemID',
		                   '$value',
                           '$this->ID' )
                          " );
            }
        
            $db->unlock();
            
            if ( $res == false )
                $db->rollback();
            else
                $db->commit();
        }
    }

    /*!
      Returns the value for the given data type. Returns false if no value is found.
    */
    function itemValue( $typeItem )
    {
        $db =& eZDB::globalDatabase();
        $typeItemID = $typeItem->id();

        $db->array_query( $value_array, "SELECT Value
                                                FROM eZDataManager_ItemValue
                                                WHERE ItemID='$this->ID'
                                                AND DataTypeItemID='$typeItemID'" );

        $value = false;
        if ( count( $value_array ) > 0 )
        {
            $value = $value_array[0][$db->fieldName( "Value" )];
        }
        
        return $value;
    }
    
    /*!
      \static
      Returns all data items which matches the search.
    */
    function &search( $searchText )
    {
        $db =& eZDB::globalDatabase();
        
        if ( is_array( $searchText ) )
            $nameText = trim( $db->escapeString( $searchText["ItemName"] ) );
        else
            $nameText = trim( $db->escapeString( $searchText ) );
        
        $nameSQL = "";
        
        if ( $nameText != "" )
        {
            $nameSQL = "( Value.Value LIKE '%$nameText%'
               OR Item.Name LIKE '%$nameText%'
                                         )  ";
        }

        $itemTypeSQL = "";
        if ( is_array( $searchText ) )
        {            
            $itemTypeArray = $searchText["ItemTypeArray"];

            $itemTypeSQL = "";
            $i=0;
            if ( is_array( $itemTypeArray ) )
            {              
                reset( $itemTypeArray );
                while (list ($key, $val) = each ( $itemTypeArray ) )
                {
                    if ( trim( $val ) != "" )
                    {
                        $val = trim( $val );
                        if ( $i == 0 )
                            $itemTypeSQL .= "( Value.DataTypeItemID='$key' AND Value.Value LIKE '%$val%' )";
                        else
                            $itemTypeSQL .= " OR ( Value.DataTypeItemID='$key' AND Value.Value LIKE '%$val%' )";
                        
                        $i++;
                    }
                }
                if (  $i > 0 )
                {
                    if ( $nameSQL == "" )
                        $itemTypeSQL = " ( $itemTypeSQL ) ";
                    else
                        $itemTypeSQL = " OR ( $itemTypeSQL ) ";
                }

            }
                                
        }
        
        $return_array = array();
        $item_array = array();
        
        $db->array_query( $item_array, "SELECT Item.ID FROM eZDataManager_Item AS Item, eZDataManager_ItemValue AS Value
                                        WHERE Item.ID=Value.ItemID
                                        AND ( $nameSQL $itemTypeSQL )
                                        GROUP BY Item.ID 
                                        ORDER BY Name" );
        for ( $i = 0; $i < count( $item_array ); $i++ )
        { 
            $return_array[$i] = new eZDataItem( $item_array[$i][$db->fieldName( "ID" )], 0 );
        }
        
        return $return_array;
    }

	/// the ID for the data item
    var $ID;

    /// the name of the data item
    var $Name;

    /// the user group which can edit the item
    var $OwnerGroupID;

    /// the datatype for this item
    var $DataTypeID;

    /// the image for this item
    var $Image;
}

?>
