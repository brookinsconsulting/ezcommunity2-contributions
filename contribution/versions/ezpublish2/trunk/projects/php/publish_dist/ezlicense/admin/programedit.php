<?php
//
// $Id: programedit.php,v 1.1 2001/11/02 07:55:03 pkej Exp $
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
include_once( "ezlicense/classes/ezlicenseprogramversion.php" );

if ( isset( $Store ) || isset( $NewVersion ) || isset( $Back ) )
{
    $count = count( $VersionIDArray );
    $hasError = false;
    for ( $i = 0; $i < $count; $i++ )
    {
        $version =& new eZLicenseProgramVersion( $VersionIDArray[$i] );
        $version->setMajor( $VersionMajorArray[$i] );
        $version->setMinor( $VersionMinorArray[$i] );

        if ( $version->store() == false )
        {
            $hasError = true;
        }
    }

    if ( isset( $Store ) )
    {
        if ( $hasError == true )
        {
            eZHTTPTool::header( "Location: /license/program/edit/$ObjectID/?Error=DuplicateKey" );
            exit();
        }
        else
        {
            eZHTTPTool::header( "Location: /license/program/edit/$ObjectID/" );
            exit();
        }
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
            eZHTTPTool::header( "Location: /license/program/list/" );
            exit();
        }
    }

}

if ( isset( $NewVersion ) )
{
    $version =& new eZLicenseProgramVersion();
    $version->setProgramID( $ObjectID );
    $version->setMajor( 0 );
    $version->setMinor( 0 );
    if ( $version->store() == false )
    {
        eZHTTPTool::header( "Location: /license/program/edit/$ObjectID/?Error=DuplicateKey" );
    }
    else
    {
        eZHTTPTool::header( "Location: /license/program/edit/$ObjectID/" );
    }
    exit();
}

if ( isset( $DeleteSelected ) )
{
    $count = count( $VersionDeleteIDArray );
    for ( $i = 0; $i < $count; $i++ )
    {
        $version =& new eZLicenseProgramVersion( $VersionDeleteIDArray[$i] );
        $version->delete();
    }
    eZHTTPTool::header( "Location: /license/program/edit/$ObjectID/" );
    exit();
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZLicenseMain", "Language" );

$t = new eZTemplate( "ezlicense/admin/" . $ini->read_var( "eZLicenseMain", "AdminTemplateDir" ),
                     "ezlicense/admin/intl/", $Language, "license.php" );
$t->setAllStrings();

$t->set_file( array(
    "program_list_page_tpl" => "programedit.tpl"
    ) );

$t->set_block( "program_list_page_tpl", "duplicate_key_error_tpl", "duplicate_key_error" );
$t->set_block( "program_list_page_tpl", "no_versions_tpl", "no_versions" );
$t->set_block( "program_list_page_tpl", "version_list_tpl", "version_list" );
$t->set_block( "version_list_tpl", "version_item_tpl", "version_item" );

$t->set_var( "no_versions", "" );
$t->set_var( "version_list", "" );
$t->set_var( "duplicate_key_error", "" );

$versions = eZLicenseProgramVersion::versions( $ObjectID );
$program = new eZLicenseProgram( $ObjectID );

$t->set_var( "program_id", $program->id() );
$t->set_var( "program_name", $program->name() );

if ( count( $versions ) > 0 )
{
    $i=0;
    foreach( $versions as $version )
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
        
        $t->set_var( "version_id", $version->id() );
        $t->set_var( "version_major", $version->major() );
        $t->set_var( "version_minor", $version->minor() );
        $t->parse( "version_item", "version_item_tpl", true );
    }
    
    $t->parse( "version_list", "version_list_tpl" );
}
else
{
    $t->parse( "no_versions", "no_versions_tpl" );
}

if ( $Error == "DuplicateKey" )
{
    $t->parse( "duplicate_key_error", "duplicate_key_error_tpl" );
}

$t->set_var( "RedirectURL", $RedirectURL );
$t->set_var( "site_style", $SiteStyle );
$t->pparse( "output", "program_list_page_tpl" );

?>
