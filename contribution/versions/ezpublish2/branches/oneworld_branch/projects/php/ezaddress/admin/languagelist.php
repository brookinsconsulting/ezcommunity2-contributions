<?php
//
// $Id: languagelist.php,v 1.1.2.1 2002/06/04 11:25:47 br Exp $
//
// <Bjørn Reiten> <br@ez.no>
// Created on: <15-May-2002 12:13:16 br>
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

include_once( "classes/ezlist.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezaddress/classes/ezlanguage.php" );

$ini =& INIFile::globalINI();

$AdminListLimit =& $ini->read_var( "eZAddressMain", "MaxCountryList" );
$Offset = $Index;

if ( isSet( $NewLanguage ) )
{
    eZHTTPTool::header( "Location: /address/language/new" );
    exit();
}

if ( isSet( $DeleteLanguages ) )
{
    if ( count( $LanguageArrayID ) > 0 )
    {
        foreach ( $LanguageArrayID as $languageID )
        {
            $language = new eZLanguage( $languageID );
            $language->delete();
        }
    }
    
    eZHTTPTool::header( "Location: /address/language/list/$Offset" );
    exit();
}



$t = new eZTemplate( "ezaddress/admin/" . $ini->read_var( "eZAddressMain", "AdminTemplateDir" ),
"ezaddress/admin/intl/", $Language, "languagelist.php" );
$t->setAllStrings();

$t->set_file( "language_list_tpl", "languagelist.tpl" );

$t->set_block( "language_list_tpl", "name_list_tpl", "name_list"  );
$t->set_block( "name_list_tpl", "name_item_tpl", "name_item"  );

$t->set_var( "name_list", "" );

$t->set_var( "offset", $Offset );

$countryNames =& eZLanguage::getAllArray( $Offset, $AdminListLimit );
$Count = eZLanguage::getAllCount();

if ( count( $countryNames ) > 0 )
{
    $i=0;
    foreach ( $countryNames as $countryName )
    {
        if ( ( $i++ %2 ) == 0 )
            $t->set_var( "bg_color", "bglight" );
        else
            $t->set_var( "bg_color", "bgdark" );

        $t->set_var( "language_name", $countryName["Name"] );
        $t->set_var( "language_id", $countryName["ID"] );
        $t->parse( "name_item", "name_item_tpl", true );
    }
    $t->parse( "name_list", "name_list_tpl" );
}
eZList::drawNavigator( $t, $Count, $AdminListLimit, $Offset, "language_list_tpl" );

$t->pparse( "output", "language_list_tpl" );
?>
