<?php
// 
// $Id: ezformelementtext.php,v 1.1 2001/12/18 16:32:47 pkej Exp $
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

class eZFormElementText
{

    /*!
      Constructs a new eZFormElementTexts object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZFormElementText( $id=-1 )
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
      Stores a eZFormElementTexts object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $text =& $db->escapeString( $this->Text );
        
        if ( empty( $this->ID ) || $this->ID == 0 || $this->status == "new" )
        {
            $db->lock( "eZForm_FormElementText" );
            $nextID = $this->ID;
            $res[] = $db->query( "INSERT INTO eZForm_FormElementText
                         ( ElementID, TextBlock )
                         VALUES
                         ( '$nextID', '$text' )" );

			$this->ID = $nextID;
            unset( $this->status );
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res[] = $db->query( "UPDATE eZForm_FormElementText SET TextBlock='$text' WHERE ElementID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZFormElementTexts object from the database.
    */
    function delete( $formID=-1 )
    {
        if ( $formID == -1 )
            $formID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZForm_FormElementText WHERE ElementID=$formID" );
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
            $db->array_query( $formArray, "SELECT * FROM eZForm_FormElementText WHERE ElementID='$id'",
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
        $this->Text =& $formArray["TextBlock"];
    }

    /*!
      Returns all the objects found in the database.

      The objects are returned as an array of eZFormElementTexts objects.
    */
    function &getAll( )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $formArray = array();

        $db->array_query( $formArray, "SELECT ElementID FROM eZForm_FormElementText" );

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZFormElementText( $formArray[$i][$db->fieldValue( "ElementID" )] );
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
                                     FROM eZForm_FormElementText" );
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
      Returns the text of the object.
    */
    function &text()
    {
        return htmlspecialchars( $this->Text );
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
      Sets the text of the object.
    */
    function setText( &$text )
    {
       $this->Text = $text;
    }
    
    var $ID;
    var $Text;
}

?>
