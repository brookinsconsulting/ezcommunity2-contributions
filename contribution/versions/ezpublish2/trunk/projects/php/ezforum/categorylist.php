<?
/*!
    $Id: categorylist.php,v 1.9 2000/09/01 13:39:55 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/class.INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );


include_once( "classes/template.inc" );
include_once( "classes/ezdb.php" );
include_once( "$DOC_ROOT/classes/ezforumcategory.php" );

$t = new Template( "." );
$t->set_file( Array( "list" => "$DOC_ROOT/templates/categorylist.tpl",
                     "elements" => "$DOC_ROOT/templates/categorylist-elements.tpl"
                     )
              );

$t->set_var( "docroot", $DOC_ROOT);

$category = new eZForumCategory();
$categories = $category->getAllCategories();

for ($i = 0; $i < count( $categories ); $i++ )
{
    $t->set_var("id", $categories[$i]["Id"] );
    $t->set_var("name", $categories[$i]["Name"] );

    $t->parse( "categories", "elements", true);
}
$t->pparse( "output", "list" );
?>
