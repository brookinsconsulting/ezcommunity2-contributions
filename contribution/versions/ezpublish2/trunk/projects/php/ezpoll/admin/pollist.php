<?
// 
// $Id: pollist.php,v 1.10 2000/11/01 07:36:47 bf-cvs Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZPollMain", "Language" );
$errorIni = new INIFIle( "ezpoll/admin/intl/" . $Language . "/pollist.php.ini", false );

include_once( "ezpoll/classes/ezpoll.php" );

require( "ezuser/admin/admincheck.php" );

if ( $Action == "StoreMainPoll" )
{
    // clear the menu cache
    unlink( "ezpoll/cache/menubox.cache" );
    
    $mainPoll = new eZPoll( $MainPollID );
    if ( $mainPoll->isClosed() )
    {
        $errorMsg = $errorIni->read_var( "strings", "poll_closed" );
    }
    else
    {
        $mainPoll->setMainPoll( $mainPoll );
    }
}

$t = new eZTemplate( "ezpoll/admin/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                     "ezpoll/admin/intl/", $Language, "pollist.php" );

$t->setAllStrings();

$t->set_file( array(
    "poll_list_page" => "pollist.tpl"
    ) );

$t->set_block( "poll_list_page", "poll_item_tpl", "poll_item" );

$nopolls = "";

$poll = new eZPoll();

$pollList = $poll->getAll( );

if ( !$pollList )
{
    $ini = new INIFile( "ezpoll/" . "/admin/" . "intl/" . $Language . "/pollist.php.ini", false );
    $nopolls =  $ini->read_var( "strings", "nopolls" );
    $t->set_var( "poll_list", "" );
}

$mainPoll = $poll->mainPoll();

if ( $mainPoll )
{
    $mainPollID = $mainPoll->id();
}

$i=0;
foreach( $pollList as $pollItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
        
    if ( $pollItem->isEnabled() == "true" )
        $t->set_var( "poll_is_enabled", "Ja" );
    else
        $t->set_var( "poll_is_enabled", "Nei" );

    if ( $pollItem->anonymous() == "true" )
        $t->set_var( "anonymous", "Ja" );
    else
        $t->set_var( "anonymous", "Nei" );

    
    if ( $pollItem->id() == $mainPollID )
        $t->set_var( "is_checked", "checked" );
    else
        $t->set_var( "is_checked", "" );        

    if ( $pollItem->isClosed() == "true" )
    {
        $t->set_var( "poll_is_closed", "Avsluttet" );
    }
    else
    {
        $t->set_var( "poll_is_closed", "Ikke avsluttet" );
    }
    $t->set_var( "poll_id", $pollItem->id() );
    $t->set_var( "poll_name", $pollItem->name() );
    $t->set_var( "poll_description", $pollItem->description() );

    $t->parse( "poll_item", "poll_item_tpl", true );
    $i++;
}

$t->set_var( "error_msg", $errorMsg );
$t->set_var( "nopolls", $nopolls );

$t->pparse( "output", "poll_list_page" );
?>
