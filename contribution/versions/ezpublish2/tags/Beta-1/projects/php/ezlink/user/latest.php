<?
/*!
    $Id: latest.php,v 1.1 2000/10/19 09:32:09 ce-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: 
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZLinkMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( "common/ezphputils.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );


$t = new eZTemplate( "ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
"ezlink/user/intl", $Language, "linklist.php" );
$t->setAllStrings();

$t->set_file( array(
    "last_links" => "latest.tpl"
    ) );

$t->set_block( "last_links", "link_list_tpl", "link_item" );

$link = new eZLink();

$link_array = $link->getLastTenDate( 10, 0 );

if ( count( $link_array ) == 0 )
{
    
    $t->set_var( "link_list", "<p>Ingen linker ble funnet.</p>" );
}
else
{
    $i=0;
    foreach( $link_array as $linkItem )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#f0f0f0" );
        }
        else
        {
            $t->set_var( "bg_color", "#dcdcdc" );
        }  

        $t->set_var( "link_id", $linkItem->id() );
        $t->set_var( "link_title", $linkItem->title() );
        $t->set_var( "link_description", $linkItem->description() );
        $t->set_var( "link_groupid", $linkItem->linkgroupid() );
        $t->set_var( "link_keywords", $linkItem->keywords() );
        $t->set_var( "link_created", $linkItem->created() );
        $t->set_var( "link_modified", $linkItem->modified() );
        $t->set_var( "link_accepted", $linkItem->accepted() );
        $t->set_var( "link_url", $linkItem->url() );

        $hit = new eZHit();
        $hits = $hit->getLinkHits( $link_array[ $i ][ "ID" ] );

        $t->set_var( "link_hits", $hits );

        $t->set_var( "document_root", $DOC_ROOT );

        $t->parse( "link_item", "link_list_tpl", true );
        $i++;
    }
}

$t->pparse( "output", "last_links" );

?>
