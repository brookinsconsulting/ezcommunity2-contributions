<?php
// 
// $Id: rfplist.php,v 1.50.2.4 2002/04/24 07:35:26 jhe Exp $
//
// Created on: <18-Oct-2000 14:41:37 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfptool.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );
$Locale = new eZLocale( $Language );
$AdminListLimit = $ini->read_var( "eZRfpMain", "AdminListLimit" );

$session =& eZSession::globalSession();

if ( isSet( $GoTo ) && is_Numeric( $GoToCategoryID ) )
{
    eZHTTPTool::header( "Location: /rfp/archive/$GoToCategoryID" );
    exit();
}

if ( isSet( $StoreSelection ) )
{
    switch ( $RfpSelection )
    {
        case "Published" :
        {
            $session->setVariable( "MixUnpublished", "Published" ); 
        }
        break;

        case "Unpublished" :
        {
            $session->setVariable( "MixUnpublished", "Unpublished" ); 
        }
        break;
        
        case "All" :
        default  :
        {
            $session->setVariable( "MixUnpublished", "All" ); 
        }        
    }
}

$rfpMix =& $session->variable( "MixUnpublished" );

$RfpSelection =& $rfpMix;

if ( $rfpMix == "" )
{
    $rfpMix = "All";
}

if ( isset( $CopyCategories ) )
{
    if ( count( $CategoryArrayID ) != 0 )
    {
        foreach ( $CategoryArrayID as $tCategoryID )
        {
            // copy category
            $tmpCategory = new eZRfpCategory( $tCategoryID );

            $newCategory = new eZRfpCategory();
            $newCategory->setName( "Copy of " . $tmpCategory->name() );            
            $newCategory->setDescription( $tmpCategory->description(false) );
            $newCategory->setParent( $tmpCategory->parent( false ) );
            $newCategory->setOwner( eZUser::currentUser() );

            $newCategory->store();

            // write access
            eZObjectPermission::setPermission( -1, $newCategory->id(), "rfp_category", 'w' );

            // read access 
            eZObjectPermission::setPermission( -1, $newCategory->id(), "rfp_category", 'r' );
            

            $tmpCategory->copyTree( $tCategoryID, $newCategory );
        }
        eZHTTPTool::header( "Location: /rfp/archive/" );
        exit();
    }    
}




if ( isSet( $DeleteRfps ) )
{
    if ( count( $RfpArrayID ) != 0 )
    {
        foreach ( $RfpArrayID as $TRfpID )
        {
            if ( eZObjectPermission::hasPermission( $TRfpID, "rfp_rfp", 'w' ) ||
                 eZRfp::isAuthor( eZUser::currentUser(), $TRfpID ) )
            {
                $rfp = new eZRfp( $TRfpID );

                // get the category to redirect to
                $rfpID = $rfp->id();

                $categoryArray =& $rfp->categories();
                $categoryIDArray = array();
                foreach ( $categoryArray as $cat )
                {
                    $categoryIDArray[] = $cat->id();
                }
                $categoryID = $rfp->categoryDefinition();
                $categoryID = $categoryID->id();

                // clear the cache files.
                deleteCache( $TRfpID, $categoryID, $categoryIDArray );
                $rfp->delete();
            }
        }
	
        eZHTTPTool::header( "Location: /rfp/archive/$CurrentCategoryID" );
        exit();
    }
}

