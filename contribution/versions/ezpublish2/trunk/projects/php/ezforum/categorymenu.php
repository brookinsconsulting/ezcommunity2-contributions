<?
/*!
    $Id: categorymenu.php,v 1.1 2000/10/13 12:52:25 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );

include_once( "classes/template.inc" );
include_once( "classes/ezdb.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$t = new Template( "." );

$t->set_file( Array( "categorylist_tpl" => "ezforum/templates/standard/categorymenu.tpl" ) );

$t->set_block( "categorylist_tpl", "category_tpl", "category" );

$category = new eZForumCategory();
$categories = $category->getAllCategories();

foreach( $categories as $category )
{
    $t->set_var("id", $category->id() );
    $t->set_var("name", $category->name() );

    $t->parse( "category", "category_tpl", true);
    
}
$t->pparse( "output", "categorylist_tpl" );
?>
