<?
// 
// $Id: separator.php,v 1.10 2001/03/01 14:06:24 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Jan-2001 16:06:07 bf>
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

include_once( "classes/INIFile.php" );
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZ" . ucfirst( $moduleName ) . "Main", "Language" );

include_once( "classes/eztemplate.php" );

$t = new eZTemplate( "templates/" . $SiteStyle,
                     "ez" . $moduleName . "/admin/intl/", $Language, "menubox.php" );


$t->set_file( array(
    "separator_tpl" => "separator.tpl"
    ) );

$t->set_var( "site_style", $SiteStyle );

$t->set_var( "module_name", $moduleName );


$t->setAllStrings();

$t->pparse( "output", "separator_tpl" );
    

?>

