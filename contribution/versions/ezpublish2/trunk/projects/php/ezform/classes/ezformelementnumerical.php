<?php
// 
// $Id: ezformelementnumerical.php,v 1.2 2001/12/21 11:57:33 jhe Exp $
//
// ezformelementtype class
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

//!! eZForm
//! ezformelementtext documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "ezform/classes/ezform.php" );

class eZFormElementNumerical
{

    /*!
      Constructs a new eZFormElementNumericals object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZFormElementNumerical( $id=-1 )
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
      Stores a eZFormElementNumericals object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $MinValue =& $db->escapeString( $this->MinValue );
        $MaxValue =& $db->escapeString( $this->MaxValue );
        
        if ( empty( $this->ID ) || $this->ID == 0 || $this->status == "new" )
        {
            $db->lock( "eZForm_FormElementNumerical" );
            $nextID = $this->ID;
            $res[] = $db->query( "INSERT INTO eZForm_FormElementNumerical
                         ( ElementID, MinValue, MaxValue )
                         VALUES
                         ( '$nextID', '$MinValue', '$MaxValue' )" );

			$this->ID = $nextID;
            unset( $this->status );
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res[] = $db->query( "UPDATE eZForm_FormElementNumerical SET MinValue='$MinValue', MaxValue='$MaxValue' WHERE ElementID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZFormElementNumericals object from the database.
    */
    function delete( $formID=-1 )
    {
        if ( $formID == -1 )
            $formID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZForm_FormElementNumerical WHERE ElementID=$formID" );
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
        if ( $id != "-1" )
        {
            $db->array_query( $formArray, "SELECT * FROM eZForm_FormElementNumerical WHERE ElementID='$id'",
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
        $db =& eZDB::globalDatabase();
        $this->ID =& $formArray["ElementID"];
        $this->MinValue =& $formArray["MinValue"];
        $this->MaxValue =& $formArray["MaxValue"];
    }

    /*!
      Returns all the objects found in the database.

      The objects are returned as an array of eZFormElementNumericals objects.
    */
    function &getAll( )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $formArray = array();

        $db->array_query( $formArray, "SELECT ElementID FROM eZForm_FormElementNumerical" );

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZFormElementNumerical( $formArray[$i][$db->fieldValue( "ElementID" )] );
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
                                     FROM eZForm_FormElementNumerical" );
        $ret = $result[$db->fieldValue( "Count" )];
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
      Returns the minValue of the object.
    */
    function &minValue()
    {
        return htmlspecialchars( $this->MinValue );
    }

    /*!
      Returns the maxValue of the object.
    */
    function &maxValue()
    {
        return htmlspecialchars( $this->MaxValue );
    }

    /*!
      Sets the id of the object.
    */
    function setID( &$ID )
    {
       $this->ID = $ID;
       $this->status = "new";
    }
    /*!
      Sets the minValue of the object.
    */
    function setMinValue( &$text )
    {
       $this->MinValue = $text;
    }
    
    /*!
      Sets the maxValue of the object.
    */
    function setMaxValue( &$text )
    {
       $this->MaxValue = $text;
    }

    function validNumber( $value )
    {
        if ( $value >= $this->MinValue && $value <= $this->MaxValue )
            return true;
        else
            return false;
    }
    
    var $ID;
    var $MinValue;
    var $MaxValue;
}

?>
