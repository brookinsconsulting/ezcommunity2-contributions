<?
/*!
    $Id: categorylist.php,v 1.3 2000/07/26 13:20:35 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezforumcategory.php" );

$t = new Template( "." );
$t->set_file( Array( "list" => "$DOCROOT/templates/categorylist.tpl",
                     "elements" => "$DOCROOT/templates/main-elements.tpl"
                     )
              );

$t->set_var( "docroot", $DOCROOT);
$cat = new eZforumCategory;
$categories = $cat->getAllCategories();


for ($i = 0; $i < count( $categories ); $i++ )
{
    $Id = $categories[$i]["Id"];
    $Name = $categories[$i]["Name"];
    $Description = $categories[$i]["Description"];
        
    $t->set_var("id", $Id);
    $t->set_var("name", $Name);
    $t->set_var("link",$link);
    $t->set_var("description",$Description);
 
   // setter ikke lenger bakgrunnsfarge.
    if ( ($i % 2) != 0)
        $t->set_var( "color", "" );
    else
        $t->set_var( "color", "" );
            
    $t->parse( "categories", "elements", true );
}
$t->pparse( "output", "list" );
?>
