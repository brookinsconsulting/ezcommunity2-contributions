<?
// $Id: suggestlink.php,v 1.14 2001/03/09 11:05:35 jb Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Oct-2000 14:54:13 ce>   
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZLinkMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );
include_once( "ezlink/classes/ezmeta.php" );

if ( $GetSite )
{
    if ( $url )
    {
        if ( !preg_match( "%^([a-z]+://)%", $url ) )
            $real_url = "http://" . $url;
        else
            $real_url = $url;

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
            $ttitle = $title;
        $turl = $url;

    }
    $Action = "";
}

if ( $Action == "insert" )
{
    $newlink = new eZLink();

    if ( ( $title == "" ) || ( $url == "" ) || ( $description == "" ) || ( $keywords == "" ) ) 
    {
        $inierror = new INIFile( "ezlink/user/" . "/intl/" . $Language . "/suggestlink.php.ini", false );
        $terror_msg =  $inierror->read_var( "strings", "empty_error" );

        $ttitle = $title;
        $turl = $url;
        if ( !$GetSite )
        {
            $tkeywords = $keywords;
            $tdescription = $description;
        }
    }
    else
    {
        $newlink->setTitle( $title );
        $newlink->setUrl( $url );
        $newlink->setKeyWords( $keywords );
        $newlink->setDescription( $description );
        $newlink->setLinkGroupID( $linkgroup );
        $newlink->setAccepted( "N" );
     
        if ( $newlink->checkUrl( $url ) == 0 )
        {
            $newlink->store();
            eZHTTPTool::header( "Location: /link/success/" );
            exit();
        }
        else
        {
            $inierror = new INIFile( "ezlink/user/" . "/intl/" . $Language . "/suggestlink.php.ini", false );
            $terror_msg =  $inierror->read_var( "strings", "link_error_msg" );

            $ttitle = $title;
            $turl = $url;
            $tkeywords = $keywords;
            $tdescription = $description;
        }
    }
}

$t = new eZTemplate( "ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
"ezlink/user/intl", $Language, "suggestlink.php" );
$t->setAllStrings();

$t->set_file( array(
    "suggest_link" => "suggestlink.tpl"
    ));

$t->set_block( "suggest_link", "group_select_tpl", "group_select" );

$groupselect = new eZLinkGroup();
$groupList = $groupselect->getAll( );

// Selecter
$group_select_dict = "";

$i=0;
foreach( $groupList as $groupItem )
{
    $i++;
    $t->set_var( "grouplink_id", $groupItem->id() );
    $t->set_var( "grouplink_title", $groupItem->title() );

    if ( ( $groupItem->id() ) == $LinkGroupID )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $group_select_dict[ ( $groupItem->id() ) ] = $i;

    $t->parse( "group_select", "group_select_tpl", true );
}

$t->set_var( "error_msg", $terror_msg );

$t->set_var( "title", $ttitle );
$t->set_var( "url", $turl );
$t->set_var( "keywords", $tkeywords );
$t->set_var( "description", $tdescription ); 

$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "suggest_link" );

?>
