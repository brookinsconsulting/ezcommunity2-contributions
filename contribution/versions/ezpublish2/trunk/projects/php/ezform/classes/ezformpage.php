<?php
// 
// $Id: ezformpage.php,v 1.8 2001/12/18 18:29:20 jhe Exp $
//
// Definition of ||| class
//
// Created on: <13-Dec-2001 15:17:47 br>
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

//!! |||
//! 
/*!
 
  Example code:
  \code
  \endcode

*/

include_once( "ezform/classes/ezformelement.php" );
       
class eZFormPage
{
    /*!
      Constructs a new eZFormPage object.
    */
    function eZFormPage( $id = -1 )
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
      Store the formPage to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name =& $db->escapeString( $this->Name );
        
        if ( empty( $this->ID ) )
        {
            $db->lock( "eZForm_FormPage" );
            $db->query_single( $qry, "SELECT MAX( Placement ) as Placement FROM eZForm_FormPage
                                      WHERE FormID='$this->FormID'" );
            $placement = $qry[$db->fieldName( "Placement" )] + 1;
            
            $nextID = $db->nextID( "eZForm_FormPage", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormPage
                          ( ID,
                            Name,
                            FormID,
                            Placement )
                          VALUES
                          ( '$nextID',
                            '$name',
                            '$this->FormID',
                            '$placement' )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res[] = $db->query( "UPDATE eZForm_FormPage SET
                                    Name='$name',
                                    FormID='$this->FormID',
                                    Placement='$this->Placement'
                                  WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        
        return true;
    }

    /*!
      Get a formpage from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != -1 )
        {
            $db->array_query( $formPageArray, "SELECT * FROM eZForm_FormPage WHERE ID='$id'", 0, 1 );
            if ( count( $formPageArray ) == 1 )
            {
                $this->fill( &$formPageArray[0] );
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Fetches all Pages from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $db->array_query( $formPageArray, "SELECT ID
                                       FROM eZForm_FormPage
                                       ORDER BY Placement" );

        for ( $i = 0; $i < count( $formPageArray ); $i++ )
        {
            $returnArray[$i] = new eZFormPage( $formPageArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Fetches all Pages to a form.
    */
    function &getByFormID( $id="" )
    {
        $db =& eZDB::globalDatabase();
        $returnArray = array();
        
        if ( $id != "" )
        {
            $db->array_query( $formPageArray, "SELECT ID
                                               FROM eZForm_FormPage
                                               Where FormID='$id'
                                               ORDER BY Placement" );

            for ( $i = 0; $i < count( $formPageArray ); $i++ )
            {
                $returnArray[$i] = new eZFormPage( $formPageArray[$i][$db->fieldName( "ID" )] );
            }
        }
        return $returnArray;
    }

    function &numberOfElements()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ElementID) as Count
                                     FROM eZForm_PageElementDict WHERE PageID='$this->ID'" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    function addElement( &$object )
    {
        if ( get_class( $object ) == "ezformelement" )
        {
            $elementID = $object->id();
            $elementName = $object->name();
            $pageID = $this->ID;

            $db =& eZDB::globalDatabase();
            $db->begin();
            $db->query_single( $result, "SELECT MAX(Placement) as Placement
                                     FROM eZForm_PageElementDict WHERE PageID='$pageID'" );
            
            $placement = $result[$db->fieldName( "Placement" )];
            $placement++;
            $db->lock( "eZForm_PageElementDict" );
            $nextID = $db->nextID( "eZForm_PageElementDict", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_PageElementDict
                                   ( ID, Placement, ElementID, PageID, Name )
                                   VALUES
                                   ( '$nextID', '$placement', '$elementID', '$pageID', '$elementName' )" );
            eZDB::finish( $res, $db );
            return true;
        }
    }

    function &formElement( $placement )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $element, "SELECT ElementID FROM eZForm_PageElementDict WHERE
                                      PageID='$this->ID' AND Placement='$placement'" );

        $return = new eZFormElement( $element[$db->fieldName( "ElementID" )], true );
           
        return $return;
    }


    /*!
      Returns every form element of this page.
      The page elements are returned as an array of eZFormElement objects.
    */
    function &pageElements()
    {
        $returnArray = array();
        $formArray = array();
        
        $db =& eZDB::globalDatabase();
        $db->array_query( $formArray, "SELECT ElementID FROM eZForm_PageElementDict WHERE
                                       PageID='$this->ID' ORDER BY Placement" );

        for ( $i = 0; $i < count( $formArray ); $i++ )
        {
            $returnArray[$i] = new eZFormElement( $formArray[$i][$db->fieldName( "ElementID" )], true );
        }
        return $returnArray;
    }


    /*!
      Deletes a form page.
     */
    function delete( $pageID=-1 )
    {
        if ( $pageID == -1 )
            $pageID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZForm_FormPage WHERE ID=$pageID" );
        
        eZDB::finish( $res, $db );
    }


    /*!
      private
      
      Fills in information to the object taken from the array.
    */
    function fill( &$pageArray )
    {
        $db =& eZDB::globalDatabase();
        
        $this->ID =& $pageArray[$db->fieldName( "ID" )];
        $this->Name =& $pageArray[$db->fieldName( "Name" )];
        $this->FormID =& $pageArray[$db->fieldName( "FormID" )];
        $this->Placement =& $pageArray[$db->fieldName( "Placement" )];
    }

    /*!
      move the page one placement up
     */
    function moveUp( $id="" )
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $qry, "SELECT Placement FROM eZForm_FormPage WHERE ID='$id'" );
        $originalPlacement = $qry[$db->fieldName( "Placement" )];
        
        if ( $id != "" )
        {
            $db->array_query( $pageArray, "SELECT Placement, ID FROM eZForm_FormPage
                                           WHERE Placement < " . $originalPlacement .
                                          " ORDER BY Placement DESC",
                                           array( "Limit" => 1, "Offset" => 0 ) );
            // if the page allready is at the top, move it to the bottom
            if ( count( $pageArray ) == 0 )
            {
                $pageArray = array();
                $db->array_query( $pageArray, "SELECT Placement, ID FROM eZForm_FormPage" .
                                  " WHERE Placement > " . $originalPlacement .
                                  " ORDER BY Placement DESC",
                                    array( "Limit" => 1, "Offset" => 0 ) );
            }

            $newPlacement = $pageArray[0][$db->fieldName( "Placement" )];
            $newID = $pageArray[0][$db->fieldName( "ID" )];
            
            if ( count( $pageArray ) > 0 )
            {
                $res[] = $db->query( "UPDATE eZForm_FormPage SET
                                      Placement='$newPlacement' WHERE ID='$id'" );

                $res[] = $db->query( "UPDATE eZForm_FormPage SET
                                      Placement='$originalPlacement' WHERE ID='$newID'" );
                eZDB::finish( $res, $db );
            }
        }
    }

    /*!
      move the page one placement down
    */
    function moveDown( $id="" )
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $qry, "SELECT Placement FROM eZForm_FormPage WHERE ID='$id'" );
        $originalPlacement = $qry[$db->fieldName( "Placement" )];
        
        if ( $id != "" )
        {
            $db->array_query( $pageArray, "SELECT Placement, ID FROM eZForm_FormPage
                                           WHERE Placement > " . $originalPlacement .
                                          " ORDER BY Placement ASC",
                                           array( "Limit" => 1, "Offset" => 0 ) );
            // if the page allready is at the bottom, move it to the top
            if ( count( $pageArray ) == 0 )
            {
                $pageArray = array();
                $db->array_query( $pageArray, "SELECT Placement, ID FROM eZForm_FormPage" .
                                  " WHERE Placement < " . $originalPlacement .
                                  " ORDER BY Placement ASC",
                                    array( "Limit" => 1, "Offset" => 0 ) );
            }

            $newPlacement = $pageArray[0][$db->fieldName( "Placement" )];
            $newID = $pageArray[0][$db->fieldName( "ID" )];
            
            if ( count( $pageArray ) > 0 )
            {
                $res[] = $db->query( "UPDATE eZForm_FormPage SET
                                      Placement='$newPlacement' WHERE ID='$id'" );

                $res[] = $db->query( "UPDATE eZForm_FormPage SET
                                      Placement='$originalPlacement' WHERE ID='$newID'" );
                eZDB::finish( $res, $db );
            }
        }
    }


    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveElementUp( $object )
    {
        if ( get_class( $object ) == "ezformelement" )
        {
            $db =& eZDB::globalDatabase();

            $pageID = $this->id();
            $elementID = $object->id();
            
            $db->query_single( $qry, "SELECT Placement FROM eZForm_PageElementDict
                                      WHERE ElementID = '$elementID' AND PageID = '$pageID' ORDER BY Placement DESC",
                                      array( "Limit" => 1, "Offset" => 0) );

            $elementPlacement = $qry[$db->fieldName( "Placement" )];

            $db->query_single( $qry, "SELECT min( $db->fieldName( \"Placement\" ) ) as Placement
                                      FROM eZForm_PageElementDict WHERE PageID='$pageID'" );
            $min =& $qry[$db->fieldName( "Placement" )];

            if ( $min == $elementPlacement )
            {
                $db->query_single( $qry, "SELECT max($db->fieldName( \"Placement\" ) ) as Placement
                                          FROM eZForm_PageElementDict WHERE PageID='$pageID'" );
                
                $newOrder =& $qry[$db->fieldName( "Placement" )];
                
                $db->query_single( $qry, "SELECT ElementID FROM eZForm_PageElementDict
                                          WHERE PageID='$pageID' AND Placement='$newOrder'" );
                
                $oldElementID =& $qry[$db->fieldName( "ElementID" )];
            }
            else
            {
                $db->query_single( $qry, "SELECT PageID, ElementID, Placement FROM eZForm_PageElementDict
                                          WHERE Placement < '$elementPlacement' AND PageID='$pageID'
                                          ORDER BY Placement DESC",
                                          array( "Limit" => 1, "Offset" => 0 ) );

                $newOrder = $qry[$db->fieldName( "Placement" )];
                $oldElementID = $qry[$db->fieldName( "ElementID" )];
            }

            $res[] = $db->query( "UPDATE eZForm_PageElementDict SET Placement='$newOrder'
                                 WHERE ElementID='$elementID' AND PageID='$pageID'" );
                
            $res[] = $db->query( "UPDATE eZForm_PageElementDict SET Placement='$elementPlacement'
                                 WHERE ElementID='$oldElementID' AND PageID='$pageID'" );
            eZDB::finish( $res, $db );
        }
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveElementDown( $object )
    {
        if ( get_class( $object ) == "ezformelement" )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            
            $pageID = $this->id();
            $elementID = $object->id();
            
            $db->query_single( $qry, "SELECT Placement FROM eZForm_PageElementDict
                                      WHERE ElementID = '$elementID' AND PageID = '$pageID' ORDER BY Placement DESC",
                                      array( "Limit" => 1, "Offset" => 0 ) );

            $elementPlacement = $qry[$db->fieldName( "Placement" )];

            $db->query_single( $qry, "SELECT max($db->fieldName( \"Placement\" )) as Placement
                                      FROM eZForm_PageElementDict WHERE PageID='$pageID'" );
            
            $max =& $qry[$db->fieldName( "Placement" )];

            if ( $max == $elementPlacement )
            {
                $db->query_single( $qry, "SELECT min($db->fieldName( \"Placement\" ) ) as Placement
                                          FROM eZForm_PageElementDict WHERE PageID='$pageID'" );
                
                $newOrder =& $qry[$db->fieldName( "Placement" )];
                
                $db->query_single( $qry, "SELECT ElementID FROM eZForm_PageElementDict
                                          WHERE PageID='$pageID' AND Placement='$newOrder'" );
                
                $oldElementID =& $qry[$db->fieldName( "ElementID" )];
            }
            else
            {
                $db->query_single( $qry, "SELECT PageID, ElementID, Placement FROM eZForm_PageElementDict
                                          WHERE Placement > '$elementPlacement' AND PageID='$pageID' ORDER BY Placement",
                                          array( "Limit" => 1, "Offset" => 0) );

                $newOrder = $qry[$db->fieldName( "Placement" )];
                $oldElementID = $qry[$db->fieldName( "ElementID" )];
            }

            $res[] = $db->query( "UPDATE eZForm_PageElementDict SET Placement='$newOrder'
                                  WHERE ElementID='$elementID' AND PageID='$pageID'" );
            
            $res[] = $db->query( "UPDATE eZForm_PageElementDict SET Placement='$elementPlacement'
                                  WHERE ElementID='$oldElementID' AND PageID='$pageID'" );
            
            eZDB::finish( $res, $db );
        }
    }

    

    /*!
      Set the name for the page.
    */
    function &setName( $value )
    {
        $this->Name = $value;
    }

    
    /*!
      Set the form id the page.
    */
    function setFormID( $value )
    {
        $this->FormID = $value;
    }

    /*!
      Return the form id for the page.
    */
    function formID()
    {
        return $this->FormID;
    }
    
    /*!
      Return the id for the formpage.
    */
    function id()
    {
        return $this->ID;
    }


    /*!
      Return the name for the formpage.
    */
    function &name()
    {
        return $this->Name;
    }
    
    var $ID;
    var $Name;
    var $FormID;
    var $Placement;
} 

?>
