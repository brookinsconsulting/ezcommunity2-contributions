<?
// 
// $Id: ezformelementtype.php,v 1.1 2001/06/15 15:40:34 pkej Exp $
//
// ezformelementtype class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <11-Jun-2001 12:07:57 pkej>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

//!! ezformelementtype
//! ezformelementtype documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "ezform/classes/ezform.php" );

class eZFormElementType
{

    /*!
      Constructs a new eZFormElementType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZFormElementType( $id=-1, $fetch=true )
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
      Stores a eZFormElementType object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name =& addslashes( $this->Name );
        $description =& addslashes( $this->Description );
        
        $setValues = "
            Name='$name',
            Description='$description'
        ";
        if ( empty( $this->ID ) )
        {
            $db->query( "INSERT INTO eZForm_FormElementType SET $setValues" );

			$this->ID = $db->insertID();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $db->query( "UPDATE eZForm_FormElementType SET $setValues WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZFormElementType object from the database.
    */
    function delete( $formID=-1 )
    {
        if ( $formID == -1 )
            $formID = $this->ID;

        $db =& eZDB::globalDatabase();

        $formElements =& $this->formElements();
        if ( is_array ( $formElements ) )
        {
            foreach( $formElements as $element )
            {
                $formElements->delete();
            }
        }
        
        $db->query( "DELETE FROM eZForm_FormElementType WHERE ID=$formID" );
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
            $db->array_query( $formArray, "SELECT * FROM eZForm_FormElementType WHERE ID='$id'",
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
        $this->ID =& $formArray[ "ID" ];
        $this->Name =& $formArray[ "Name" ];
        $this->Description =& $formArray[ "Description" ];
    }

    /*!
      Returns all the objects found in the database.

      The objects are returned as an array of eZFormElementType objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $formArray = array();

        if ( $limit == false )
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZForm_FormElementType
                                           ORDER BY Name DESC
                                           " );

        }
        else
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZForm_FormElementType
                                           ORDER BY Name DESC
                                           LIMIT $offset, $limit" );
        }

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZFormElementType( $formArray[$i]["ID"] );
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
                                     FROM eZForm_FormElementType" );
        $ret = $result["Count"];
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
      Returns the description of the object.
    */
    function &description()
    {
        return htmlspecialchars( $this->Description );
    }


    /*!
      Sets the name of the object.
    */
    function setName( &$value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the description of the object.
    */
    function setDescription( &$value )
    {
       $this->Description = $value;
    }

    /*!
      Returns every element which this form element type is associated with.
      The form elements are returned as an array of eZFormElement objects.
    */
    function &formElements()
    {
        $returnArray = array();
        $formArray = array();
        
        $db =& eZDB::globalDatabase();
        $db->array_query( $formArray, "SELECT ID FROM eZForm_FormElement WHERE ElementTypeID='$this->ID'" );

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZFormElement( $formArray[$i]["ID"], true );
        }
        return $returnArray;
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
        $ret = $result["Count"];
        
        return $ret;
    }

    var $ID;
    var $Name;
    var $Description;
}

?>
