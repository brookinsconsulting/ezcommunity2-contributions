<?php
//
// $Id: expireduserlist.php,v 1.1 2001/12/04 13:44:30 jhe Exp $
//
// Created on: <27-Nov-2001 18:33:50 jhe>
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

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZUserMain", "Language" );

if ( $Action == "edit" )
{
    if ( $NoExpiryDate == "on" )
    {
        $expiryTime = 0;
    }
    else
    {
        $date = new eZDateTime( $Year, $Month, $Day, 23, 59, 59 );
        $expiryTime = $date->timeStamp();
    }
    
    foreach ( $UserArray as $UserItem )
    {
        $expiredUser = new eZUser( $UserItem );
        $expiredUser->setExpiryDate( $expiryTime );
        $expiredUser->setIsActive( true );
        $expiredUser->store();
    }
}

$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/intl", $Language, "expireduserlist.php" );
$t->setAllStrings();

$t->set_file( "expireduserlist_tpl", "expireduserlist.tpl" );
$t->set_block( "expireduserlist_tpl", "day_item_tpl", "day_item" );
$t->set_block( "expireduserlist_tpl", "month_item_tpl", "month_item" );
$t->set_block( "expireduserlist_tpl", "user_item_tpl", "user_item" );

$t->set_var( "user_item" );

$list = eZUser::getExpiredUsers( eZDateTime::timeStamp( true ) );

$today = new eZDateTime();
$locale = new eZLocale( $Language );

for ( $i = 1; $i <= 31; $i++ )
{
    $t->set_var( "day", $i );
    $t->set_var( "day_selected", $today->day() == $i ? "selected" : "" );
    $t->parse( "day_item", "day_item_tpl", true );
}

for ( $i = 1; $i <= 12; $i++ )
{
    $t->set_var( "month_name", $locale->monthName( $i, false ) );
    $t->set_var( "month_id", $i );
    $t->set_var( "month_selected", $today->month() == $i ? "selected" : "" );
    $t->parse( "month_item", "month_item_tpl", true );
}

$t->set_var( "year", $today->year() );

foreach ( $list as $expiredUser )
{
    $t->set_var( "username", $expiredUser->login() );
    $t->set_var( "name", $expiredUser->name() );
    $t->set_var( "id", $expiredUser->id() );
    $t->parse( "user_item", "user_item_tpl", true );
}

$t->pparse( "output", "expireduserlist_tpl" );

?>
