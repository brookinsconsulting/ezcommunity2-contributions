<?
/*!
    $Id: categorylist.php,v 1.10 2000/10/10 11:41:59 ce-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: 
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZLinkMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "common/ezphputils.php" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZLinkMain", "TemplateDir" ). "/categorylist/",
$DOC_ROOT . "/intl", $Language, "categorylist.php" );
$t->setAllStrings();

$t->set_file( array(
    "link_group_list" => "linkgrouplistshort.tpl"
    ) );

$t->set_block( "link_group_list", "link_group_tpl", "link_group" );

// Lister alle kategorier
$linkGroup = new eZLinkGroup();
$linkGroup->get ( $LGID );

$linkGroup_array = $linkGroup->getByParent( 0 );

if ( count( $linkGroup_array ) == 0 )
{
    $t->set_var( "group_list", "" );

}
else
{
    for ( $i=0; $i<count( $linkGroup_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#FFFFFF" );
        }
        else
        {
            $t->set_var( "bg_color", "#FFFFFF" );
        }  

        $link_group_id = $linkGroup_array[ $i ][ "ID" ];
        $t->set_var( "linkgroup_id", $link_group_id );
        $t->set_var( "linkgroup_title", $linkGroup_array[ $i ][ "Title" ] );
        $t->set_var( "linkgroup_parent", $linkGroup_array[ $i ][ "Parent" ] );

        $total_sub_links = $linkGroup->getTotalSubLinks( $link_group_id, $link_group_id );
        $new_sub_links = $linkGroup->getNewSubLinks( $link_group_id, $link_group_id, 1 );
        
        $t->set_var( "total_links", $total_sub_links );
        $t->set_var( "new_links", $new_sub_links );
        
        $t->set_var( "document_root", $DOC_ROOT );
        $t->parse( "link_group", "link_group_tpl", true );

    }
}
$t->set_var( "linkgroup_id", $LGID );
$t->set_var( "document_root", $DOC_ROOT );
                       
$t->pparse( "output", "link_group_list" );

?>
