<?
/*!
  $Id: linkedit.php,v 1.23 2000/10/09 14:15:08 ce-cvs Exp $

  Author: Christoffer A. Elo <ce@ez.no>
    
  Created on: 
    
  Copyright (C) 2000 eZ systems. All rights reserved.
*/

/*
  linkedit.php - Redigerer en link.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );
$Language = $ini->read_var( "eZLinkMain", "Language" );

include_once( "../classes/eztemplate.php" );
include_once( "../common/ezphputils.php" );

include( "ezlink/classes/ezlinkgroup.php" );
include( "ezlink/classes/ezlink.php" );
include( "ezlink/classes/ezhit.php" );

require( "ezuser/admin/admincheck.php" );

// Oppdatere
if ( $Action == "update" )
{
    $updatelink = new eZLink();

    $updatelink->get( $LID );

    $updatelink->setTitle( $title );
    $updatelink->setDescription( $description );
    $updatelink->setLinkGroup( $linkgroup );
    $updatelink->setKeyWords( $keywords );
    $updatelink->setAccepted( $accepted );
    $updatelink->setUrl( $url );
    
    $updatelink->update();

    Header( "Location: /link/group/" . $linkgroup );
}

// Slette link
if ( $Action == "delete" )
{
    $deletelink = new eZLink();
    $deletelink->get( $LID );
    $deletelink->delete();

    if ( $LGID == incoming )
    {
        Header( "Location: /link/group/incoming" );
    }
    else
    {
        Header( "Location: /link/group/" );
    }
}

// Legge til link
if ( $Action == "insert" )
{
    $newlink = new eZLink();

    $newlink->setTitle( $title );
    $newlink->setDescription( $description );
    $newlink->setLinkGroup( $linkgroup );
    $newlink->setKeyWords( $keywords );
    $newlink->setAccepted( $accepted );
    $newlink->setUrl( $url );


    $ttile = "";
    $turl = "";
    $tkeywords = "";
    $tdescription = "";
    
    $message = "Legg til ny link";
    $submit = "Legg til";
//    print ( "akseptert: " . $accepted );
    $newlink->store();

    Header( "Location: index.php?page=../ezlink/admin/linklist.php" );
}

// Sette template filer.

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZLinkMain", "TemplateDir" ). "/linkedit/",
$DOC_ROOT . "/admin/" . "/intl", $Language, "linkedit.php" );
$t->setAllStrings();

$t->setAllStrings();

$t->set_file( array(
    "link_edit" => "linkedit.tpl",
    "link_group_select" => "linkgroupselect.tpl"
    ));


$linkselect = new eZLinkGroup();
$link_array = $linkselect->getAll();

// Template variabler
$message = "Legg til link";
$submit = "Legg til";
$action = "insert";

// setter akseptert link som default.
$yes_selected = "selected";
$no_selected = "";

// editere
if ( $Action == "edit" )
{

    $editlink = new eZLink();
    $editlink->get( $LID );

    $title = $editlink->Title;

    $LGID = $editlink->linkGroup();

    $title = $editlink->title();
    $description = $editlink->description();
    $linkgroup = $editlink->linkGroup();
    $keywords = $editlink->keyWords();
    $accepted = $editlink->accepted();
    $url = $editlink->url();

    $action = "update";
    $message = "Rediger link";
    $submit = "Rediger";
              
    $ttile = $editlink->title();
    $tdescription = $editlink->description();
    $tkeywords = $editlink->keywords();
    $turl = $editlink->url();

    if ( $editlink->accepted() == "Y" )
    {
        $yes_selected = "selected";
        $no_selected = "";

    }
    else
    {
        $yes_selected = "";
        $no_selected = "selected";
    }

}
    
// Selector
$link_select_dict = "";

for ( $i=0; $i<count( $link_array ); $i++ )
{
    $t->set_var("link_id", $link_array[ $i ][ "ID" ] );
    $t->set_var("link_title", $link_array[ $i ][ "Title" ] );

    if ( $LGID == $link_array[ $i ][ "ID" ] )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $link_select_dict[ $link_array[ $i ][ "ID" ] ] = $i;

    $t->parse( "group_category", "link_group_select", true );
}


$t->set_var( "yes_selected", $yes_selected );
$t->set_var( "no_selected", $no_selected );

$t->set_var( "submit_text", $submit );
$t->set_var( "action_value", $action );
$t->set_var( "message", $message );


$t->set_var( "title", $ttile );
$t->set_var( "url", $turl );
$t->set_var( "keywords", $tkeywords );
$t->set_var( "description", $tdescription );
// $t->set_var( "accepted", $taccepted );


$t->set_var( "document_root", $DOC_ROOT );

$t->set_var( "link_id", $LID );
$t->pparse( "output", "link_edit" );

?>
