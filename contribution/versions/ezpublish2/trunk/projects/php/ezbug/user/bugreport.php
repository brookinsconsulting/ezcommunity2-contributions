<?
// 
// $Id: bugreport.php,v 1.2 2000/11/29 16:51:37 bf-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Nov-2000 20:31:00 bf>
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
include_once( "classes/ezlog.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "ezbug/classes/ezbug.php" );
include_once( "ezbug/classes/ezbugcategory.php" );
include_once( "ezbug/classes/ezbugmodule.php" );

$t = new eZTemplate( "ezbug/user/" . $ini->read_var( "eZBugMain", "TemplateDir" ),
                     "ezbug/user/intl", $Language, "bugreport.php" );
$t->setAllStrings();

$t->set_file( array(
    "bug_report_tpl" => "bugreport.tpl"
    ) );

$t->set_block( "bug_report_tpl", "module_item_tpl", "module_item_tpl" );
$t->set_block( "bug_report_tpl", "category_item_tpl", "category_item_tpl" );

if ( $Action == "Insert" )
{
    $user = eZUser::currentUser();

    if ( $user )
    {
        $category = new eZBugCategory( $CategoryID );
        $module = new eZBugModule( $ModuleID );
        
        $bug = new eZBug();
        $bug->setName( $Name );
        $bug->setDescription( $Description );
        $bug->setUser( $user );
        $bug->setIsHandled( false );
        $bug->store();

        $category->addBug( $bug );
        $module->addBug( $bug );
        
        Header( "Location: /bug/reportsuccess/" );
        exit();
    }
}

$category = new eZBugCategory();
$module = new eZBugModule();

// list the categories
$categories = $category->getAll();
foreach ( $categories as $category )
{
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );

    $t->parse( "category_item", "category_item_tpl", true );
}

// list the categories
$modules = $module->getAll();
foreach ( $modules as $module )
{
    $t->set_var( "module_id", $module->id() );
    $t->set_var( "module_name", $module->name() );

    $t->parse( "module_item", "module_item_tpl", true );
}

$t->set_var( "action_value", "Insert" );

$t->pparse( "output", "bug_report_tpl" );

?>