if ( isset( $DeleteCategories ) )
{
    if ( count( $CategoryArrayID ) != 0 )
    {
        /** Delete menubox cache **/
        $files =& eZCacheFile::files( "ezrfp/cache/",
                                 array( "menubox", NULL ),
                                 "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }

        $categories = array();
        foreach ( $CategoryArrayID as $ID )
        {
            $categories[] = $ID;
            $category = new eZRfpCategory( $ID );
            $categories[] = $category->parent( false );
            if ( eZObjectPermission::hasPermission( $ID , "rfp_category", 'w' ) ||
                 eZRfpCategory::isOwner( eZUser::currentUser(), $ID ) )
                $category->delete();
        }
        $categories = array_unique( $categories );
        $files =& eZCacheFile::files( "ezrfp/cache/",
                                      array( "rfplist",
                                             $categories, NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
    }

    eZHTTPTool::header( "Location: /rfp/archive/" );
    exit();
}


$t = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                     "ezrfp/admin/intl/", $Language, "rfplist.php" );

$t->setAllStrings();

$t->set_file( array(
    "rfp_list_page_tpl" => "rfplist.tpl"
    ) );

// path
$t->set_block( "rfp_list_page_tpl", "path_item_tpl", "path_item" );

// category selector

$t->set_block( "rfp_list_page_tpl", "category_tree_id_tpl", "category_tree_id" );

// category
$t->set_block( "rfp_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );
$t->set_block( "category_item_tpl", "category_edit_tpl", "category_edit" );

// rfp
$t->set_block( "rfp_list_page_tpl", "rfp_list_tpl", "rfp_list" );
$t->set_block( "rfp_list_tpl", "rfp_item_tpl", "rfp_item" );

$t->set_block( "rfp_item_tpl", "rfp_is_published_tpl", "rfp_is_published" );
$t->set_block( "rfp_item_tpl", "rfp_not_published_tpl", "rfp_not_published" );

// move up / down
$t->set_block( "rfp_list_tpl", "absolute_placement_header_tpl", "absolute_placement_header" );
$t->set_block( "rfp_item_tpl", "absolute_placement_item_tpl", "absolute_placement_item" );
$t->set_block( "rfp_item_tpl", "rfp_edit_tpl", "rfp_edit" );


$t->set_var( "site_style", $SiteStyle );

$category = new eZRfpCategory( $CategoryID );

/** move rfp categories up/down **/
if ( is_numeric( $MoveCategoryUp ) || is_numeric( $MoveCategoryDown ) )
{
    if ( is_numeric( $MoveCategoryUp ) )
    {
        $mvcategory = new eZRfpCategory( $MoveCategoryUp );
        $mvcategory->moveCategoryUp();
    }

    if ( is_numeric( $MoveCategoryDown ) )
    {
        $mvcategory = new eZRfpCategory( $MoveCategoryDown );
        $mvcategory->moveCategoryDown();
    }

    /** Clear cache when moving stuff arround **/
    $files =& eZCacheFile::files( "ezrfp/cache/",
                                 array( "menubox", NULL ),
                                 "cache", "," );
    
    foreach ( $files as $file )
    {
        $file->delete();
    }
    $files =& eZCacheFile::files( "ezrfp/cache/",
                                 array( "rfplist", $CategoryID, NULL, NULL ), "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }

}
// move rfps up / down
if ( $category->sortMode() == "absolute_placement" )
{
    if ( is_numeric( $MoveUp ) )
    {
        $category->moveUp( $MoveUp );
    }

    if ( is_numeric( $MoveDown ) )
    {
        $category->moveDown( $MoveDown );
    }
}

$t->set_var( "current_category_id", $category->id() );

//EP: CategoryDescriptionXML=enabled, description go in XML -------------------
if ( $ini->read_var( "eZRfpMain", "CategoryDescriptionXML" ) == "enabled" )
{
    if ( $CategoryID )
    {
        include_once( "ezrfp/classes/ezrfprenderer.php" );
    
        $rfp = new eZRfp();
        $rfp->setContents( $category->description( false ) );
	    
        $renderer = new eZRfpRenderer( $rfp );
		
        $t->set_var( "current_category_description", $renderer->renderIntro() );
    }
    else
    {
        $t->set_var( "current_category_description", "" );
    }
}
else
{
    $t->set_var( "current_category_description", $category->description() );    
}	
//EP --------------------------------------------------------------------------

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );
    $t->set_var( "category_name", $path[1] );
    $t->parse( "path_item", "path_item_tpl", true );
}

$categoryList =& $category->getByParent( $category, true, "placement" );

// category "tree" selector
$tree = new eZRfpCategory();
$treeArray =& $tree->getTree();

foreach ( $treeArray as $catItem )
{
    $t->set_var( "category_id", $catItem[0]->id() );
    $t->set_var( "category_name", $catItem[0]->name() );

    if ( $catItem[1] > 1 )
        $t->set_var( "category_level", str_repeat( "&nbsp;&nbsp;", $catItem[1] ) );
    else
        $t->set_var( "category_level", "" );

    $t->set_var( "selected", $catItem[0]->id() == $CategoryID ? "selected" : "" );
    
    $t->parse( "category_tree_id", "category_tree_id_tpl", true );    
}


// categories
$i = 0;
$t->set_var( "category_list", "" );
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_name", $categoryItem->name() );

    $parent = $categoryItem->parent();

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    //EP: CategoryDescriptionXML=enabled, description go in XML -------------------
    if ( $ini->read_var( "eZRfpMain", "CategoryDescriptionXML" ) == "enabled" )
    {
        include_once( "ezrfp/classes/ezrfprenderer.php" );
       
        $rfp = new eZRfp ();
        $rfp->setContents ($categoryItem->description(false));
	       
        $renderer = new eZRfpRenderer( $rfp );
		   
        $t->set_var( "category_description", $renderer->renderIntro() );
    }
    else
    {
        $t->set_var( "category_description", $categoryItem->description() );
    }       
    //EP --------------------------------------------------------------------------

    if ( eZObjectPermission::hasPermission( $categoryItem->id(), "rfp_category", 'w' ) ||
         eZRfpCategory::isOwner( eZUser::currentUser(), $categoryItem->id() ) )
        $t->parse( "category_edit", "category_edit_tpl", false );
    else
        $t->set_var( "category_edit", "" );
        
    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}

$t->set_var( "archive_id", $CategoryID );

if ( $i > 0 )
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


// set the offset/limit
if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $Limit ) )
    $Limit = $AdminListLimit;

