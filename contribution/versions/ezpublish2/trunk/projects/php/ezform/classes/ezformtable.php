<?php
//
// $Id: ezformtable.php,v 1.2 2001/12/13 08:59:30 jhe Exp $
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
        $this->ID =& $tableArray[$db->fieldName( "ElementID" )];
        $this->Cols =& $tableArray[$db->fieldName( "Cols" )];
        $this->Rows =& $tableArray[$db->fieldName( "Rows" )];
    }

    function id()
    {
        return $this->ID;
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
    var $Cols;
    var $Rows;
}

?>
