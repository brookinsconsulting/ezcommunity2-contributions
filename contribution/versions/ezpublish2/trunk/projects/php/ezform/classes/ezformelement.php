<?php
// 
// $Id: ezformelement.php,v 1.4 2001/08/16 10:00:36 br Exp $
//
// ezformelement class
//
// Created on: <11-Jun-2001 12:07:57 pkej>
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

//!! eZForum
//! ezformelement documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelementtype.php" );

class eZFormElement
{

    /*!
      Constructs a new eZFormElement object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZFormElement( $id=-1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZFormElement object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $name =& $db->escapeString( $this->Name );
        $required =& $this->Required;
        
        if( get_class( $this->ElementType ) == "ezformelementtype" )
        {
            $elementTypeID =& $this->ElementType->id();
        }
        
        if ( empty( $this->ID ) )
        {
            $db->lock( "eZForm_FormElement" );
            $nextID = $db->nextID( "eZForm_FormElement", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormElement
                         ( ID, Name, Required, ElementTypeID )
                         VALUES
                         ( '$nextID', '$name', '$required', '$elementTypeID' )" );

			$this->ID = $nextID;
}
        elseif ( is_numeric( $this->ID ) )
        {    
            $res[] = $db->query( "UPDATE eZForm_FormElement SET
                                    Name='$name',
                                    Required='$required',
                                    ElementTypeID='$elementTypeID'
                                  WHERE ID='$this->ID'" );
        }
        
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZFormElement object from the database.
    */
    function delete( $elementID=-1 )
    {
        if ( $elementID == -1 )
            $elementID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZForm_FormElementDict WHERE ElementID='$elementID'" );
        $res[] = $db->query( "DELETE FROM eZForm_FormElement WHERE ID='$elementID'" );

        eZDB::finish( $res, $db );
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $formArray, "SELECT * FROM eZForm_FormElement WHERE ID='$id'",
                              0, 1 );
            if( count( $formArray ) == 1 )
            {
                $this->fill( &$formArray[0] );
                $ret = true;
            }
            elseif( count( $formArray ) != 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$formArray )
    {
        $db = eZDB::globalDatabase();

        $this->ID =& $formArray[$db->fieldName( "ID" )];
        
        
        $this->Name =& $formArray[$db->fieldName( "Name" )];
        $this->Required =& $formArray[$db->fieldName( "Required" )];
        $this->ElementType =& new eZFormElementType( $formArray[$db->fieldName( "ElementTypeID" )] );
    }

    /*!
      Returns all the objects found in the database.

      The objects are returned as an array of eZFormElement objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $formArray = array();

        if ( $limit == false )
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZForm_FormElement
                                           ORDER BY Name DESC
                                           " );

        }
        else
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZForm_FormElement
                                           ORDER BY Name DESC",
                                           array( "Limit" => $limit, "Offset" => $offset ) );
        }

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZFormElement( $formArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count of objects in the database.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZForm_FormElement" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns the object ID. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the object.
    */
    function &name()
    {
        return htmlspecialchars( $this->Name );
    }

    /*!
      Returns true if this element is required
    */
    function isRequired()
    {
        if( $this->Required == 0 )
        {
            $ret = false;
        }
        else
        {
            $ret = true;
        }
        
        return $ret;
    }

    /*!
      Returns the ElementType of the object.
    */
    function &elementType()
    {
        return $this->ElementType;
    }

    /*!
      Sets the name of the object.
    */
    function setName( &$value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the required status.
    */
    function setRequired( $value = true )
    {
        if( $value == true )
        {
            $value = 1;
        }
        else
        {
            $value = 0;
        }
        $this->Required = $value;
    }

    /*!
      Sets the ElementType.
    */
    function setElementType( &$object )
    {
        if( get_class( $object ) == "ezformelementtype" )
        {
            $this->ElementType = $object;
        }
    }

    /*!
      Returns every form of this form element is associated with.
      The form elements are returned as an array of eZForm objects.
    */
    function &forms()
    {
        $returnArray = array();
        $formArray = array();
        
        $db =& eZDB::globalDatabase();
        $db->array_query( $formArray, "SELECT FormID FROM eZForm_FormElementDict WHERE ElementID='$this->ID'" );

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZFormElement( $formArray[$i][$db->fieldName( "FormID" )], true );
        }
        return $returnArray;
    }
    
    
    /*!
      Returns the number of forms this element belongs to
    */
    function &numberOfForms()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ElementID) as Count
                                     FROM eZForm_FormElementDict WHERE ElementID='$this->ID'" );
        $ret = $result[$db->fieldName( "Count" )];
        
        return $ret;
    }

    /*!
      Returns the number of types which exists
    */
    function &numberOfTypes()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZForm_FormElementType" );
        $ret = $result[$db->fieldName( "Count" )];
        
        return $ret;
    }



    var $ID;
    var $Name;
    var $Required;
    var $ElementType;
}

?>
