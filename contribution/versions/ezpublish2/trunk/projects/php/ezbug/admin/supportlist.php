<?php
// 
// $Id: supportlist.php,v 1.1 2001/10/29 14:13:02 jhe Exp $
//
// Created on: <29-Oct-2001 10:26:39 jhe>
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
include_once( "classes/ezlist.php" );

require( "ezuser/admin/admincheck.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBugMain", "Language" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "supportlist.php" );
$t->setAllStrings();

$t->set_file( "support_edit_tpl", "supportlist.tpl" );
$t->set_block( "support_edit_tpl", "support_block_tpl", "support_block" );
$t->set_var( "support_block", "" );

$list = eZBugSupport::getAll( $Offset );
$countList = eZBugSupport::getAllCount();

$locale = new eZLocale( $Language );
$t->set_var( "site_style", $SiteStyle );
foreach ( $list as $supportUser )
{
    $t->set_var( "support_id", $supportUser->id() );
    $t->set_var( "support_name", $supportUser->name() );
    $t->set_var( "support_expirydate", $locale->format( $supportUser->expiryDate() ) );

    $t->parse( "support_block", "support_block_tpl", true );
}

eZList::drawNavigator( $t, $countList, 10, $Offset, "support_edit_tpl" );

$t->pparse( "output", "support_edit_tpl" );

?>
