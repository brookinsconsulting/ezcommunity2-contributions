<?php
// 
// $Id: ezlinkitem.php,v 1.3 2001/05/03 16:54:21 jb Exp $
//
// Definition of eZLinkItem class
//
// Jan Borsodi <jb@ez.no>
// Created on: <19-Mar-2001 13:15:16 amos>
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
//! The class eZLinkItem does
/*!

*/

class eZLinkItem
{
    function eZLinkItem( $id, $module )
    {
        $this->Module = $module;
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( is_numeric( $id ) )
        {
            $this->get( $id );
        }
    }

    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $table_name = $this->Module . "_Link";
        $db->query_single( $row, "SELECT ID, Name, URL, Placement, ModuleType FROM $table_name WHERE ID='$id'" );
        $this->fill( $row );
    }

    function store()
    {
        $table_name = $this->Module . "_Link";
        $db =& eZDB::globalDatabase();
        if ( is_numeric( $this->ID ) and $this->ID > 0 )
        {
            $qry_text = "UPDATE $table_name";
            $qry_where = "WHERE ID='$this->ID'";
        }
        else
        {
            $qry_text = "INSERT INTO $table_name";
            $db->array_query( $qry_array, "SELECT Placement FROM $table_name
                                           WHERE SectionID='$this->Section'
                                           ORDER BY Placement DESC LIMIT 1", 0, 1 );
            $this->Placement = count( $qry_array ) == 1 ? $qry_array[0]["Placement"] + 1 : 1;
        }
        $db->query( "$qry_text
                     SET SectionID='$this->Section',
                         Name='$this->Name',
                         URL='$this->URL',
                         Placement='$this->Placement',
                         ModuleType='$this->ModuleType' $qry_where" );
        $this->ID = $db->insertID();
    }

    function delete( $id = false, $module = false )
    {
        if ( !$id )
            $id = $this->ID;
        if ( !$module )
            $module = $this->Module;
        $table_name = $module . "_Link";
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM $table_name WHERE ID='$id'" );
    }

    function fill( $row )
    {
        $this->ID = $row["ID"];
        $this->Name = $row["Name"];
        $this->URL = $row["URL"];
        $this->Placement = $row["Placement"];
        $this->ModuleType = $row["ModuleType"];
    }

    function setSection( $section )
    {
        $this->Section = $section;
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    function setURL( $url )
    {
        $this->URL = $url;
    }

    function id()
    {
        return $this->ID;
    }

    function section()
    {
        return $this->Section;
    }

    function name()
    {
        return $this->Name;
    }

    function url()
    {
        return $this->URL;
    }

    function type( $as_name = false )
    {
        if ( !$as_name )
        {
            return $this->ModuleType;
        }
        else
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $row, "SELECT Module, Type FROM eZModule_LinkModuleType
                                      WHERE ID='$this->ModuleType'" );
            return $row;
        }
    }

    function setType( $module, $type )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $rows, "SELECT ID FROM eZModule_LinkModuleType
                                  WHERE Module='$module' AND Type='$type'", 0, 1, "ID" );
        if ( count( $rows ) == 0 )
        {
            $db->query( "INSERT INTO eZModule_LinkModuleType
                         SET Module='$module', Type='$type'" );
            $this->ModuleType = $db->insertID();
        }
        else
        {
            $this->ModuleType = $rows[0];
        }
    }

    function moveUp( $sectionid, $id, $module )
    {
        $db =& eZDB::globalDatabase();
        $table_name = $module . "_Link";
        $db->query_single( $placement, "SELECT Placement FROM $table_name
                                        WHERE ID='$id'", "Placement" );
        $db->array_query( $items, "SELECT ID, Placement
                                   FROM $table_name
                                   WHERE SectionID='$sectionid' AND Placement<'$placement'
                                   ORDER BY Placement DESC LIMIT 1", 0, 1 );
        if ( count( $items ) == 0 )
        {
            $db->array_query( $items, "SELECT Placement
                                       FROM $table_name
                                       WHERE SectionID='$sectionid' AND ID!='$id'
                                       ORDER BY Placement DESC LIMIT 1", 0, 1 );
            $swap_placement = $items[0]["Placement"] + 1;
            $db->query( "UPDATE $table_name
                         SET Placement=$swap_placement WHERE ID=$id" );
        }
        else
        {
            $swap_id = $items[0]["ID"];
            $swap_placement = $items[0]["Placement"];
            $db->query( "UPDATE $table_name
                         SET Placement=$swap_placement WHERE ID=$id" );
            $db->query( "UPDATE $table_name
                         SET Placement=$placement WHERE ID=$swap_id" );
        }
    }

    function moveDown( $sectionid, $id, $module )
    {
        $db =& eZDB::globalDatabase();
        $table_name = $module . "_Link";
        $db->query_single( $placement, "SELECT Placement FROM $table_name
                                        WHERE ID='$id'", "Placement" );
        $db->array_query( $items, "SELECT ID, Placement
                                   FROM $table_name
                                   WHERE SectionID='$sectionid' AND Placement>'$placement'
                                   ORDER BY Placement ASC LIMIT 1", 0, 1 );
        if ( count( $items ) == 0 )
        {
            $db->array_query( $items, "SELECT Placement
                                       FROM $table_name
                                       WHERE SectionID='$sectionid' AND ID!='$id'
                                       ORDER BY Placement ASC LIMIT 1", 0, 1 );
            $swap_placement = $items[0]["Placement"] - 1;
            $db->query( "UPDATE $table_name
                         SET Placement=$swap_placement WHERE ID=$id" );
        }
        else
        {
            $swap_id = $items[0]["ID"];
            $swap_placement = $items[0]["Placement"];
            $db->query( "UPDATE $table_name
                         SET Placement=$swap_placement WHERE ID=$id" );
            $db->query( "UPDATE $table_name
                         SET Placement=$placement WHERE ID=$swap_id" );
        }
    }

    var $ID;
    var $Section;
    var $Name;
    var $URL;
    var $Placement;
    var $Module;
    var $ModuleType;
}

?>
