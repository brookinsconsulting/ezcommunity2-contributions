<?php
// 
// $Id: menucategorylist.php,v 1.1 2001/09/30 13:01:16 bf Exp $
//
// Created on: <30-Sep-2001 15:53:38 bf>
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
include_once( "classes/ezlog.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );
$ImageDir = $ini->read_var( "eZImageCatalogueMain", "ImageDir" );

$t = new eZTemplate( "ezimagecatalogue/user/" . $ini->read_var( "eZImageCatalogueMain", "TemplateDir" ),
                     "ezimagecatalogue/user/intl/", $Language, "menucategorylist.php" );

$t->set_file( "menu_category_list_tpl", "menucategorylist.tpl" );

$t->setAllStrings();

$t->set_block( "menu_category_list_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

$category = new eZImageCategory( $CategoryID );


// Print out all the categories
$categoryList =& $category->getByParent( $category );

$i=0;
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_name", $categoryItem->name() );
    $t->set_var( "category_id", $categoryItem->id() );

    $t->parse( "category", "category_tpl", true );
    $i++;
}

if ( count( $categoryList ) )
{
    $t->parse( "category_list", "category_list_tpl" );
}
else
{
    $t->set_var( "category_list", "" );
}

$limit = $ini->read_var( "eZImageCatalogueMain", "ListImagesPerPage" );

// Print out all the images
if ( isSet( $SearchText )  )
{
    $imageList =& eZImage::search( $SearchText );
    $count =& eZImage::searchCount( $SearchText );
}
else
{
    $imageList =& $category->images( "time", $Offset, $limit );
    $count =& $category->imageCount(  );
}


$i = 0;
$j = 0;
$counter = 0;


$t->pparse( "output", "menu_category_list_tpl" );

?>
