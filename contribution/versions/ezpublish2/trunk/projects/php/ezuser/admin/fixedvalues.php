<?php
//
// $Id: fixedvalues.php,v 1.1 2001/11/20 16:11:57 ce Exp $
//
// Definition of ||| class
//
// <real-name> <<mail-name>>
// Created on: <20-Nov-2001 16:41:52 ce>
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

//!! 
//! The class ||| does
/*!

*/

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezlist.php" );

include_once( "ezuser/classes/ezuseradditional.php" );

$ActionValue = "list";
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFormMain", "Language" );

$additional = new eZUserAdditional( $AdditionalID );

if( isset( $AddValue ) )
{
    print( "hm" );
    $additional->addFixedValue( );
}


if( isset( $Store ) || isset ( $AddValue ) || isset ( $DeleteSelected ) || isset ( $OK ) )
{
    $i=0;
    if ( count ( $ValueID ) > 0 )
    {
        foreach( $ValueID as $ID )
        {
            print( $ID );
            $additional->addFixedValue( $ID, $Value[$i] );
            $i++;
        }
    }
}

if( isset( $DeleteSelected ) )
{
    foreach( $ValueDeleteID as $ID )
    {
        $value = new eZUserFixedValue( $ID );
        $value->delete();
    }
}

if( isset( $OK ) )
{
    eZHTTPTool::header( "Location: /user/additional/$AdditionalID" );
    exit();
}

$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/intl/", $Language, "fixedvalues.php" );
$t->setAllStrings();

$t->set_file( array(
    "value_list_page_tpl" => "fixedvalues.tpl"
    ) );

$t->set_block( "value_list_page_tpl", "no_values_item_tpl", "no_values_item" );
$t->set_block( "value_list_page_tpl", "value_list_tpl", "value_list" );
$t->set_block( "value_list_tpl", "value_item_tpl", "value_item" );

$t->set_var( "value_item", "" );
$t->set_var( "value_list", "" );
$t->set_var( "no_values_item", "" );

$t->set_var( "additional_name", $additional->name() );
$t->set_var( "additional_id", $additional->id() );

$t->set_var( "form_id", $FormID );

$values =& $additional->fixedValues();


if( count( $values ) == 0 )
{
    $t->parse( "no_values_item", "no_values_item_tpl" );
}
else
{
    $i = 0;
    foreach( $values as $value )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        
        $t->set_var( "value_id", $value["ID"] );
        $t->set_var( "value", $value["Value"] );

        $t->parse( "value_item", "value_item_tpl", true );
        
        $i++;
    }
    
    
    $t->parse( "value_list", "value_list_tpl" );
}

$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteStyle );
$t->pparse( "output", "value_list_page_tpl" );

?>
