<?
/*!
    $Id: groupedit.php,v 1.24 2000/10/19 14:03:25 ce-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: 
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

/*
  groupedit.php 
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );
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
    if ( eZPermission::checkPermission( $user, "eZLink", "GroupAdd" ) )
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
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
}

// Delete a group.
if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "GroupDelete" ) )
    {
        $group = new eZLinkGroup();
        $group->get( $LinkGroupID );
        $group->delete();

        Header( "Location: /link/group/" );
        exit();
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
}

// Update a group.
if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "GroupModify" ) )
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
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
}

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
$DOC_ROOT . "/admin/" . "/intl/", $Language, "groupedit.php" );
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
    $message = "Legg til linkkategori";
    $submit = "Legg til";
    $action = "insert";
}
if ( $Action == "update" )
{
    $action = "update";
}

// Modifing a group.
if ( $Action == "edit" )
{
    $editlinkgroup = new eZLinkGroup();
    $editlinkgroup->get ( $LinkGroupID );

    $title = $editlinkgroup->title();
    
    $action = "update";
    $message = "Rediger linkkategori";
    $submit = "Rediger";
    $ttitle = $editlinkgroup->title();

    $ini = new INIFIle( "ezlink/admin/intl/" . $Language . "/groupedit.php.ini", false );
    $headline = $ini->read_var( "strings", "headline_edit" );
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

$t->set_var( "document_root", $DOC_ROOT );

$t->set_var( "linkgroup_id", $LinkGroupID );
$t->pparse( "output", "group_edit" );
?>
