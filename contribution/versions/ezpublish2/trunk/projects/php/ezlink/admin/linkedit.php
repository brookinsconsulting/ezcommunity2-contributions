<?
// $Id: linkedit.php,v 1.35 2000/11/02 09:54:34 bf-cvs Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Oct-2000 14:58:57 ce>
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
  linkedit.php - Redigerer en link.
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

include_once( "ezlink/classes/ezmeta.php" );

require( "ezuser/admin/admincheck.php" );

if ( $GetSite )
{
    if ( $Url )
    {
        if ( !preg_match( "%^([a-z]+://)%", $Url ) )
            $real_url = "http://" . $Url;
        else
            $real_url = $Url;

        $metaList = fetchURLInfo( $real_url );

        if ( $metaList == false )
        {
            // Change this to use an external message
            $terror_msg = "The site does not exists";
        }
        else if( count( $metaList ) == 0 )
        {
            $inierror = new INIFile( "ezlink/user/" . "/intl/" . $Language . "/suggestlink.php.ini", false );
            $terror_msg = $inierror->read_var( "strings", "nometa" );
        }

        if ( $metaList["description"] )
            $tdescription = $metaList["description"];
        else
            $tdescription = $description;
        if ( $metaList["keywords"] )
            $tkeywords = $metaList["keywords"];
        else
            $tkeywords = $keywords;
        if ( $metaList["title"] )
            $ttitle = $metaList["title"];
        else if ( $metaList["abstract"] )
            $ttitle = $metaList["abstract"];
        else
            $ttitle = $Title;
//          $tdescription = $metaList["description"];
//          $tkeywords = $metaList["keywords"];
//          $ttitle = $Title;
        $turl = $Url;

    }
    $Action = "";
}

// Update a link.
if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkModify" ) )
    {
        if ( $Title != "" &&
        $LinkGroupID != "" &&
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
        Header( "Location: /link/norights" );
    }
}

// Delete a link.
if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkDelete" ) )
    {
        $deletelink = new eZLink();
        $deletelink->get( $LinkID );
        $LinkGroupID = $deletelink->linkGroupID();
        $deletelink->delete();

        if ( !$LinkGroupID )
        {
            Header( "Location: /link/group/incoming" );
            exit();
        }
        
        Header( "Location: /link/group/$LinkGroupID" );
        exit();
        
    }
    else
    {
        Header( "Location: /link/norights" );
    }
}


// Insert a link.
if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkAdd") )
    {
        if ( $Title != "" &&
        $LinkGroupID != "" &&
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

            $ttitle = $Title;
            $turl = $Url;
            if ( !$GetSite )
            {
                $tkeywords = $Keywords;
                $tdescription = $Description;
            }
    
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
        Header( "Location: /link/norights" );
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
$action = "update";

if ( $Action == "new" )
{
    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkAdd" ) )
    {
        Header( "Location: /link/norights" );
    }

    $action = "insert";
}

// setter akseptert link som default.
$yes_selected = "selected";
$no_selected = "";

// editere
if ( $Action == "edit" )
{
    $ini = new INIFIle( "ezlink/admin/intl/" . $Language . "/linkedit.php.ini", false );
    $headline =  $ini->read_var( "strings", "headline_edit" );

    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkModify" ) )
    {
        Header( "Location: /link/norights" );
    }
    else
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
              
        $ttitle = $editlink->title();
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


$t->set_var( "title", $ttitle );
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
