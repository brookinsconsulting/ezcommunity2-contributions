<?php
//
// $Id: programlist.php,v 1.1 2001/11/02 07:55:03 pkej Exp $
//
// Created on: <17-Oct-2001 14:23:46 pkej>
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

include_once( "ezlicense/classes/ezlicenseprogram.php" );

if ( isset( $Store ) || isset( $NewProgram ) || isset( $Back ) )
{
    $count = count( $ProgramIDArray );
    for ( $i = 0; $i < $count; $i++ )
    {
        $licenseType =& new eZLicenseProgram( $ProgramIDArray[$i] );
        $licenseType->setName( $ProgramNameArray[$i] );
        $licenseType->store();
    }

    if ( isset( $Store ) )
    {
        eZHTTPTool::header( "Location: /license/program/list/" );
        exit();
    }

    if ( isset( $Back ) )
    {
        if ( isset( $RedirectURL ) && !empty($RedirectURL) )
        {    
            eZHTTPTool::header( "Location: $RedirectURL" );
            exit();
        }
        else
        {
            eZHTTPTool::header( "Location: /module/activate/eZLicense?RefURL=/license/license/list/" );
            exit();
        }
    }

}

if ( isset( $NewProgram ) )
{
    $licenseType =& new eZLicenseProgram();
    $licenseType->store();
    eZHTTPTool::header( "Location: /license/program/list/" );
    exit();
}

if ( isset( $DeleteSelected ) )
{
    $count = count( $ProgramDeleteIDArray );
    for ( $i = 0; $i < $count; $i++ )
    {
        $licenseType =& new eZLicenseProgram( $ProgramDeleteIDArray[$i] );
        $licenseType->delete();
    }
    eZHTTPTool::header( "Location: /license/program/list/" );
    exit();
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZLicenseMain", "Language" );

$t = new eZTemplate( "ezlicense/admin/" . $ini->read_var( "eZLicenseMain", "AdminTemplateDir" ),
                     "ezlicense/admin/intl/", $Language, "license.php" );
$t->setAllStrings();

$t->set_file( array(
    "license_list_page_tpl" => "programlist.tpl"
    ) );

$t->set_block( "license_list_page_tpl", "no_licenses_tpl", "no_licenses" );
$t->set_block( "license_list_page_tpl", "license_list_tpl", "license_list" );
$t->set_block( "license_list_tpl", "license_item_tpl", "license_item" );


$t->set_var( "no_licenses", "" );
$t->set_var( "license_list", "" );

$licenses = eZLicenseProgram::programs();

if ( count( $licenses ) > 0 )
{
    $i=0;
    foreach( $licenses as $licenseProgram )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        $i++;
        
        $t->set_var( "program_id", $licenseProgram->id() );
        $t->set_var( "program_name", $licenseProgram->name() );
        $t->parse( "license_item", "license_item_tpl", true );
    }
    
    $t->parse( "license_list", "license_list_tpl" );
}
else
{
    $t->parse( "no_licenses", "no_licenses_tpl" );
}


$t->set_var( "RedirectURL", $RedirectURL );
$t->set_var( "site_style", $SiteStyle );
$t->pparse( "output", "license_list_page_tpl" );

?>
