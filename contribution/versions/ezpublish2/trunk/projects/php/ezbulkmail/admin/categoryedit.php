<?
// 
// $Id: categoryedit.php,v 1.1 2001/04/18 09:35:21 fh Exp $
//
// Frederik Holljen <fh@ez.no>
// Created on: <18-Apr-2001 11:15:33 fh>
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

include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );

if( isset( $Cancel ) ) // cancel pressed, redirect to categorylist page...
{
    eZHTTPTool::header( "Location: /bulkmail/categorylist/" );
    exit();
}

if( isset( $Ok ) ) // cancel pressed, redirect to categorylist page...
{
    if( $CategoryID == 0 )
    {
        $category = new eZBulkMailCategory();
    }
    else
    {
        $category = new eZBulkMailCategory( $CategoryID );
    }
    $category->setDescription( $Description );
    $category->setName( $Name );
    $category->store();
    eZHTTPTool::header( "Location: /bulkmail/categorylist/" );
    exit();
}


$t = new eZTemplate( "ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "ezbulkmail/admin/intl", $Language, "categoryedit.php" );
$errorIni = new INIFIle( "ezbulkmail/admin/intl/" . $Language . "/categoryedit.php.ini", false );

$t->set_file( array(
    "category_edit_tpl" => "categoryedit.tpl"
    ) );

$t->setAllStrings();
$t->set_var( "site_style", $SiteStyle );

$t->set_var( "category_name", "" );
$t->set_var( "description", "" );
$t->set_var( "category_id", $CategoryID );

if( $CategoryID != 0  )
{
    $category = new eZBulkMailCategory( $CategoryID );
    if( is_object( $category ) )
    {
        $t->set_var( "category_name", $category->name() );
        $t->set_var( "description", $category->description() );
    }
}

$t->pparse( "output", "category_edit_tpl" );
?>