switch ( $rfpMix )
{
    case "Published" :
    {
        $t->set_var( "published_selected", "selected" );
        $t->set_var( "un_published_selected", "" );
        $t->set_var( "all_selected", "" );
    }
    break;

    case "Unpublished" :
    {
        $t->set_var( "published_selected", "" );
        $t->set_var( "un_published_selected", "selected" );
        $t->set_var( "all_selected", "" );
    }
    break;
        
    case "All" :
    default  :
    {
        $t->set_var( "published_selected", "" );
        $t->set_var( "un_published_selected", "" );
        $t->set_var( "all_selected", "selected" );
    }
}


// rfps
if ( is_numeric( $CategoryID ) && ( $CategoryID > 0 ) )
{
    switch ( $RfpSelection )
    {
       
        case "Published" :
        {
            $rfpList =& $category->rfps( $category->sortMode(), false, true, $Offset, $Limit );
            $rfpCount = $category->rfpCount( false, true  );        
        }
        break;

        case "Unpublished" :
        {
            $rfpList =& $category->rfps( $category->sortMode(), false, false, $Offset, $Limit );
            $rfpCount = $category->rfpCount( false, false  );
        }
        break;
        
        case "All" :
        default  :
        {
            $rfpList =& $category->rfps( $category->sortMode(), true, true, $Offset, $Limit );
            $rfpCount = $category->rfpCount( true, true  );        
        }
    }
}
else
{
    $rfpList = array();
    $rfpCount = 0;
}

$i = 0;
$t->set_var( "rfp_list", "" );

if ( $category->sortMode() == "absolute_placement" )
{
    $t->parse( "absolute_placement_header", "absolute_placement_header_tpl" );
}
else
{
    $t->set_var( "absolute_placement_header", "" );
}

$locale = new eZLocale( $Language );

foreach ( $rfpList as $rfp )
{
    if ( eZObjectPermission::hasPermission( $rfp->id(), "rfp_rfp", 'r' ) ||
         eZRfp::isAuthor( eZUser::currentUser(), $rfp->id() ) )
    {
        if ( $rfp->name() == "" )
            $t->set_var( "rfp_name", "&nbsp;" );
        else
            $t->set_var( "rfp_name", $rfp->name() );

        $t->set_var( "rfp_id", $rfp->id() );

        if ( $rfp->isPublished() == true )
        {
            $t->parse( "rfp_is_published", "rfp_is_published_tpl" );
            $t->set_var( "rfp_not_published", "" );        
        }
        else
        {
            $t->set_var( "rfp_is_published", "" );
            $t->parse( "rfp_not_published", "rfp_not_published_tpl" );
        }

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        if ( $category->sortMode() == "absolute_placement" )
        {
            $t->parse( "absolute_placement_item", "absolute_placement_item_tpl" );
        }
        else
        {
            $t->set_var( "absolute_placement_item", "" );
        }

        $published = $rfp->published();
        $t->set_var( "rfp_published_date", $locale->format( $published ) );

        if( eZObjectPermission::hasPermission( $rfp->id(), "rfp_rfp", 'w') ||
            eZRfp::isAuthor( eZUser::currentUser(), $rfp->id() ) )
            $t->parse( "rfp_edit", "rfp_edit_tpl", false );
        else
            $t->set_var( "rfp_edit", "" );


        $t->parse( "rfp_item", "rfp_item_tpl", true );
        $i++;
    }
}
eZList::drawNavigator( $t, $rfpCount, $AdminListLimit, $Offset, "rfp_list_page_tpl" );

// $i is from the last foreach loop
if ( $i > 0 )    
    $t->parse( "rfp_list", "rfp_list_tpl" );
else
    $t->set_var( "rfp_list", "" );


$t->pparse( "output", "rfp_list_page_tpl" );

/*!
  Delete cache.
*/
function deleteCache( $RfpID, $CategoryID, $CategoryArray )
{    
    eZRfpTool::deleteCache( $RfpID, $CategoryID, $CategoryArray );
}

?>
