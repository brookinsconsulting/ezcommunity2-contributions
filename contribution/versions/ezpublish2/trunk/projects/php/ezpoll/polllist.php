<?
// 
// $Id: polllist.php,v 1.2 2000/09/27 11:41:58 ce-cvs Exp $
//
// Definition of eZPoll class
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

$Language = $ini->read_var( "eZPollMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZPollMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezpoll.php" );


$t = new eZTemplate( $DOC_ROOT . $ini->read_var( "eZPollMain", "TemplateDir" ) . "/polllist/",
                     $DOC_ROOT . "/intl/", $Language, "polllist.php" );

$t->setAllStrings();

$t->set_file( array(
    "poll_list_page" => "polllist.tpl",
    "poll_item" => "pollitem.tpl"
    ) );


$poll = new eZPoll();

$pollList = $poll->getAll( );

foreach( $pollList as $pollItem )
{
    $t->set_var( "poll_id", $pollItem->id() );
    $t->set_var( "poll_name", $pollItem->name() );
    $t->set_var( "poll_description", $pollItem->description() );

    $t->parse( "poll_list", "poll_item", true );
}

$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "poll_list_page" );

?>
