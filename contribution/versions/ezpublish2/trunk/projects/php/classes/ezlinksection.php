<?php
// 
// $Id: ezlinksection.php,v 1.2 2001/05/03 14:27:07 jb Exp $
//
// Definition of eZLinkSection class
//
// Jan Borsodi <jb@ez.no>
// Created on: <16-Mar-2001 19:25:51 amos>
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

//!! 
//! The class eZLinkSection does
/*!

*/

include_once( "classes/ezlinkitem.php" );

class eZLinkSection
{
    function eZLinkSection( $id, $module )
    {
        $this->Module = $module;
        $this->Type = $type;
        $this->ID = $id;
        if ( is_numeric( $id ) )
            $this->get( $id );
    }

    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $table_name = $this->Module . "_LinkSection";
        $db->query_single( $row, "SELECT Name FROM $table_name WHERE ID='$id'" );
        $this->fill( $row );
    }

    function store()
    {
        $db =& eZDB::globalDatabase();
        $table_name = $this->Module . "_LinkSection";
        if ( is_numeric( $this->ID ) and $this->ID > 0 )
        {
            $qry_text = "UPDATE $table_name";
            $qry_where = "WHERE ID='$this->ID'";
        }
        else
        {
            $qry_text = "INSERT INTO $table_name";
        }
        $db->query( "$qry_text
                     SET Name='$this->Name' $qry_where" );
        $this->ID = $db->insertID();
    }

    function delete( $id = false, $module = false )
    {
        if ( !$id )
            $id = $this->ID;
        if ( !$module )
            $module = $this->Module;
        $db =& eZDB::globalDatabase();
        $table_name = $module . "_Link";
        $db->query( "DELETE FROM $table_name WHERE SectionID='$id'" );
        $table_name = $module . "_LinkSection";
        $db->query( "DELETE FROM $table_name WHERE ID='$id'" );
    }

    function fill( $row )
    {
        $this->Name = $row["Name"];
    }

    function &links()
    {
        $db =& eZDB::globalDatabase();
        $table_name = $this->Module . "_Link";
        $db->array_query( $qry_array, "SELECT ID, Name, URL, Placement, ModuleType FROM $table_name
                                       WHERE SectionID='$this->ID'" );
        $ret = array();
        foreach( $qry_array as $row )
        {
            $ret[] = new eZLinkItem( $row, $this->Module );
        }
        return $ret;
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    function id()
    {
        return $this->ID;
    }

    function name()
    {
        return $this->Name;
    }

    var $Module;
    var $ID;
    var $Name;
}

?>
