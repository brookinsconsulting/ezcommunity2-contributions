<?php
// 
// $Id: tac.php,v 1.1.2.1 2002/05/15 12:12:00 pkej Exp $
//
// Created on: <15-mai-2002 11:59:42 pkej>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );

$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     "ezuser/user/intl/", $Language, "tac.php" );

$TaCPath = $siteDir . "ezuser/user/intl/" . $Language . "/tac_text.tpl";
$NonAcceptTaCPath = $siteDir . "ezuser/user/intl/". $Language . "/tac_not_accepted.tpl";

if ( ereg( "(.*/)([^\/]+\.php)$", $SCRIPT_FILENAME, $regs ) )
{
    $siteDir = $regs[1];
}

$t->setAllStrings();

$t->set_file( "tac_show", "tac.tpl" );

$t->set_block( "tac_show", "tac_tpl", "tac" );
$t->set_block( "tac_show", "tac_denied_tpl", "tac_denied" );
$t->set_block( "tac_show", "tac_text_only_tpl", "tac_text_only" );

$t->set_var( "tac", "" );
$t->set_var( "tac_denied", "" );
$t->set_var( "tac_text_only", "" );

if ( $Agreement == "No" )
{
    $tac = new eZTemplate( "ezuser/user/intl/$Language/",
                     "ezuser/user/intl/", $Language, "tac.php" );

    $tac->setAllStrings();
    
    $tac->set_file( "tac_file", "tac_no_text.tpl" );
    
    $tac->set_var( "site_name", $ini->read_var( "site", "SiteTitle" ) );
    $tac->set_var( "site_url", $ini->read_var( "site", "UserSiteURL" ) );
    
    $tac->parse( "tac_text", "tac_file" );
    $tac_text = $tac->varvals["tac_text"];

    $t->set_var( "no_tac_text", $tac_text );

    $t->parse( "tac_denied", "tac_denied_tpl" );    




}
else if ( $Action == "View" )
{
    $tac = new eZTemplate( "ezuser/user/intl/$Language/",
                     "ezuser/user/intl/", $Language, "tac.php" );

    $tac->setAllStrings();
    
    $tac->set_file( "tac_file", "tac_text.tpl" );
    
    $tac->set_var( "site_name", $ini->read_var( "site", "SiteTitle" ) );
    $tac->set_var( "site_url", $ini->read_var( "site", "UserSiteURL" ) );
    
    $tac->parse( "tac_text", "tac_file" );
    $tac_text = $tac->varvals["tac_text"];

    $t->set_var( "tac_text", $tac_text );

    $t->parse( "tac_text_only", "tac_text_only_tpl" );    




}
else
{
    $tac = new eZTemplate( "ezuser/user/intl/$Language/",
                     "ezuser/user/intl/", $Language, "tac.php" );

    $tac->setAllStrings();
    
    $tac->set_file( "tac_file", "tac_text.tpl" );
    
    $tac->set_var( "site_name", $ini->read_var( "site", "SiteTitle" ) );
    $tac->set_var( "site_url", $ini->read_var( "site", "UserSiteURL" ) );
    
    $tac->parse( "tac_text", "tac_file" );
    $tac_text = $tac->varvals["tac_text"];

    $t->set_var( "tac_text", $tac_text );

    $t->parse( "tac", "tac_tpl" );    
}

$t->set_var( "action_value", "new" );
$t->set_var( "global_section_id", $GlobalSectionID );
$t->set_var( "redirect_url", $RedirectURL );

$t->pparse( "output", "tac_show" );
