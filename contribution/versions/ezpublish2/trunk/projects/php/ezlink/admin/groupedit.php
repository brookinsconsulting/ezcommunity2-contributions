<?
//
// $Id: groupedit.php,v 1.30 2000/11/01 08:16:43 ce-cvs Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Oct-2000 14:57:28 ce>
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


/*
  groupedit.php 
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZLinkMain", "Language" );
$error = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );

include_once( "classes/eztemplate.php" );
include( "ezlink/classes/ezlinkgroup.php" );
include( "ezlink/classes/ezlink.php" );
include( "ezlink/classes/ezhit.php" );

require( "ezuser/admin/admincheck.php" );

// Insert a group.
if ( $Action == "insert" )
{
    unlink( "ezlink/cache/menubox.cache" );
    
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkGroupAdd" ) )
    {
        if ( $Title != "" &&
        $ParentCategory != "" )
        {
            $group = new eZLinkGroup();
            
            $group->setTitle( $Title );
            $group->setParent( $ParentCategory );
            $ttile = "";
            $group->store();
            Header( "Location: /link/group/". $ParentCategory );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );Header( "Location: /link/norights" );
    }
}

// Delete a group.
if ( $Action == "delete" )
{
    unlink( "ezlink/cache/menubox.cache" );

    if ( eZPermission::checkPermission( $user, "eZLink", "LinkGroupDelete" ) )
    {
        $group = new eZLinkGroup();
        $group->get( $LinkGroupID );
        $group->delete();

        Header( "Location: /link/group/" );
        exit();
    }
    else
    {
        Header( "Location: /link/norights" );
    }
}

// Update a group.
if ( $Action == "update" )
{
    unlink( "ezlink/cache/menubox.cache" );
    
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkGroupModify" ) )
    {
        if ( $Title != "" &&
        $ParentCategory != "" )
        {
            $group = new eZLinkGroup();
            $group->get ( $LinkGroupID );
            $group->setTitle ( $Title );
            $group->update();
            Header( "Location: /link/group/" );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        Header( "Location: /link/norights" );
    }
}

$t = new eZTemplate( "ezlink/admin/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
 "ezlink/admin/" . "/intl/", $Language, "groupedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "group_edit" => "groupedit.tpl"
    ));

$ini = new INIFIle( "ezlink/admin/intl/" . $Language . "/groupedit.php.ini", false );
$headline = $ini->read_var( "strings", "headline_insert" );

$t->set_block( "group_edit", "parent_category_tpl", "parent_category" );

$groupselect = new eZLinkGroup();
$groupLinkList = $groupselect->getAll( );

if ( $Action == "new" )
{
    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkGroupAdd" ) )
    {
        Header( "Location: /link/norights" );
    }

    $action = "insert";
}

// Modifing a group.
if ( $Action == "edit" )
{
    $ini = new INIFIle( "ezlink/admin/intl/" . $Language . "/groupedit.php.ini", false );
    $headline = $ini->read_var( "strings", "headline_edit" );

    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkGroupModify" ) )
    {
        Header( "Location: /link/norights" );
    }
    else
    {

    $editlinkgroup = new eZLinkGroup();
    $editlinkgroup->get ( $LinkGroupID );

    $title = $editlinkgroup->title();
    
    $action = "update";
    $message = "Rediger linkkategori";
    $submit = "Rediger";
    $ttitle = $editlinkgroup->title();
    }

}


// Selecter
$group_select_dict = "";
foreach( $groupLinkList as $groupLinkItem )
{
    $t->set_var( "grouplink_id", $groupLinkItem->id() );
    $t->set_var( "grouplink_title", $groupLinkItem->title() );
    $t->set_var( "grouplink_parent", $groupLinkItem->parent() );

    if ( $GroupLink == $groupLinkItem->id() )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $group_select_dict[ $groupLinkItem->id() ] = $i;

    $t->parse( "parent_category", "parent_category_tpl", true );
}

$t->set_var( "submit_text", $submit );
$t->set_var( "action_value", $action );

$t->set_var( "headline", $headline );

$t->set_var( "title", $ttitle );
$t->set_var( "error_msg", $error_msg );

$t->set_var( "linkgroup_id", $LinkGroupID );
$t->pparse( "output", "group_edit" );
?>
