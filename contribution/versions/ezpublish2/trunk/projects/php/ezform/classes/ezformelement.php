<?php
// 
// $Id: ezformelement.php,v 1.25 2002/01/03 07:58:53 jhe Exp $
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
include_once( "ezform/classes/ezformelementfixedvalue.php" );

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
        $size =& $db->escapeString( $this->Size );
        $required =& $this->Required;
        
        if ( get_class( $this->ElementType ) == "ezformelementtype" )
        {
            $elementTypeID =& $this->ElementType->id();
        }
        
        if ( empty( $this->ID ) )
        {
            $db->lock( "eZForm_FormElement" );
            $nextID = $db->nextID( "eZForm_FormElement", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormElement
                         ( ID, Name, Required, Size, Break, ElementTypeID )
                         VALUES
                         ( '$nextID', '$name', '$required', '$size', '$this->Break', '$elementTypeID' )" );

			$this->ID = $nextID;


        }
        elseif ( is_numeric( $this->ID ) )
        {    
            $res[] = $db->query( "UPDATE eZForm_FormElement SET
                                    Name='$name',
                                    Required='$required',
                                    Size='$size',
                                    Break='$this->Break',
                                    ElementTypeID='$elementTypeID'
                                  WHERE ID='$this->ID'" );
        }
        
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZFormElement object from the database.
    */
    function delete( $elementID = -1 )
    {
        if ( $elementID == -1 )
            $elementID = $this->ID;

        $fixedValues =& $this->fixedValues();
        if ( $fixedValues )
        {
            foreach ( $fixedValues as $value )
            {
                $value->delete();
            }
        }

        if ( $this->Name == "table_item" )
        {
            $table = new eZFormTable( $elementID );
            $table->delete();
        }
        
        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZForm_PageElementDict WHERE ElementID='$elementID'" );
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
            if ( count( $formArray ) == 1 )
            {
                $this->fill( &$formArray[0] );
                $ret = true;
            }
            elseif ( count( $formArray ) != 1 )
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
        $db =& eZDB::globalDatabase();

        $this->ID =& $formArray[$db->fieldName( "ID" )];
        $this->Name =& $formArray[$db->fieldName( "Name" )];
        $this->Required =& $formArray[$db->fieldName( "Required" )];
        $this->Size =& $formArray[$db->fieldName( "Size" )];
        $this->Break =& $formArray[$db->fieldName( "Break" )];
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
                                           ORDER BY Name DESC" );

        }
        else
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZForm_FormElement
                                           ORDER BY Name DESC",
                                           array( "Limit" => $limit, "Offset" => $offset ) );
        }

        for ( $i = 0; $i < count( $formArray ); $i++ )
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
      Returns the size of the object.
    */
    function size()
    {
        return htmlspecialchars( $this->Size );
    }

    function getConditions()
    {
        $db =& eZDB::globalDatabase();
        $condArray = array();
        $db->array_query( $condArray, "SELECT * FROM eZForm_FormCondition WHERE ElementID='$this->ID'" );
        $returnArray = array();
        $i = 0;
        foreach ( $condArray as $cond )
        {
            $returnArray[$i]["Min"] = $cond[$db->fieldName( "Min" )];
            $returnArray[$i]["Max"] = $cond[$db->fieldName( "Max" )];
            $returnArray[$i]["Page"] = $cond[$db->fieldName( "PageID" )];
            $i++;
        }
        return $returnArray;
    }
    
    /*!
      Returns true if this element is required
    */
    function isRequired()
    {
        if ( $this->Required == 0 )
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
      Returns true if this element is breaking
    */
    function isBreaking()
    {
        if ( $this->Break == 0 )
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
      Sets the size of the object.
    */
    function setSize( $value )
    {
       $this->Size = $value;
    }

    /*!
      Sets the required status.
    */
    function setRequired( $value = true )
    {
        if ( $value == true )
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
      Sets the break status.
    */
    function setBreak( $value = true )
    {
        if ( $value == true )
        {
            $value = 1;
        }
        else
        {
            $value = 0;
        }
        $this->Break = $value;
    }

    /*!
      Sets the ElementType.
    */
    function setElementType( &$object )
    {
        if ( get_class( $object ) == "ezformelementtype" )
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
        $db->array_query( $formArray, "SELECT FormID FROM eZForm_PageElementDict WHERE ElementID='$this->ID'" );

        for ( $i = 0; $i < count( $formArray ); $i++ )
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


    function &getConditionByPage( $pageID )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $db->query_single( $pageID, "SELECT ElementID FROM eZForm_FormCondition WHERE
                     PageID='$pageID' GROUP BY ElementID" );
        
        return $pageID[$db->fieldName( "ElementID" )];
    }


    function getConditionMaxByPage( $min=0, $max=0 )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $db->query_single( $pageID, "SELECT PageID FROM eZForm_FormCondition WHERE
                     ElementID='$this->ID' AND Min='$min' AND Max='$max'" );

        return $pageID[$db->fieldName( "PageID" )];
        
        
    }
    
    function elementInCondition()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $ret = false;

        $db->array_query( $elementID, "SELECT PageID, Min, Max FROM eZForm_FormCondition WHERE
                     ElementID='$this->ID'" );

        for ( $i = 0; $i < count( $elementID ); $i++ )
        {
            $ret[$i]["Page"] = $elementID[$i][$db->fieldName( "PageID" )];
            $ret[$i]["Min"] = $elementID[$i][$db->fieldName( "Min" )];
            $ret[$i]["Max"] = $elementID[$i][$db->fieldName( "Max" )];
        }
        
        return $ret;
    }
    
    
    /*!
      Removes all Conditions which have the given ID.
     */
    function removeCondition()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $ret = array();

        $res[] = $db->query( "DELETE FROM eZForm_FormCondition WHERE
                              ElementID='$this->ID'" );
        
        eZDB::finish( $res, $db );               
    }

    
    /*!
      Add a form condition to the database.
    */
    function addCondition( $page_id, $min=0, $max=0 )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        
        $db->lock( "eZForm_FormCondition" );
        $nextID = $db->nextID( "eZForm_FormCondition", "ID" );
        
        $res[] = $db->query( "INSERT INTO eZForm_FormCondition
                         ( ID, ElementID, PageID, Min, Max )
                         VALUES
                         ( '$nextID', '$this->ID', '$page_id', '$min', '$max' )" );
        eZDB::finish( $res, $db );               
    }

    
    /*!
      Sets if this element has fixed values.
    */
    function addValue( &$value )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( is_object( $value ) )
             $value = $value->id();
        
        $db->lock( "eZForm_FormElementFixedValueLink" );
        $nextID = $db->nextID( "eZForm_FormElementFixedValueLink", "ID" );
        $res[] = $db->query( "INSERT INTO eZForm_FormElementFixedValueLink
                         ( ID, ElementID, FixedValueID )
                         VALUES
                         ( '$nextID', '$this->ID', '$value' )" );
        
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Returns true if this type has fixed values.
    */
    function &fixedValues()
    {
        $returnArray = array();
        $formArray = array();
        
        $db =& eZDB::globalDatabase();
        if ( get_class( $this->ElementType ) == "ezformelementtype" )
        {
            if ( $this->ElementType->name() == "multiple_select_item" ||
                 $this->ElementType->name() == "dropdown_item" ||
                 $this->ElementType->name() == "checkbox_item" ||
                 $this->ElementType->name() == "radiobox_item" )
            {
                $db->array_query( $formArray, "SELECT fv.ID, fv.Value FROM eZForm_FormElementFixedValues as fv, eZForm_FormElementFixedValueLink as fvl
                                               WHERE fv.ID=fvl.FixedValueID AND fvl.ElementID='$this->ID' ORDER BY fv.Value" );
                
                for ( $i = 0; $i < count( $formArray ); $i++ )
                {
                    $returnArray[$i] = new eZFormElementFixedValue( $formArray[$i] );
                }
            }
            else
            {
                $db->array_query( $qa, "SELECT FixedValueID AS ID FROM eZForm_FormElementFixedValueLink WHERE ElementID='$this->ID'" );
                $db->query( "DELETE FROM eZForm_FormElementFixedValueLink WHERE ElementID='$this->ID'" );
                foreach ( $qa as $q )
                {
                    $db->query( "DELETE FROM eZForm_FormElementFixedValues WHERE ID='" . $q[$db->fieldName( "ID" )] . "'" );
                }
            }
        }
        return $returnArray;
    }

    /*!
      Returns the value of the element
    */
    function result( $hash = -1 )
    {
        $result = array();
        $session =& eZSession::globalSession();
        if ( $hash == -1 )
            $hash = $session->hash();
        $db =& eZDB::globalDatabase();
        $db->array_query( $result, "SELECT fer.Result AS Result FROM eZForm_FormResults AS fr,
                                     eZForm_FormElementResult AS fer WHERE
                                     fer.ResultID=fr.ID AND ElementID='$this->ID'
                                     AND fr.UserHash='$hash'" );
        if ( count( $result ) == 1 )
            return $result[0][$db->fieldName( "Result" )];
        else
            return false;
    }

    function setResult( $value, $hash = -1 )
    {
        $session =& eZSession::globalSession();
        if ( $hash == -1 )
            $hash = $session->hash();
        $res = array();
        $result = array();
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->array_query( $result, "SELECT ID FROM eZForm_FormResults
                                     WHERE UserHash='$hash'" );
        if ( count( $result ) == 1 )
        {
            $resultID = $result[0][$db->fieldName( "ID" )];
        }
        else
        {
            $db->lock( "eZForm_FormResults" );
            $resultID = $db->nextID( "eZForm_FormResults", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormResults (ID, UserHash, IsRegistered)
                                  VALUES
                                  ('$resultID', '$hash', '0')" );
            $db->unlock();
        }
        $resultArray = array();
        $db->array_query( $resultArray, "SELECT ID FROM eZForm_FormElementResult
                          WHERE ElementID='$this->ID' AND ResultID='$resultID'" );

        if ( count( $resultArray ) == 0 )
        {
            $db->lock( "eZForm_FormElementResult", "ID" );
            $nextID = $db->nextID( "eZForm_FormElementResult", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormElementResult
                                  (ID, ElementID, ResultID, Result)
                                  VALUES
                                  ('$nextID','$this->ID','$resultID','$value')" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZForm_FormElementResult SET
                                Result='$value'
                                WHERE ElementID='$this->ID' AND
                                ResultID='$resultID'" );
        }
        eZDB::finish( $res, $db );
    }
    
    var $ID;
    var $Name;
    var $Required;
    var $ElementType;
    var $Break;
    var $Size;
}


?>
                  
