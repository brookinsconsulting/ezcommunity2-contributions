<?
/*!
    $Id: categorylist.php,v 1.2 2000/08/09 14:12:44 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:41:35 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php" );
include_once( "template.inc" );
include_once( "../classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezforumcategory.php" );
  
$cat = new eZforumCategory();
$t = new Template( "$DOCROOT/admin/templates" );

$t->set_file(array( "category" => "category.tpl",
                    "category-add" => "category-add.tpl",
                    "category-modify" => "category-modify.tpl",
                    "listelements" => "category-list-elements.tpl"
                    ) );

$t->set_var( "docroot", $DOCROOT );
$t->set_var( "box", "" );

$categories = eZforumCategory::getAllCategories();

for ($i = 0; $i < count( $categories ); $i++)
{
    arrayTemplate( $t, $categories[$i], Array( Array("Id", "list-Id" ),
                                               Array("Name", "list-Name" ),
                                               Array("Description", "list-Description" ),
                                               Array("Private", "list-Private" )
                                               )
                   );

    $t->set_var( "color", switchColor( $i, "#f0f0f0", "#dcdcdc" ) );

    $t->parse("categories","listelements",true);
}

$t->pparse("output", "category");
?>
