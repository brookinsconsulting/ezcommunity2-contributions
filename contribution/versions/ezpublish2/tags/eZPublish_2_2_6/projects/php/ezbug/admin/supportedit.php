<?php
// 
// $Id: supportedit.php,v 1.2 2001/10/31 07:25:56 jhe Exp $
//
// Created on: <29-Oct-2001 14:40:06 jhe>
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

include_once( "ezbug/classes/ezbugsupport.php" );
include_once( "classes/eztemplate.php" );

require( "ezuser/admin/admincheck.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBugMain", "Language" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "supportedit.php" );
$t->setAllStrings();

$t->set_file( "support_edit_tpl", "supportedit.tpl" );
$t->set_block( "support_edit_tpl", "month_tpl", "month" );
$t->set_block( "support_edit_tpl", "day_tpl", "day" );

$locale = new eZLocale( $Language );

$t->set_var( "site_style", $SiteStyle );

if ( isSet( $Cancel ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/support/list/" );
    exit();
}

switch ( $Action )
{
    case "edit":
    {
        $supportUser = new eZBugSupport( $id );
        $t->set_var( "id", $supportUser->id() );
        $t->set_var( "name", $supportUser->name() );
        $t->set_var( "email", $supportUser->userEmail() );
        $t->set_var( "action", "update" );
        $expiryDate = $supportUser->expiryDate();
        for ( $i = 1; $i <= 31; $i++ )
        {
            $t->set_var( "day_name", $i );
            if ( $expiryDate->day() == $i )
                $t->set_var( "selected", "selected" );
            else
                $t->set_var( "selected", "" );
            $t->set_var( "day_id", $i );
            $t->parse( "day", "day_tpl", true );
        }
        for ( $i = 1; $i <= 12; $i++ )
        {
            $t->set_var( "month_name", $locale->monthName( $i, false ) );
            if ( $expiryDate->month() == $i )
                $t->set_var( "selected", "selected" );
            else
                $t->set_var( "selected", "" );
            $t->set_var( "month_id", $i );
            $t->parse( "month", "month_tpl", true );
        }
        $t->set_var( "year_value", $expiryDate->year() );
    }
    break;

    case "new":
    {
        $t->set_var( "id", "" );
        $t->set_var( "name", "" );
        $t->set_var( "email", "" );
        $t->set_var( "action", "insert" );
        $expiryDate = new eZDate();
        for ( $i = 1; $i <= 31; $i++ )
        {
            $t->set_var( "day_name", $i );
            if ( $expiryDate->day() == $i )
                $t->set_var( "selected", "selected" );
            else
                $t->set_var( "selected", "" );
            $t->set_var( "day_id", $i );
            $t->parse( "day", "day_tpl", true );
        }
        for ( $i = 1; $i <= 12; $i++ )
        {
            $t->set_var( "month_name", $locale->monthName( $i, false ) );
            if ( $expiryDate->month() == $i )
                $t->set_var( "selected", "selected" );
            else
                $t->set_var( "selected", "" );
            $t->set_var( "month_id", $i );
            $t->parse( "month", "month_tpl", true );
        }
        $t->set_var( "year_value", $expiryDate->year() );
    }
    break;
    
    case "update":
    {
        $supportUser = new eZBugSupport( $id );
    }
    break;

    case "insert":
    {
        $supportUser = new eZBugSupport();
    }
    break;
}

if ( $Action == "update" || $Action == "insert" )
{
    $supportUser->setName( $Name );
    $supportUser->setUserEmail( $Email );
    $supportUser->setExpiryDate( new eZDate( $Year, $Month, $Day ) );
    $supportUser->store();

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/support/list/" );
    exit();
}

$t->pparse( "output", "support_edit_tpl" );

?>
