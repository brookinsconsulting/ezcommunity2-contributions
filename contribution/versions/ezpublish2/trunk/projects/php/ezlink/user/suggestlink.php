<?
/*!
    $Id: suggestlink.php,v 1.6 2000/10/26 08:42:03 ce-cvs Exp $

    Author: Christoffer A. Elo <ce@ez.no>
    
    Created on: <14-Sep-2000 19:37:17 bf>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZLinkMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

if ( $GetSite )
{
    if ( $url )
    {
        
        $metaList =  get_meta_tags ( "http://" . $url );

        if( count( $metaList ) == 0 )
        {
            $inierror = new INIFile( "ezlink/user/" . "/intl/" . $Language . "/suggestlink.php.ini", false );
            $terror_msg = $inierror->read_var( "strings", "nometa" );
        }

        $tdescription = $metaList["description"];
        $tkeywords = $metaList["keywords"];
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
            Header( "Location: /link/success/" );
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

$t->set_var( "tjo", "001" );

$t->set_var( "title", $ttitle );
$t->set_var( "url", $turl );
$t->set_var( "keywords", $tkeywords );
$t->set_var( "description", $tdescription ); 

$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "suggest_link" );

?>
