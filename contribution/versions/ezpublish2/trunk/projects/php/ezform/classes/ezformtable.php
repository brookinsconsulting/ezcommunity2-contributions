<?php
//
// $Id: ezformtable.php,v 1.6 2001/12/14 12:57:50 jhe Exp $
//
// Definition of eZFormTable class
//
// Created on: <12-Dec-2001 14:12:50 jhe>
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

//!! 
//! The class eZFormTable does
/*!

*/

class eZFormTable
{
    function eZFormTable( $id = -1 )
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

    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $tableArray, "SELECT * FROM eZForm_FormTable WHERE ElementID='$id'",
                              array( "Offset" => 0, "Limit" => 1 ) );
            if ( count( $tableArray ) == 1 )
            {
                $this->fill( &$tableArray[0] );
                $ret = true;
            }
            elseif ( count( $tableArray ) != 1 )
            {
                $this->ID = 0;
                $this->Rows = 0;
                $this->Cols = 0;
            }
        }
        return $ret;
    }

    function fill( &$tableArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $tableArray[$db->fieldName( "ID" )];
        $this->ElementID =& $tableArray[$db->fieldName( "ElementID" )];
        $this->Cols =& $tableArray[$db->fieldName( "Cols" )];
        $this->Rows =& $tableArray[$db->fieldName( "Rows" )];
    }

    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( $this->ID == 0 || !isSet( $this->ID ) )
        {
            $db->lock( "eZForm_FormTable" );
            $this->ID = $db->nextID( "eZForm_FormTable", "ID" );
            $res = $db->query( "INSERT INTO eZForm_FormTable
                                       (ID,
                                        ElementID,
                                        Cols,
                                        Rows)
                                VALUES ('$this->ID',
                                        '$this->ElementID',
                                        '$this->Cols',
                                        '$this->Rows')" );
            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZForm_FormTable SET
                                ElementID='$this->ElementID',
                                Cols='$this->Cols',
                                Rows='$this->Rows'
                                WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    function delete( $table = -1 )
    {
        if ( $table == -1 )
            $tableID = $table;
        else
            $tableID = $this->ID;

        $res = array();
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZForm_FormTable WHERE ElementID='$tableID'" );
        $res[] = $db->query( "DELETE FROM eZForm_FormElement WHERE Parent='$tableID'" );
        eZDB::finish( $res, $db );
    }

    function tableElements( $id = -1 )
    {
        if ( $id == -1 )
            $tableID = $this->ID;
        else
            $tableID = $id;

        $elementArray = array();
        $returnArray = array();
        
        $db =& eZDB::globalDatabase();
        $db->array_query( $elementArray, "SELECT ElementID FROM eZForm_FormTableElementDict
                                          WHERE TableID='$tableID'
                                          ORDER BY Placement" );

        foreach ( $elementArray as $element )
        {
            $returnArray[] = new eZFormElement( $element[$db->fieldName( "ElementID" )] );
        }
        return $returnArray;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp( $object )
    {
        if ( get_class( $object ) == "ezformelement" )
        {
            $db =& eZDB::globalDatabase();

            $tableID = $this->tableID();
            $elementID = $object->id();
            
            $db->query_single( $qry, "SELECT Placement FROM eZForm_FormTableElementDict
                                      WHERE ElementID='$elementID' AND TableID='$tableID'
                                      ORDER BY Placement DESC",
                                      array( "Limit" => 1, "Offset" => 0) );

            $elementPlacement = $qry[$db->fieldName( "Placement" )];

            $db->query_single( $qry, "SELECT min($db->fieldName( \"Placement\" )) as Placement
                                      FROM eZForm_FormTableElementDict WHERE TableID='$tableID'" );
            $min =& $qry[$db->fieldName( "Placement" )];

            if ( $min == $elementPlacement )
            {
                $db->query_single( $qry, "SELECT max($db->fieldName( \"Placement\" ) ) as Placement
                                          FROM eZForm_FormTableElementDict WHERE TableID='$tableID'" );
                
                $newOrder =& $qry[$db->fieldName( "Placement" )];
                
                $db->query_single( $qry, "SELECT ElementID FROM eZForm_FormTableElementDict
                                          WHERE TableID='$tableID' AND Placement='$newOrder'" );
                
                $oldElementID =& $qry[$db->fieldName( "ElementID" )];
            }
            else
            {
                $db->query_single( $qry, "SELECT TableID, ElementID, Placement FROM eZForm_FormTableElementDict
                                          WHERE Placement < '$elementPlacement' AND TableID='$tableID'
                                          ORDER BY Placement DESC",
                                          array( "Limit" => 1, "Offset" => 0 ) );

                $newOrder = $qry[$db->fieldName( "Placement" )];
                $oldElementID = $qry[$db->fieldName( "ElementID" )];
            }

            $res[] = $db->query( "UPDATE eZForm_FormTableElementDict SET Placement='$newOrder'
                                 WHERE ElementID='$elementID' AND TableID='$tableID'" );
                
            $res[] = $db->query( "UPDATE eZForm_FormTableElementDict SET Placement='$elementPlacement'
                                 WHERE ElementID='$oldElementID' AND TableID='$tableID'" );
            eZDB::finish( $res, $db );
        }
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown( $object )
    {
        if ( get_class( $object ) == "ezformelement" )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            
            $tableID = $this->tableID();
            $elementID = $object->id();
            
            $db->query_single( $qry, "SELECT Placement FROM eZForm_FormTableElementDict
                                      WHERE ElementID='$elementID' AND TableID='$tableID'
                                      ORDER BY Placement DESC",
                                      array( "Limit" => 1, "Offset" => 0 ) );

            $elementPlacement = $qry[$db->fieldName( "Placement" )];

            $db->query_single( $qry, "SELECT max($db->fieldName( \"Placement\" )) as Placement
                                      FROM eZForm_FormTableElementDict WHERE TableID='$tableID'" );
            
            $max =& $qry[$db->fieldName( "Placement" )];

            if ( $max == $elementPlacement )
            {
                $db->query_single( $qry, "SELECT min($db->fieldName( \"Placement\" ) ) as Placement
                                          FROM eZForm_FormTableElementDict WHERE TableID='$tableID'" );
                
                $newOrder =& $qry[$db->fieldName( "Placement" )];
                
                $db->query_single( $qry, "SELECT ElementID FROM eZForm_FormTableElementDict
                                          WHERE TableID='$tableID' AND Placement='$newOrder'" );
                
                $oldElementID =& $qry[$db->fieldName( "ElementID" )];
            }
            else
            {
                $db->query_single( $qry, "SELECT TableID, ElementID, Placement FROM eZForm_FormTableElementDict
                                          WHERE Placement > '$elementPlacement' AND TableID='$tableID'
                                          ORDER BY Placement",
                                          array( "Limit" => 1, "Offset" => 0) );

                $newOrder = $qry[$db->fieldName( "Placement" )];
                $oldElementID = $qry[$db->fieldName( "ElementID" )];
            }

            $res[] = $db->query( "UPDATE eZForm_FormTableElementDict SET Placement='$newOrder'
                                  WHERE ElementID='$elementID' AND TableID='$tableID'" );
            
            $res[] = $db->query( "UPDATE eZForm_FormTableElementDict SET Placement='$elementPlacement'
                                  WHERE ElementID='$oldElementID' AND TableID='$tableID'" );
            
            eZDB::finish( $res, $db );
        }
    }

    function addElement( &$object )
    {
        if ( get_class( $object ) == "ezformelement" )
        {
            $elementID = $object->id();
            $elementName = $object->name();
            $tableID = $this->ElementID;

            $db =& eZDB::globalDatabase();
            $db->begin();
            $db->query_single( $result, "SELECT MAX(Placement) as Placement
                                     FROM eZForm_FormTableElementDict WHERE TableID='$tableID'" );
            
            $placement = $result[$db->fieldName( "Placement" )];
            $placement++;
            $db->lock( "eZForm_FormTableElementDict" );
            $nextID = $db->nextID( "eZForm_FormTableElementDict", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormTableElementDict
                                   ( ID, Placement, ElementID, TableID, Name )
                                   VALUES
                                   ( '$nextID', '$placement', '$elementID', '$tableID', '$elementName' )" );
            eZDB::finish( $res, $db );
            return true;
        }
    }
    
    function id()
    {
        return $this->ID;
    }

    function elementID()
    {
        return $this->ElementID;
    }

    function setElementID( $value )
    {
        $this->ElementID = $value;
    }
    
    function cols()
    {
        return $this->Cols;
    }

    function setCols( $value )
    {
        $this->Cols = $value;
    }

    function rows()
    {
        return $this->Rows;
    }

    function setRows( $value )
    {
        $this->Rows = $value;
    }
    
    var $ID;
    var $ElementID;
    var $Cols;
    var $Rows;
}

?>
