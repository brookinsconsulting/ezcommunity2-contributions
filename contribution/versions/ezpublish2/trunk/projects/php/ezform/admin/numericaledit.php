<?php
// 
// $Id: numericaledit.php,v 1.2 2001/12/18 18:15:19 pkej Exp $
//
// Created on: <12-Jun-2001 13:07:24 pkej>
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
include_once( "classes/ezlist.php" );

include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelement.php" );
include_once( "ezform/classes/ezformelementtype.php" );
include_once( "ezform/classes/ezformelementnumerical.php" );

$ActionValue = "list";
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFormMain", "Language" );

$element = new eZFormElement( $ElementID );
$elementNumerical = new eZFormElementNumerical( $ElementID );

if ( isset( $OK ) )
{
    if ( $elementNumerical->id() == 0 )
    {
        $elementNumerical->setID( $ElementID );
    }
    
    if ( $MinValue < $MaxValue )
    {
        $elementNumerical->setMinValue( $MinValue );
        $elementNumerical->setMaxValue( $MaxValue );
        $elementNumerical->store();
        eZHTTPTool::header( "Location: /form/form/pageedit/$FormID/$PageID" );
        exit();
    }
    
    if ( ( $MinValue == "" && $MaxValue == "" ) && $elementNumerical->id() > 0)
    {
        $elementNumerical->delete();
        eZHTTPTool::header( "Location: /form/form/pageedit/$FormID/$PageID" );
        exit();        
    }
}

if ( isset( $Back ) )
{
    eZHTTPTool::header( "Location: /form/form/pageedit/$FormID/$PageID" );
    exit();
}

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "form.php" );
$t->setAllStrings();

$t->set_file( array(
    "page_tpl" => "numericaledit.tpl"
    ) );

if ( $MinValue >= $MaxValue && !empty( $MaxValue ) )
{
    $t->set_var( "min_higher_than_max_error", $t->Ini->read_var( "strings", "min_higher_than_max_error" ) );
    $t->set_var( "min_value", $MinValue );
    $t->set_var( "max_value", $MaxValue );
}
else
{
    $t->set_var( "min_higher_than_max_error", "" );
    $t->set_var( "min_value", $elementNumerical->minValue() );
    $t->set_var( "max_value", $elementNumerical->maxValue() );
}



$t->set_var( "element_name", $element->name() );
$t->set_var( "element_id", $element->id() );

$t->set_var( "form_id", $FormID );
$t->set_var( "page_id", $PageID );


$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteStyle );
$t->pparse( "output", "page_tpl" );

?>
