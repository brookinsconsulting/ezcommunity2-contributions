<?php
// 
// $Id: ezmodulelink.php,v 1.4 2001/07/09 07:18:20 jakobn Exp $
//
// Definition of eZModuleLink class
//
// Jan Borsodi <jb@ez.no>
// Created on: <16-Mar-2001 18:51:22 amos>
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

//!! eZCommon
//! The class eZModuleLink handles remote linkage of sections.
/*!
  It can link sections from a remote modules to a specific module and sub type.
  Each link can have zero or more link items, this makes it possible to create
  a list of related articles etc..

  The module linkage system assumes that some sql tables has been created for each module/type.
  The tables are:
  {Module}_{Type}SectionDict
  {Module}_LinkSection
  {Module}_Link
  where {Module} is the module name and {Type} is the type name which are sent to the constructor.

  The best thing is to use the linklist.php, linkselect.php and linkmove.php
  found in classes/admin, it has everything setup for simple usage and is properly
  generalized for simple reuse.

  \code
  // Sets a module link for eZTrade using Products and with product id 1
  $link = new eZModuleLink( "eZTrade", "Product", 1 );
  $sections =& $link->sections();
  foreach( $sections as $section )
  {
  // Do something with section
  }

  $section = new eZLinkSection( false, "eZTrade" );
  $section->setName( "Related links" );
  $section->store();
  $link->addSection( $section );
  \endcode

*/

class eZModuleLink
{
    /*!
      Initializes the object with the module name, the sub type and the id of the item.
    */
    function eZModuleLink( $module, $type, $id )
    {
        $this->Module = $module;
        $this->Type = $type;
        $this->ID = $id;
    }

    /*!
      Returns an array of sections belonging the current item.
      If $as_object is true it is returned with eZLinkSection objects as items,
      otherwise it is returned with the ID as item.
    */
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

    /*!
      Returns the number of sections the current item has.
    */
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

    /*!
      Adds a new section to the current item.
      $section must be a properly initialized eZLinkSection object.
    */
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

    /*!
      Adds a new section to the current item.
      $section must be a properly initialized eZLinkSection object and belong to the current item.
    */
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
