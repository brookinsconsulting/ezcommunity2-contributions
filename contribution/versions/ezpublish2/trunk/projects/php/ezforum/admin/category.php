<?
/*!
    $Id: category.php,v 1.7 2000/07/28 10:05:51 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:41:35 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
// zez/admin/
// zez/ezforum/
include( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezforumcategory.php" );
  
$cat = new eZforumCategory();
$t = new Template(".");

$t->set_file(array( "category" => "$DOCROOT/admin/templates/category.tpl",
                    "category-add" => "$DOCROOT/admin/templates/category-add.tpl",
                    "category-modify" => "$DOCROOT/admin/templates/category-modify.tpl",
                    "listelements" => "$DOCROOT/admin/templates/category-list-elements.tpl"
                    ) );
$t->set_var( "docroot", $DOCROOT);

if ($add)
{
    if ($category_id)
    {
        $cat->Id = $category_id;
    }
           
    $cat->setName($Name);
    $cat->setDescription($Description);
    
    if ($Private)
        $cat->setPrivate("Y");
    else
        $cat->setPrivate("N");
    
    $cat->store();
}
  
if ($action == "delete")
{
    $cat->delete($category_id);
}

if ($action == "modify")
{
    $cat->get($category_id);
}

$t->set_var("Id", $Id);
    
if ($action == "modify")
{
    
    $t->set_var("category-name", $cat->name() );
    $t->set_var("category-description", $cat->description() );
    if ($cat->private() == "Y")
        $t->set_var("category-private", "checked");
    else
        $t->set_var("category-private", "");
    
    $t->parse("box", "category-modify", true);
}
else
{
    $t->parse("box", "category-add", true);
}

    
$cat = new eZforumCategory();
$categories = $cat->getAllCategories();

for ($i = 0; $i < count($categories); $i++)
{
    $Id = $categories[$i]["Id"];
    $Name = $categories[$i]["Name"];
    $Description = $categories[$i]["Description"];
    $Private = $categories[$i]["Private"];
    
    $t->set_var("list-Id", $Id);
    $t->set_var("list-Name", $Name);
    $t->set_var("list-Description", $Description);
    $t->set_var("list-Private", $Private);

    if ( ($i % 2) != 0)
        $t->set_var( "color", "#eeeeee" );
    else
        $t->set_var( "color", "#bbbbbb" );

    $t->parse("categories","listelements",true);
}

$t->pparse("output", "category");
?>
