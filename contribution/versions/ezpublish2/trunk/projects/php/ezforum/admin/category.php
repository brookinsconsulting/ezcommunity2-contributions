<?
/*!
    $Id: category.php,v 1.17 2000/10/12 11:00:29 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:41:35 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "classes/ezdb.php" );
include_once( "classes/eztemplate.php" );

include_once( "ezforum/classes/ezforumcategory.php" );

$cat = new eZforumCategory();
$t = new Template( $DOC_ROOT . "admin/templates" );

$t->set_file(Array( "category" => "category.tpl",
                    "category-add" => "category-add.tpl",
                    "category-modify" => "category-modify.tpl",
                    "listelements" => "category-list-elements.tpl"
                    ) );

$t->set_var( "docroot", $DOC_ROOT );


$t->set_var("category_id", $category_id );
    

$category = new eZforumCategory();
$categories = $category->getAllCategories();

//$categories = eZforumCategory::getAllCategories();

for ($i = 0; $i < count( $categories ); $i++)
{
    arrayTemplate( $t, $categories[$i], Array( Array("Id", "list-Id" ),
                                               Array("Name", "list-Name" ),
                                               Array("Description", "list-Description" ),
                                               Array("Private", "list-Private" )
                                               )
                 );

    $t->set_var( "color", switchColor( $i, "#f0f0f0", "#dcdcdc" ) );
    $t->parse( "categories", "listelements", true );
}

$t->pparse("output", "category");
?>
