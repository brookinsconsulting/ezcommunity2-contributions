<?
/*!
  $Id: linkedit.php,v 1.27 2000/10/19 14:03:25 ce-cvs Exp $

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
$error = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );

include_once( "../classes/eztemplate.php" );
include_once( "../common/ezphputils.php" );

include( "ezlink/classes/ezlinkgroup.php" );
include( "ezlink/classes/ezlink.php" );
include( "ezlink/classes/ezhit.php" );

require( "ezuser/admin/admincheck.php" );

// Update a link.
if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkModify" ) )
    {
        if ( $Title != "" &&
        $Description != "" &&
        $LinkGroupID != "" &&
        $Keywords != "" &&
        $Accepted != "" &&
        $Url != "" )
        {
            $link = new eZLink();
            $link->get( $LinkID );
            
            $link->setTitle( $Title );
            $link->setDescription( $Description );
            $link->setLinkGroupID( $LinkGroupID );
            $link->setKeyWords( $Keywords );
            $link->setAccepted( $Accepted );
            $link->setUrl( $Url );
            
            $link->update();
            
            Header( "Location: /link/group/$LinkGroupID" );
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

// Delete a link.
if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkDelete" ) )
    {

        $deletelink = new eZLink();
        $deletelink->get( $LinkID );
        $deletelink->delete();
        
        Header( "Location: /link/group/$LinkGroupID" );
        exit();
        
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
}


// Insert a link.
if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkAdd") )
    {
        if ( $Title != "" &&
        $Description != "" &&
        $LinkGroupID != "" &&
        $Keywords != "" &&
        $Accepted != "" &&
        $Url != "" )
        {
            $link = new eZLink();
            
            $link->setTitle( $Title );
            $link->setDescription( $Description );
            $link->setLinkGroupID( $LinkGroupID );
            $link->setKeyWords( $Keywords );
            $link->setAccepted( $Accepted );
            $link->setUrl( $Url );

            $ttile = "";
            $turl = "";
            $tkeywords = "";
            $tdescription = "";
    
            $message = "Legg til ny link";
            $submit = "Legg til";
            $link->store();
            
            Header( "Location: /link/group/$LinkGroupID" );
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

// Sette template filer.

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
$DOC_ROOT . "/admin/" . "/intl", $Language, "linkedit.php" );
$t->setAllStrings();

$t->setAllStrings();

$t->set_file( array(
    "link_edit" => "linkedit.tpl"
    ));

$t->set_block( "link_edit", "link_group_tpl", "link_group" );

$ini = new INIFIle( "ezlink/admin/intl/" . $Language . "/linkedit.php.ini", false );
$headline = $ini->read_var( "strings", "headline_insert" );

$linkselect = new eZLinkGroup();
$linkGroupList = $linkselect->getAll();

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
    $editlink->get( $LinkID );

    $title = $editlink->Title;

    $LinkGroupID = $editlink->linkGroupID();

    $title = $editlink->title();
    $description = $editlink->description();
    $linkgroup = $editlink->linkGroupID();
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
    
    $ini = new INIFIle( "ezlink/admin/intl/" . $Language . "/linkedit.php.ini", false );
    $headline =  $ini->read_var( "strings", "headline_edit" );

}
    
// Selector
$link_select_dict = "";

foreach( $linkGroupList as $linkGroupItem )
{
    $t->set_var("link_group_id", $linkGroupItem->id() );
    $t->set_var("link_group_title", $linkGroupItem->title() );

    if ( $LinkGroupID == $linkGroupItem->id() )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $link_select_dict[ $linkGroupItem->id() ] = $i;

    $t->parse( "link_group", "link_group_tpl", true );
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

$t->set_var( "headline", $headline );

$t->set_var( "error_msg", $error_msg );
$t->set_var( "document_root", $DOC_ROOT );

$t->set_var( "link_id", $LinkID );
$t->pparse( "output", "link_edit" );

?>
