<?
// 
// $Id: grouplist.php,v 1.5 2000/10/25 16:44:19 ce-cvs Exp $
//
// Definition of eZUser class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZUserMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZUserMain", "DocumentRoot" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     $DOC_ROOT . "/admin/" . "/intl", $Language, "grouplist.php" );
$t->setAllStrings();

$t->set_file( array(
    "group_list_page" => "grouplist.tpl"
    ) );

$t->set_block( "group_list_page", "group_item_tpl", "group_item" );

$group = new eZUserGroup();

$groupList = $group->getAll();

$i=0;
foreach( $groupList as $groupItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );
    

    $t->set_var( "group_id", $groupItem->id() );
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_description", $groupItem->description() );

    $t->parse( "group_item", "group_item_tpl", true );
    $i++;
}

$t->pparse( "output", "group_list_page" );

?>
