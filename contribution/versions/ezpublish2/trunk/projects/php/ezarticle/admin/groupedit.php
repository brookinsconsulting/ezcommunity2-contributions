<?
// 
// $Id: groupedit.php,v 1.6 2000/10/28 12:29:01 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Sep-2000 14:46:19 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

if ( isset( $Cancel ) )
{
    Header( "Location: /article/archive/$categoryID/" );
    exit();
}

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

include_once( "ezarticle/classes/ezarticlecategory.php" );

// Direct actions
if ( $Action == "insert" )
{
    $parentCategory = new eZArticleCategory();
    $parentCategory->get( $ParentID );

    $category = new eZArticleCategory();
    $category->setName( $Name );
    $category->setParent( $parentCategory );
    $category->setDescription( $Description );

    if ( $ExcludeFromSearch == "on" )
    {
        $category->setExcludeFromSearch( true );
    }
    else
    {
        $category->setExcludeFromSearch( false );
    }
    
    $category->store();

    $categoryID = $category->id();

    Header( "Location: /article/archive/$categoryID/" );
    exit();
}

if ( $Action == "update" )
{
    $parentCategory = new eZArticleCategory();
    $parentCategory->get( $ParentID );
    
    $category = new eZArticleCategory();
    $category->get( $CategoryID );
    $category->setName( $Name );
    $category->setParent( $parentCategory );
    $category->setDescription( $Description );

    if ( $ExcludeFromSearch == "on" )
    {
        $category->setExcludeFromSearch( true );
    }
    else
    {
        $category->setExcludeFromSearch( false );
    }
    
    $category->store();

    $categoryID = $category->id();

    Header( "Location: /article/archive/$categoryID/" );
    exit();
}

if ( $Action == "delete" )
{
    $category = new eZArticleCategory();
    $category->get( $CategoryID );

    $category->delete();
    
    Header( "Location: /article/archive/" );
    exit();
}

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "categoryedit.php" );

$t->setAllStrings();

$t->set_file( array( "category_edit_tpl" => "categoryedit.tpl" ) );


$t->set_block( "category_edit_tpl", "value_tpl", "value" );
               
$category = new eZArticleCategory();

$categoryArray = $category->getAll( );

$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "action_value", "insert" );
$t->set_var( "exclude_checked", "" );

// edit
if ( $Action == "edit" )
{
    $category = new eZArticleCategory();
    $category->get( $CategoryID );

    $t->set_var( "name_value", $category->name() );
    $t->set_var( "description_value", $category->description() );
    $t->set_var( "action_value", "update" );
    $t->set_var( "category_id", $category->id() );

    if ( $category->excludeFromSearch() == true )
    {
        $t->set_var( "exclude_checked", "checked" );
    }
}

foreach ( $categoryArray as $catItem )
{
    if ( $CategoryID != $catItem->id() )
    {
        $t->set_var( "option_value", $catItem->id() );
        $t->set_var( "option_name", $catItem->name() );

        $t->parse( "value", "value_tpl", true );
    }
}

$t->pparse( "output", "category_edit_tpl" );

?>
