<?php
//
// $Id: ezformreport.php,v 1.1 2002/01/18 14:05:57 jhe Exp $
//
// Definition of eZFormReport class
//
// Created on: <17-Jan-2002 11:29:04 jhe>
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
//! The class eZFormReport does
/*!

*/

include_once( "classes/ezdb.php" );
include_once( "ezform/classes/ezform.php" );

class eZFormReport
{
    function eZFormReport( $id = -1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id > -1 && $id != "" )
        {
            $this->get( $id );
        }
    }

    function get( $id )
    {
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT * FROM eZForm_FormReport WHERE ID='$id'" );
        $this->fill( $res );
    }

    function fill( $value )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $value[$db->fieldName( "ID" )];
        $this->FormID =& $value[$db->fieldName( "FormID" )];
        $this->Name =& $value[$db->fieldName( "Name" )];
    }

    function store()
    {
        $db =& eZDB::globalDatabase();
        $name = $db->escapeString( $this->Name );
        if ( $this->ID == "" )
        {
            $db->lock( "eZForm_FormReport" );
            $this->ID = $db->nextID( "eZForm_FormReport", "ID" );
            $db->query( "INSERT INTO eZForm_FormReport
                         (ID, FormID, Name)
                         VALUES
                         ('$this->ID','$this->FormID','$name')" );
            $db->unlock();
        }
        else
        {
            $db->query( "UPDATE eZForm_FormReport SET
                         FormID='$this->FormID',
                         Name='$name'
                         where ID='$this->ID'" );
        }
    }

    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id == -1 )
            $id = $this->ID;

        $res = array();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZForm_FormReport WHERE ID='$id'" );
        eZDB::finish( $res, $db );
    }

    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        $res = array();
        $returnArray = array();

        $db->array_query( $res, "SELECT * FROM eZForm_FormReport" );
        foreach ( $res as $rep )
        {
            $returnArray[] = new eZFormReport( $rep );
        }
        return $returnArray;
    }
    
    function id()
    {
        return $this->ID;
    }

    function form( $as_object = true )
    {
        if ( $as_object )
            return new eZForm( $this->FormID );
        else
            return $this->FormID;
    }

    function setForm( $value )
    {
        if ( get_class( $value ) == "ezform" )
            $this->FormID = $value->id();
        else if ( is_numeric( $value ) )
            $this->FormID = $value;
    }

    function name()
    {
        return $this->Name;
    }

    function setName( $value )
    {
        $this->Name = $value;
    }
    
    var $ID;
    var $FormID;
    var $Name;
}

?>
