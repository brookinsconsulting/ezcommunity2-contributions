<?
// 
// $Id: optionedit.php,v 1.8 2000/11/01 09:24:18 ce-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <20-Sep-2000 10:18:33 bf>
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );

$product = new eZProduct( $ProductID );

if ( $Action == "Insert" )
{
    $option = new eZOption();
    $option->setName( $Name );
    $option->setDescription( $Description );

    $option->store();

    $product->addOption( $option );
    
    $optionArray = explode( "\n", $OptionValues );
    
    foreach ( $optionArray as $optionValue )
    {
        $name = $optionValue;

        $name = trim( $name );

        if ( $name != "" )
        {
            $value = new eZOptionValue();

            
            $value->setName( $name );
            $option->addValue( $value );
        }
    }

    Header( "Location: /trade/productedit/optionlist/$ProductID/" );
    exit();    
}

if ( $Action == "Update" )
{
    $optionArray = explode( "\n", $OptionValues );

    $option = new eZOption( $OptionID );

    $option->setName( $Name );
    $option->setDescription( $Description );

    $option->store();

    $i=0;
    foreach ( $optionArray as $optionValue )
    {
        $name = $optionValue;
        $name = trim( $name );

        if ( $name != "" )
        {
            $value = new eZOptionValue();

            if ( $ChoiceIDArray[$i] == "" )
            { // new item
                $value->setName( $name );                
                $option->addValue( $value );
            }
            else
            { // item exists update
                $value->get( $ChoiceIDArray[$i] );
                $value->setName( $name );

                $value->store();                
            }
                 
        }
        else
        {
            if ( $ChoiceIDArray[$i] != "" )
            { 
                $value->get( $ChoiceIDArray[$i] );

                $value->delete();       
            }            
        }
        $i++;
    }
    Header( "Location: /trade/productedit/optionlist/$ProductID/" );
    exit();    
}

if ( $Action == "Delete" )
{
    $option = new eZOption( $OptionID );
    $option->delete();    
    
    Header( "Location: /trade/productedit/optionlist/$ProductID/" );
    exit();    
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ) . "/optionedit/",
                     "eztrade/admin/intl/", $Language, "optionedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "option_edit_page" => "optionedit.tpl"
    ) );



//default values
$t->set_var( "name_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "option_values", "" );
$t->set_var( "hidden_fields", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );
    
$t->set_var( "product_name", $product->name() );

if ( $Action == "Edit" )
{
    $option = new eZOption( $OptionID );
    $values = $option->values();

    $hiddenArray = "";
    $valueText = "";
    $i=0;
    foreach ( $values as $value )
    {
        $valueText .= $value->name() . "\n";
        $id = $value->id();
        $hiddenArray .= "<input type=\"hidden\" name=\"ChoiceIDArray[$i]\" value=\"$id\" />\n";
        $i++;
    }

    $t->set_var( "option_id", $OptionID );
    $t->set_var( "hidden_fields", $hiddenArray );
    $t->set_var( "name_value", $option->name() );
    $t->set_var( "description_value", $option->description() );    
    $t->set_var( "option_values", $valueText );

    $t->set_var( "action_value", "Update" );
}


$t->set_var( "product_id", $ProductID );

$t->pparse( "output", "option_edit_page" );

?>
