<?
//
// $Id: groupedit.php,v 1.45 2001/05/16 08:52:51 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Oct-2000 14:57:28 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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


/*
  groupedit.php 
*/

include_once( "classes/INIFile.php" );
$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZLinkMain", "Language" );
$error = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezcachefile.php" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezimagecatalogue/classes/ezimagecategory.php" );


require( "ezuser/admin/admincheck.php" );

if ( isSet ( $DeleteCategories ) )
{
    $Action = "DeleteCategories";
}

// Insert a group.
if ( $Action == "insert" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezlink/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }
    
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkGroupAdd" ) )
    {
        if ( $Title != "" &&
        $ParentCategory != "" )
        {
            $group = new eZLinkGroup();
            
            $group->setTitle( $Title );
            $group->setDescription( $Description );
            $group->setParent( $ParentCategory );
            $ttile = "";

            $file = new eZImageFile();
            if ( $file->getUploadedFile( "ImageFile" ) )
            {
                $image = new eZImage( );
                $image->setName( "Image" );
                $image->setImage( $file );

                $image->store();
                
                $group->setImage( $image );
            }
            else
            {
            }
            
            $group->store();
            eZHTTPTool::header( "Location: /link/group/". $ParentCategory );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
        eZHTTPTool::header( "Location: /link/norights" );
        exit();
    }
}

// Delete a group.
if ( $Action == "delete" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezlink/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    if ( eZPermission::checkPermission( $user, "eZLink", "LinkGroupDelete" ) )
    {
        $group = new eZLinkGroup();
        $group->get( $LinkGroupID );
        $group->delete();

        eZHTTPTool::header( "Location: /link/group/" );
        exit();
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

if ( $Action == "DeleteCategories" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezlink/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    if ( eZPermission::checkPermission( $user, "eZLink", "LinkGroupDelete" ) )
    {
        if ( count ( $CategoryArrayID ) != 0 )
        {
            foreach( $CategoryArrayID as $CategoryID )
            {
                $group = new eZLinkGroup();
                $group->get( $CategoryID );
                $parentID = $group->parent();
                $group->delete();
            }
            eZHTTPTool::header( "Location: /link/group/$parentID" );
            exit();
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

// Update a group.
if ( $Action == "update" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezlink/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }
    
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkGroupModify" ) )
    {
        if ( $Title != "" &&
        $ParentCategory != "" )
        {
            $group = new eZLinkGroup();
            $group->get ( $LinkGroupID );
            $group->setTitle ( $Title );
            $group->setDescription( $Description );
            $group->setParent( $ParentCategory );

            $file = new eZImageFile();
            if ( $file->getUploadedFile( "ImageFile" ) )
            {
                $image = new eZImage( );
                $image->setName( "Image" );
                $image->setImage( $file );
                
                $image->store();
                
                $group->setImage( $image );
            }

            $group->update();

            if ( $DeleteImage )
            {
                $group->deleteImage();
            }

            eZHTTPTool::header( "Location: /link/group/$ParentCategory" );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

$t = new eZTemplate( "ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
                     "ezlink/admin/" . "/intl/", $Language, "groupedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "group_edit" => "groupedit.tpl"
    ));

$languageIni = new INIFIle( "ezlink/admin/intl/" . $Language . "/groupedit.php.ini", false );
$headline = $languageIni->read_var( "strings", "headline_insert" );

$t->set_block( "group_edit", "parent_category_tpl", "parent_category" );
$t->set_block( "group_edit", "image_item_tpl", "image_item" );
$t->set_block( "group_edit", "no_image_item_tpl", "no_image_item" );

$groupselect = new eZLinkGroup();
$groupLinkList = $groupselect->getTree( );

if ( $Action == "new" )
{
    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkGroupAdd" ) )
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }

    $t->set_var( "image_item", "" );
    $t->set_var( "no_image_item", "" );
    $t->set_var( "category_name", "" );
    $t->set_var( "category_description", "" );
    
    $t->set_var( "action_value", "insert" );
}

// Modifing a group.
if ( $Action == "edit" )
{
    $languageIni = new INIFIle( "ezlink/admin/intl/" . $Language . "/groupedit.php.ini", false );
    $headline = $languageIni->read_var( "strings", "headline_edit" );

    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkGroupModify" ) )
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
    else
    {
        $linkGroup = new eZLinkGroup();
        $linkGroup->get ( $LinkGroupID );

        $parentID = $linkGroup->parent();
        
        $t->set_var( "category_name", $linkGroup->title() );
        $t->set_var( "category_description", $linkGroup->description() );
        $t->set_var( "category_id", $linkGroup->id() );

        $image =& $linkGroup->image();
        
        if ( get_class( $image ) == "ezimage" && $image->id() != 0 )
        {
            $imageWidth =& $ini->read_var( "eZLinkMain", "CategoryImageWidth" );
            $imageHeight =& $ini->read_var( "eZLinkMain", "CategoryImageHeight" );
            
            $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );
            
            $imageURL = "/" . $variation->imagePath();
            $imageWidth = $variation->width();
            $imageHeight = $variation->height();
            $imageCaption = $image->caption();
            
            $t->set_var( "image_width", $imageWidth );
            $t->set_var( "image_height", $imageHeight );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $imageCaption );
            $t->set_var( "no_image_item", "" );
            $t->parse( "image_item", "image_item_tpl" );
        }
        else
        {
            $t->parse( "no_image_item", "no_image_item_tpl" );
            $t->set_var( "image_item", "" );
        }

        $t->set_var( "action_value", "update" );
    }

}


// Selecter
$group_select_dict = "";
foreach( $groupLinkList as $groupLinkItem )
{
    $t->set_var( "grouplink_id", $groupLinkItem[0]->id() );
    $t->set_var( "grouplink_title", $groupLinkItem[0]->title() );
    $t->set_var( "grouplink_parent", $groupLinkItem[0]->parent() );

    if ( is_numeric( $parentID ) )
    {
        if ( $parentID == $groupLinkItem[0]->id() )
        {
            $t->set_var( "is_selected", "selected" );
        }
        else
        {
            $t->set_var( "is_selected", "" );
        }
    }

    if ( $groupLinkItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $groupLinkItem[1] ) );
    else
        $t->set_var( "option_level", "" );

    
    $group_select_dict[ $groupLinkItem[0]->id() ] = $i;

    $t->parse( "parent_category", "parent_category_tpl", true );
}

$t->set_var( "headline", $headline );

$t->set_var( "error_msg", $error_msg );

$t->pparse( "output", "group_edit" );
?>
