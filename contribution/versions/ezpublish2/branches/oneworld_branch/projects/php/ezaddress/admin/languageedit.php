<?php
//
// $Id: languageedit.php,v 1.1.2.1 2002/06/04 11:25:47 br Exp $
//
// <Bjørn Reiten> <br@ez.no>
// Created on: <15-May-2002 16:40:32 br>
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

include_once( "ezaddress/classes/ezlanguage.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

if ( isSet( $Cancel ) )
{
    eZHTTPTool::header( "Location: /address/language/list/$Offset" );
    exit();
}

if ( isSet( $OK ) )
{
    $language = new eZLanguage( $LanguageID );
    $language->setName( $LanguageName );
    $language->store();

    if ( $Action == "new" )
    {
        $id = $language->id();
        $Offset = $language->getCountByID( $id );
        $AdminListLimit =& $ini->read_var( "eZAddressMain", "MaxCountryList" );
        
        if ( is_Numeric( $AdminListLimit ) && $AdminListLimit > 0 )
        {
            $rest = $Offset % $AdminListLimit;
            $Offset -= $rest;
        }
    }

    eZHTTPTool::header( "Location: /address/language/list/$Offset" );
    exit();
}


$t = new eZTemplate( "ezaddress/admin/" . $ini->read_var( "eZAddressMain", "AdminTemplateDir" ),
"ezaddress/admin/intl/", $Language, "languageedit.php" );
$t->setAllStrings();

$t->set_file( "language_edit_tpl", "languageedit.tpl" );
$t->set_var( "language_name", "" );
$t->set_var( "language_id", $LanguageID );
$t->set_var( "offset", $Offset );
$t->set_var( "action_value", $Action );

$language = new eZLanguage( $LanguageID );

$t->set_var( "language_name", $language->name() );

$t->pparse( "output", "language_edit_tpl" );
?>
