<?
/*!
    $Id: groupedit.php,v 1.21 2000/10/11 12:02:03 ce-cvs Exp $

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

include_once( "classes/eztemplate.php" );

include( "ezlink/classes/ezlinkgroup.php" );
include( "ezlink/classes/ezlink.php" );
include( "ezlink/classes/ezhit.php" );

$Language = $ini->read_var( "eZLinkMain", "Language" );

require( "ezuser/admin/admincheck.php" );

// Slett
if ( $Action == "delete" )
{
    $deletelinkgroup = new eZLinkGroup();
    $deletelinkgroup->get( $LGID );
    $deletelinkgroup->delete();

    Header( "Location: /link/group/" );
}

// Legg til gruppe
if ( $Action == "insert" )
{
    $addlinkgroup = new eZLinkGroup();

    $addlinkgroup->setTitle( $title );
    $addlinkgroup->setParent( $ParentCategory );
    $ttile = "";
    $addlinkgroup->store();
    $message = "Legg til gruppe";
    $submit = "Legg til";
    Header( "Location: /link/group/". $ParentCategory );
}

// Oppdatere
if ( $Action == "update" )
{
    $updatelinkgroup = new eZLinkGroup();
    $updatelinkgroup->get ( $LGID );
    $updatelinkgroup->setTitle ( $title );
    $updatelinkgroup->update();
    Header( "Location: /link/group/" );    
}

// Sette template filer
$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZLinkMain", "TemplateDir" ). "/groupedit",
$DOC_ROOT . "/admin/" . "/intl/", $Language, "groupedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "group_edit" => "groupedit.tpl"
    ));

$t->set_block( "group_edit", "parent_category_tpl", "parent_category" );

$groupselect = new eZLinkGroup();
$grouplink_array = $groupselect->getAll( );

    $message = "Legg til linkkategori";
    $submit = "Legg til";
    $action = "insert";

// Redigering av gruppe
if ( $Action == "edit" )
{
    $editlinkgroup = new eZLinkGroup();
    $editlinkgroup->get ( $LGID );

    $title = $editlinkgroup->title();
    
    $action = "update";
    $message = "Rediger linkkategori";
    $submit = "Rediger";

    $ttitle = $editlinkgroup->title();
}


// Selecter
$group_select_dict = "";
for ( $i=0; $i<count( $grouplink_array ); $i++ )
{
    $t->set_var( "grouplink_id", $grouplink_array[ $i ][ "ID" ] );
    $t->set_var( "grouplink_title", $grouplink_array[ $i ][ "Title" ] );
    $t->set_var( "grouplink_parent", $grouplink_array[ $i ][ "Parent" ] );

    if ( $GroupLink == $grouplink_array[ $i ][ "ID" ] )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $group_select_dict[ $grouplink_array[$i][ "ID" ] ] = $i;

    $t->parse( "parent_category", "parent_category_tpl", true );
}

$t->set_var( "submit_text", $submit );
$t->set_var( "action_value", $action );
$t->set_var( "message", $message );

$t->set_var( "title", $ttitle );

$t->set_var( "document_root", $DOC_ROOT );

$t->set_var( "linkgroup_id", $LGID );
$t->pparse( "output", "group_edit" );
?>
