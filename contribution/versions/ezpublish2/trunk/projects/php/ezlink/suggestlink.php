<?
/*!
    $Id: suggestlink.php,v 1.14 2000/10/10 07:01:09 ce-cvs Exp $

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

if ( $Action == "suggest" )
{
    $newlink = new eZLink();

    if ( ( $title == "" ) || ( $url == "" ) || ( $description == "" ) || ( $keywords == "" ) ) 
    {
        $terror_msg = "Legg til alle feltene..."; 

        $ttitle = $title;
        $turl = $url;
        $tkeywords = $keywords;
        $tdescription = $description;
    }
    else
    {
        $newlink->setTitle( $title );
        $newlink->setUrl( $url );
        $newlink->setKeyWords( $keywords );
        $newlink->setDescription( $description );
        $newlink->setLinkGroup( $linkgroup );
        $newlink->setAccepted( "N" );
     
        if ( $newlink->checkUrl( $url ) == 0 )
        {
            $newlink->store();
            Header( "Location: /link/" );
        }
        else
        {
            $terror_msg = "Linken finnes i databasen...";
            
            $ttitle = $title;
            $turl = $url;
            $tkeywords = $keywords;
            $tdescription = $description;
        }
    }
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZLinkMain", "TemplateDir" ). "/suggestlink/",
$DOC_ROOT . "/intl", $Language, "suggestlink.php" );
$t->setAllStrings();

$t->set_file( array(
    "suggestlink" => "suggestlink.tpl",
    "suggest_group_select" => "suggestgroupselect.tpl"
    ));

$groupselect = new eZLinkGroup();
$grouplink_array = $groupselect->getAll( );

// Selecter
$group_select_dict = "";
for ( $i=0; $i<count( $grouplink_array ); $i++ )
{
    $t->set_var( "grouplink_id", $grouplink_array[ $i ][ "ID" ] );
    $t->set_var( "grouplink_title", $grouplink_array[ $i ][ "Title" ] );

    if ( $grouplink_array[ $i ][ "ID" ] == $LGID )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $group_select_dict[ $grouplink_array[$i][ "ID" ] ] = $i;

    $t->parse( "group_category", "suggest_group_select", true );
}

$t->set_var( "error_msg", $terror_msg );

$t->set_var( "tjo", "001" );

$t->set_var( "title", $ttitle );
$t->set_var( "url", $turl );
$t->set_var( "keywords", $tkeywords );
$t->set_var( "description", $tdescription ); 

$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "suggestlink" );


?>
