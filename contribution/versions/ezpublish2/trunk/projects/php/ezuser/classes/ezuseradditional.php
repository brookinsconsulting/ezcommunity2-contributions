<?php
// 
// $Id: ezuseradditional.php,v 1.2 2001/11/20 16:11:58 ce Exp $
//
// Definition of eZCompany class
//
// Created on: <26-Sep-2000 18:45:40 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! 
//! ezuseradditional handles user groups.
/*!
  
  Example code:
  \code
  \endcode
  \sa eZUser
*/

/*!TODO
*/

include_once( "ezuser/classes/ezuser.php" );

class eZUserAdditional
{

    /*!
      Constructs a new eZUserAdditional object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZUserAdditional( $id = -1, $fetch = true )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores or updates a eZUserAdditional object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin();
        
        $name = $db->escapeString( $this->Name );
             
        if ( !isSet( $this->ID ) )
        {
            $db->array_query( $attribute_array, "SELECT Placement FROM eZUser_Additional" );

            if ( count ( $attribute_array ) > 0 )
            {
                $place = max( $attribute_array );
                $place = $place[$db->fieldName( "Placement" )];
                $place++;
            }

            $db->lock( "eZUser_Additional" );

            $nextID = $db->nextID( "eZUser_Additional", "ID" );

            $db->query( "INSERT INTO eZUser_Additional
                         (ID, Name, Type, Placement)
                         VALUES
                         ('$nextID', '$name', '$this->Type', '$place')" );

            $this->ID = $nextID;

        }
        else
        {
            $db->query( "UPDATE eZUser_Additional SET
                                 Name='$name',
                                 Type='$this->Type'
                                 WHERE ID='$this->ID'" );            
        }

        $db->unlock();
        
        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a ezuseradditional object from the database.
    */
    function delete( $id = false )
    {
        $db =& eZDB::globalDatabase();

        if ( !$id )
            $id = $this->ID;

        if ( isSet( $id ) )
        {
            $db->query( "DELETE FROM eZUser_AdditionalValue WHERE AdditionalID='$id'" );
            $db->query( "DELETE FROM eZUser_Additional WHERE ID='$id'" );
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $user_group_array, "SELECT * FROM eZUser_Additional WHERE ID='$id'",
                              array( "Offset" => 0, "Limit" => 1 ) );
            if ( count( $user_group_array ) == 1 )
            {
                $this->fill( $user_group_array[0] );
            }
        }
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$user_group_array )
    {
        $db =& eZDB::globalDatabase();
        
        $this->ID = $user_group_array[$db->fieldName( "ID" )];
        $this->Name = $user_group_array[$db->fieldName( "Name" )];
        $this->Type = $user_group_array[$db->fieldName( "Type" )];
        $this->Placement = $user_group_array[$db->fieldName( "Placement" )];
    }

    /*!
      \static
      Returns every user group from the database. The result is returned as an
      array of ezuseradditional objects.
    */
    function &getAll( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $group_array = array();

        if ( $as_object )
            $select = "*";
        else
            $select = "ID,Name";

        $db->array_query( $group_array, "SELECT $select FROM eZUser_Additional ORDER By Placement" );

        if ( $as_object )
        {
            for ( $i = 0; $i < count( $group_array ); $i++ )
            {
                $return_array[$i] = new eZUserAdditional( $group_array[$i] );
            }
        }
        else
        {
            for ( $i = 0; $i < count( $group_array ); $i++ )
            {
                $return_array[$i] =& $group_array[$db->fieldName( "ID" )];
            }
        }

        return $return_array;
    }

    /*!
      Returns the object ID to the additional.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the additional name.
    */
    function name( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Name );
        else
            return $this->Name;
    }

    /*!
      Returns the additional type.
      1 = textfield
      2 = radiobox
    */
    function type( )
    {
        return $this->Type;
    }

    /*!
      Sets the name of the additional.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the type of the additional.
      1 = textfield
      2 = radiobox
    */
    function setType( $value )
    {
       $this->Type = $value;
    }

    /*!
      Adds a user to the current user group.

      Returns true if successful, false if not.
    */
    function addValue( &$user, $value=false )
    {
       $ret = false;

       if ( get_class( $user ) == "ezuser" )
       {
           $db =& eZDB::globalDatabase();

           $dbError = false;
           $db->begin( );
           $id = false;
           
           $userID = $user->id();

           $db->query_single( $checkArray, "SELECT ID FROM eZUser_AdditionalValue
                                  WHERE AdditionalID='$this->ID' AND UserID='$userID'" );
           
           $id = $checkArray[$db->fieldName( "ID" )];
           if ( is_numeric ( $id ) )
           {
               $res = $db->query( "UPDATE eZUser_AdditionalValue
                                   SET Value='$value'
                                   WHERE ID='$id')" );
               if ( $res == false )
                   $dbError = true;
               $ret = true;
           }
           else
           {
               $db->lock( "eZUser_AdditionalValue" );
               $nextID = $db->nextID( "eZUser_AdditionalValue", "ID" );
               
               $res = $db->query( "INSERT INTO eZUser_AdditionalValue
                            ( ID, UserID, AdditionalID, Value )
                            VALUES
                            ( '$nextID', '$userID', '$this->ID', '$value' )" );
               
               if ( $res == false )
                   $dbError = true;
               $ret = true;
           }
           
           $db->unlock();  
           if ( $dbError == true )
               $db->rollback( );
           else
               $db->commit();
           
       } 
       return $ret;
    }
    
    /*!
      Returns the attribute value to the given product.
    */
    function value( $user )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        if ( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();
            
            $db->query_single( $valueArray,
            "SELECT Value FROM eZUser_AdditionalValue
             WHERE UserID='$userID' AND AdditionalID='$this->ID'" );

            if ( count( $valueArray ) == 2 )
            {
                $ret = $valueArray[$db->fieldName( "Value" )];
            }
        }
        return $ret;
    }

    /*!
      Adds a user to the current user group.

      Returns true if successful, false if not.
    */
    function addFixedValue( $valueID=false, $value=false )
    {
        $ret = false;

        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );
        $id = false;
           
        $db->query_single( $checkArray, "SELECT ID FROM eZUser_AdditionalFixedValue
                                  WHERE AdditionalID='$this->ID' AND ID='$valueID'" );
        $id = $checkArray[$db->fieldName( "ID" )];
        if ( is_numeric ( $id ) )
        {
            $res = $db->query( "UPDATE eZUser_AdditionalFixedValue
                                   SET Value='$value'
                                   WHERE ID='$valueID'" );
            if ( $res == false )
                $dbError = true;
            $ret = true;
        }
        else
        {
            $db->lock( "eZUser_AdditionalFixedValue" );
            $nextID = $db->nextID( "eZUser_AdditionalFixedValue", "ID" );
               
            $res = $db->query( "INSERT INTO eZUser_AdditionalFixedValue
                            ( ID, AdditionalID, Value )
                            VALUES
                            ( '$nextID', '$this->ID', '$value' )" );
               
            if ( $res == false )
                $dbError = true;
            $ret = true;
        }
           
        $db->unlock();  
        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();
        return $ret;
    }

    /*!
      Returns the attribute value to the given product.
    */
    function fixedValues( $id=false )
    {
        $db =& eZDB::globalDatabase();
        $return = array();

        if ( $id == false )
            $id = $this->ID;
        
        $db->array_query( $valuesArray,
                           "SELECT ID, Value FROM eZUser_AdditionalFixedValue
                            WHERE AdditionalID='$this->ID'" );

        foreach( $valuesArray as $value )
        {
            $return[] = array( "ID" => $value[$db->fieldName( "ID" )], "Value" => $value[$db->fieldName( "Value" )] );
        }
        
        return $return;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->query_single( $qry, "SELECT ID, Placement FROM eZUser_Additional
                                  WHERE Placement<'$this->Placement' ORDER BY Placement DESC", array( "Limit" => 1, "Offset" => 0 ) );
        $listorder = $qry[$db->fieldName( "Placement" )];
        $listid = $qry[$db->fieldName( "ID" )];
        $res[] = $db->query( "UPDATE eZUser_Additional SET Placement='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZUser_Additional SET Placement='$this->Placement' WHERE ID='$listid'" );

        eZDB::finish( $res, $db );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $db->query_single( $qry, "SELECT ID, Placement FROM eZUser_Additional
                                  WHERE Placement>'$this->Placement' ORDER BY Placement ASC", array( "Limit" => 1, "Offset" => 0 ) );
        $listorder = $qry[$db->fieldName( "Placement" )];
        $listid = $qry[$db->fieldName( "ID" )];
        $res[] = $db->query( "UPDATE eZUser_Additional SET Placement='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZUser_Additional SET Placement='$this->Placement' WHERE ID='$listid'" );

        eZDB::finish( $res, $db );
    }

    var $ID;
    var $Name;
    var $Type;
    var $Placement;
}

?>
