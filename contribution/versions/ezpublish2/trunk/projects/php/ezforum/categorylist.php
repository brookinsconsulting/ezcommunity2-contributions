<?
/*!
    $Id: categorylist.php,v 1.13 2000/10/13 12:52:25 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdb.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$t = new eZTemplate( $DOC_ROOT . $ini->read_var( "eZForumMain", "TemplateDir" ),
$DOC_ROOT . "/intl", $Language, "categorylist.php" );
$t->setAllStrings();

$t->set_file( Array( "categorylist_tpl" => "categorylist.tpl" ) );

$t->set_block( "categorylist_tpl", "category_tpl", "category" );

$category = new eZForumCategory();
$categories = $category->getAllCategories();

$i=0;
foreach( $categories as $categoryItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );

    $t->set_var("id", $categoryItem->id() );
    $t->set_var("name", $categoryItem->name() );
    $t->set_var("description", $categoryItem->description() );
    $i++;
    
    $t->parse( "category", "category_tpl", true);
}

$t->pparse( "output", "categorylist_tpl" );
?>
