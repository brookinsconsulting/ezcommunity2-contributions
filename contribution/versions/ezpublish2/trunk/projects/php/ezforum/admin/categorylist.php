<?
/*!
    $Id: categorylist.php,v 1.8 2000/10/17 11:40:49 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:41:35 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

//include( "ezforum/dbsettings.php" );

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
  
$cat = new eZForumCategory();

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
"ezforum//admin/" . "/intl", $Language, "categorylist.php" );
$t->setAllStrings();

$t->set_file(array( "category_page" => "categorylist.tpl",
                    ) );
$t->set_block( "category_page", "category_item_tpl", "category_item" );

$t->set_var( "docroot", $DOC_ROOT );

$category = new eZForumCategory();
$categoryList = $category->getAll();

if ( !$categoryList )
{
    $ini = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/categorylist.php.ini", false );
    $noitem =  $ini->read_var( "strings", "noitem" );

    $t->set_var( "category_item", $noitem );
}
else
{
    $i=0;
    foreach( $categoryList as $categoryItem )
        {
            if ( ( $i %2 ) == 0 )
                $t->set_var( "td_class", "bgdark" );
            else
                $t->set_var( "td_class", "bglight" );

            $t->set_var( "category_id", $categoryItem->id() );
            $t->set_var( "category_name", $categoryItem->name() );
            $t->set_var( "category_description", $categoryItem->description() );

            $t->parse( "category_item", "category_item_tpl", true );
            $i++;
        }
} 

$t->pparse( "output", "category_page" );
?>
