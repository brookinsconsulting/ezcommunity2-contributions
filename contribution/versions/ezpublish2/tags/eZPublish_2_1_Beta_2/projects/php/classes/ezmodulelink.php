<?php
// 
// $Id: ezmodulelink.php,v 1.1 2001/03/21 13:38:56 jb Exp $
//
// Definition of eZModuleLink class
//
// Jan Borsodi <jb@ez.no>
// Created on: <16-Mar-2001 18:51:22 amos>
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
//! The class eZModuleLink does
/*!

*/

class eZModuleLink
{
    function eZModuleLink( $module, $type, $id )
    {
        $this->Module = $module;
        $this->Type = $type;
        $this->ID = $id;
    }

    function &sections( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $table_name = $this->Module . "_LinkSection";
        $link_table_name = $this->Module . "_$this->Type" . "SectionDict";
        $type_column = $this->Type . "ID";
        $db->array_query( $qry_array,
                          "SELECT Link.SectionID FROM $link_table_name AS Link, $table_name AS Section
                           WHERE Link.SectionID=Section.ID AND Link.$type_column=$this->ID
                           ORDER BY Link.Placement ASC" );
        $ret_array = array();
        foreach( $qry_array as $row )
        {
            $id = $row["SectionID"];
            $ret_array[] = $as_object ? new eZLinkSection( $id, $this->Module ) : $id;
        }
        return $ret_array;
    }

    function sectionCount()
    {
        $db =& eZDB::globalDatabase();
        $table_name = $this->Module . "_LinkSection";
        $link_table_name = $this->Module . "_$this->Type" . "SectionDict";
        $type_column = $this->Type . "ID";
        $db->query_single( $row,
                           "SELECT count( Link.SectionID ) AS Count FROM $link_table_name AS Link, $table_name AS Section
                            WHERE Link.SectionID=Section.ID AND Link.$type_column=$this->ID" );
        return $row["Count"];
    }

    function addSection( $section )
    {
        $db =& eZDB::globalDatabase();
        $link_table_name = $this->Module . "_$this->Type" . "SectionDict";
        $section_id = $section->id();
        $type_column = $this->Type . "ID";
        $db->array_query( $qry_array,
                          "SELECT Placement FROM $link_table_name
                           WHERE $type_column='$this->ID'
                           ORDER BY Placement DESC LIMIT 1", 0, 1 );
        $placement = count( $qry_array ) == 1 ? $qry_array[0]["Placement"] + 1 : 1;
        $db->query( "INSERT INTO $link_table_name
                     SET $type_column='$this->ID',
                         SectionID='$section_id',
                         Placement='$placement'" );
    }

    function removeSection( $section )
    {
        if ( get_class( $section ) == "ezlinksection" )
            $id = $section->id();
        else
            $id = $section;
        $db =& eZDB::globalDatabase();
        $link_table_name = $this->Module . "_$this->Type" . "SectionDict";
        $db->query( "DELETE FROM $link_table_name
                     WHERE SectionID='$id'" );
    }

    var $Module;
    var $Type;
    var $ID;
}

?>
