<?php
//
// $Id: versionedit.php,v 1.1 2001/11/02 07:55:03 pkej Exp $
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
include_once( "ezlicense/classes/ezlicensecost.php" );
include_once( "ezlicense/classes/ezlicensetype.php" );

if ( isset( $Store ) || isset( $NewCost ) || isset( $Back )  )
{
    $count = count( $CostIDArray );
    $hasError = false;
    
    for ( $i = 0; $i < $count; $i++ )
    {
        $cost =& new eZLicenseCost( $CostIDArray[$i] );
        $cost->setProgramVersionID( $ObjectID );
        $cost->setLicenseTypeID( $LicenseTypeArray[$i] );
        $cost->setCost( $CostValueArray[$i] );
        $cost->setCostNonProfessional( $CostNonProfValueArray[$i] );
        if ( $cost->store() == false )
        {
            $hasError = true;
        }
    }

    if ( isset( $Store ) )
    {
        if ( $hasError == true )
        {
            eZHTTPTool::header( "Location: /license/version/edit/$ObjectID/?Error=DuplicateKey" );
            exit();
        }
        else
        {
            eZHTTPTool::header( "Location: /license/version/edit/$ObjectID/" );
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
            $version =& new eZLicenseProgramVersion( $ObjectID );
            $ProgramID = $version->programID();
            eZHTTPTool::header( "Location: /license/program/edit/$ProgramID/" );
            exit();
        }
    }
}

if ( isset( $NewCost ) )
{
    $cost =& new eZLicenseCost();
    $cost->setProgramVersionID( $ObjectID );
    $cost->setLicenseTypeID( 0 );
    $cost->setCost( 0 );
    if ( $cost->store() == false )
    {
        eZHTTPTool::header( "Location: /license/version/edit/$ObjectID/?Error=DuplicateKey" );
        exit();
    }
    else
    {
        eZHTTPTool::header( "Location: /license/version/edit/$ObjectID/" );
        exit();
    }
}

if ( isset( $DeleteSelected ) )
{
    $count = count( $CostDeleteIDArray );
    for ( $i = 0; $i < $count; $i++ )
    {
        $cost =& new eZLicenseCost( $CostDeleteIDArray[$i] );
        $cost->delete();
    }
    eZHTTPTool::header( "Location: /license/version/edit/$ObjectID/" );
    exit();
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZLicenseMain", "Language" );

$t = new eZTemplate( "ezlicense/admin/" . $ini->read_var( "eZLicenseMain", "AdminTemplateDir" ),
                     "ezlicense/admin/intl/", $Language, "license.php" );
$t->setAllStrings();

$t->set_file( array(
    "version_edit_page_tpl" => "versionedit.tpl"
    ) );

$t->set_block( "version_edit_page_tpl", "duplicate_key_error_tpl", "duplicate_key_error" );
$t->set_block( "version_edit_page_tpl", "no_costs_tpl", "no_costs" );
$t->set_block( "version_edit_page_tpl", "cost_list_tpl", "cost_list" );
$t->set_block( "cost_list_tpl", "cost_item_tpl", "cost_item" );
$t->set_block( "cost_item_tpl", "license_type_item_tpl", "license_type_item" );

$t->set_var( "no_costs", "" );
$t->set_var( "cost_list", "" );
$t->set_var( "duplicate_key_error", "" );

$version =& new eZLicenseProgramVersion( $ObjectID );
$program =& new eZLicenseProgram( $version->programID() );

$t->set_var( "program_id", $program->id() );
$t->set_var( "program_name", $program->name() );
$t->set_var( "version_id", $version->id() );
$t->set_var( "version_major", $version->major() );
$t->set_var( "version_minor", $version->minor() );

$costs =& eZLicenseCost::costs( $ObjectID );

if ( count( $costs ) > 0 )
{
    $i=0;
    foreach( $costs as $cost )
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

        $licenseTypes = eZLicenseType::getAll();
        
        $t->set_var( "license_type_item", "" );
        foreach( $licenseTypes as $type )
        {
            if ( $cost->licenseTypeID() == $type->id() )
            {
                $t->set_var( "selected", "selected" );
            }
            else
            {
                $t->set_var( "selected", "" );
            }
            
            $t->set_var( "license_type_id", $type->id() );
            $t->set_var( "license_type_name", $t->Ini->read_var( "strings", $type->name() ) );
            $t->parse( "license_type_item", "license_type_item_tpl", true );
        }
        
        
        $t->set_var( "cost_id", $cost->id() );
        $t->set_var( "cost_value", $cost->cost() );
        $t->set_var( "cost_value_non_prof", $cost->costNonProfessional() );
        $t->parse( "cost_item", "cost_item_tpl", true );
    }
    
    $t->parse( "cost_list", "cost_list_tpl" );
}
else
{
    $t->parse( "no_costs", "no_costs_tpl" );
}

if ( $Error == "DuplicateKey" )
{
    $t->parse( "duplicate_key_error", "duplicate_key_error_tpl" );
}

$t->set_var( "RedirectURL", $RedirectURL );
$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteStyle );
$t->pparse( "output", "version_edit_page_tpl" );

?>
