<?php
// 
// $Id: ezformpage.php,v 1.2 2001/12/17 09:42:45 br Exp $
//
// Definition of ||| class
//
// <Bjørn Reiten> <br@ez.no>
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
       
class eZFormPage
{
    /*!
      Constructs a new eZFormPage object.
    */
    function eZFormPage( $id = -1 )
    {
        if ( $id != -1 )
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
            
            $db->query_single( $qry, "SELECT MAX( PageNumber ) as PageNumber FROM eZForm_FormPage
                                      WHERE FormID='$this->FormID'" );
            $pageNumber = $qry[$db->fieldName( "PageNumber" )] + 1;
            
            $nextID = $db->nextID( "eZForm_FormPage", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormPage
                          ( ID,
                            Name,
                            PageNumber,
                            FormID,
                            Placement )
                          VALUES
                          ( '$nextID',
                            '$name',
                            '$pageNumber',
                            '$this->FormID',
                            '$placement' )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res[] = $db->query( "UPDATE eZForm_Form SET
                                    Name='$name',
                                    PageNumber='$this->PageNumber',
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
        $this->PageNumber =& $pageArray[$db->fieldName( "PageNumber" )];
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
      Set the name for the page.
    */
    function setName( $value )
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
    var $PageNumber;
    var $FormID;
    var $Placement;
} 

?>
