<?
/*!
    $Id: categorylist.php,v 1.12 2000/10/11 14:27:11 bf-cvs Exp $

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

$t->set_file( Array( "categorylist_tpl" => "ezforum/templates/categorylist.tpl" ) );

$t->set_block( "categorylist_tpl", "category_tpl", "category" );

$category = new eZForumCategory();
$categories = $category->getAllCategories();

for ( $i = 0; $i < count( $categories ); $i++ )
{
    $t->set_var("id", $categories[$i]["Id"] );
    $t->set_var("name", $categories[$i]["Name"] );

    $t->parse( "category", "category_tpl", true);
}
$t->pparse( "output", "categorylist_tpl" );
?>
