<?
/*!
    $Id: categorylist.php,v 1.7 2000/08/22 09:35:02 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezforumcategory.php" );

$t = new Template( "." );
$t->set_file( Array( "list" => "$DOCROOT/templates/categorylist.tpl",
                     "elements" => "$DOCROOT/templates/categorylist-elements.tpl"
                     )
              );

$t->set_var( "docroot", $DOCROOT);

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
