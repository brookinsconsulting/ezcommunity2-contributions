<?
// 
// $Id: categoryedit.php,v 1.7 2001/01/26 09:25:01 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <08-Jan-2001 11:13:29 ce>
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


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
               
$user = eZUser::currentUser();

if ( ( !$user ) || ( eZPermission::checkPermission( $user, "eZImageCatalogue", "WritePermission" ) == false ) )
{
    eZHTTPTool::header( "Location: /" );
    exit();
}

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );


$t = new eZTemplate( "ezimagecatalogue/user/" . $ini->read_var( "eZImageCatalogueMain", "TemplateDir" ),
                     "ezimagecatalogue/user/intl/", $Language, "categoryedit.php" );

$t->set_file( "category_edit_tpl", "categoryedit.tpl" );

$t->setAllStrings();

$t->set_block( "category_edit_tpl", "value_tpl", "value" );
$t->set_block( "category_edit_tpl", "errors_tpl", "errors" );

$t->set_var( "errors", "&nbsp;" );
$t->set_var( "category_name", "$Name" );
$t->set_var( "category_description", "$Description" );

if ( $Read == "User" )
    $t->set_var( "user_read_checked", "checked" );
if ( $Read == "Group" )
    $t->set_var( "group_read_checked", "checked" );
if ( $Read == "All" )
    $t->set_var( "all_read_checked", "checked" );

if ( $Write == "User" )
    $t->set_var( "user_write_checked", "checked" );
if ( $Write == "Group" )
    $t->set_var( "group_write_checked", "checked" );
if ( $Write == "All" )
    $t->set_var( "all_write_checked", "checked" );

$error = false;
$permissionCheck = true;
$nameCheck = true;
$descriptionCheck = true;
$readCheck = true;
$writeCheck = true;

if ( $Action == "Insert" || $Action == "Update" )
{
    if ( $permissionCheck )
    {
        $t->set_block( "errors_tpl", "error_write_permission", "error_write" );
        $t->set_var( "error_write", "" );

        if ( $ParentID == 0 )
        {
            if ( eZPermission::checkPermission( $user, "eZImageCatalogue", "WriteToRoot"  ) == false )
            {
                $t->parse( "error_write", "error_write_permission" );
                $error = true;
            }
        }
        else
        {
            $user = eZUser::currentUser();
            $parentCategory = new eZImageCategory( $ParentID );
            if ( $parentCategory->checkWritePermission( $user ) == false )
            {
                print( "her" );
                $t->parse( "error_write", "error_write_permission" );
                $error = true;
            }
        }
    }

    if ( $nameCheck )
    {
        $t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
        $t->set_var( "error_name", "&nbsp;" );
        
        if ( empty ( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
            $error = true;
        }
    }

    if ( $descriptionCheck )
    {
        $t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
        $t->set_var( "error_description", "&nbsp;" );
        
        if ( empty ( $Description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }

    if ( $writeCheck )
    {
        $t->set_block( "errors_tpl", "error_write_check_tpl", "error_write_check" );
        $t->set_var( "error_write_check", "&nbsp;" );
        
        if ( empty ( $Write ) )
        {
            $t->parse( "error_write_check", "error_write_check_tpl" );
            $error = true;
        }

    }

    if ( $readCheck )
    {
        $t->set_block( "errors_tpl", "error_read_check_tpl", "error_read_check" );
        $t->set_var( "error_read_check", "&nbsp;" );
        
        if ( empty ( $Read ) )
        {
            $t->parse( "error_read_check", "error_read_check_tpl" );
            $error = true;
        }

    }

    if ( $error == true )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}


if ( $Action == "Insert" && $error == false )
{
    $category = new eZImageCategory();
    $category->setName( $Name );
    $category->setDescription( $Description );

    $category->setReadPermission( $Read );
    $category->setWritePermission( $Write );
    
    $user = eZUser::currentUser();
    
    if ( !$user )
    {
        eZHTTPTool::header( "Location: /" );
        exit();
    }
    
    $category->setUser( $user );


    $parent = new eZImageCategory( $ParentID );
    $category->setParent( $parent );

    $category->store();

    eZHTTPTool::header( "Location: /imagecatalogue/image/list/$CategoryID" );
    exit();
}

if ( $Action == "Update" && $error == false )
{
    $category = new eZImageCategory( $CategoryID );
    $category->setName( $Name );
    $category->setDescription( $Description );

    $category->setReadPermission( $Read );
    $category->setWritePermission( $Write );
    
    $user = eZUser::currentUser();
    
    if ( !$user )
    {
        eZHTTPTool::header( "Location: /" );
        exit();
    }
    
    $parent = new eZImageCategory( $ParentID );
    $category->setParent( $parent );

    $category->store();

    eZHTTPTool::header( "Location: /imagecatalogue/image/list/$CategoryID" );
    exit();
  
}

if ( $Action == "Delete" && $error == false )
{
}
    

if ( $Action == "New" || $error )
{
    $t->set_var( "action_value", "insert" );
    $t->set_var( "category_id", "" );

    $t->set_var( "user_read_checked", "checked" );
    $t->set_var( "user_write_checked", "checked" );
}

if ( $Action == "Edit" )
{
    $category = new eZImageCategory( $CategoryID );

    
    $t->set_var( "category_name", $category->name() );
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_description", $category->description() );

    $write = $category->writePermission();

    if ( $write == "User" )
    {
        $t->set_var( "user_write_checked", "checked" );
    }
    else if ( $write == "Group" )
    {
        $t->set_var( "group_write_checked", "checked" );
    }
    else if ( $write == "All" )
    {
        $t->set_var( "all_write_checked", "checked" );
    }

    $read = $category->readPermission();

    if ( $read == "User" )
    {
        $t->set_var( "user_read_checked", "checked" );
    }
    else if ( $read == "Group" )
    {
        $t->set_var( "group_read_checked", "checked" );
    }
    else if ( $read == "All" )
    {
        $t->set_var( "all_read_checked", "checked" );
    }

    $t->set_var( "action_value", "update" );

}

$category = new eZImageCategory() ;

$categoryList = $category->getTree( );

if ( count ( $categoryList ) == 0 )
{
    $t->set_var( "value", "" );
}

foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "option_name", $categoryItem[0]->name() );
    $t->set_var( "option_value", $categoryItem[0]->id() );

    if ( $categoryItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $categoryItem[1] ) );
    else
        $t->set_var( "option_level", "" );

    $t->set_var( "selected", "" );
    
    if ( $category && !$CategoryID )
    {
        $CategoryID = $category->id();
    }

    if ( $CategoryID )
    {
        if ( $categoryItem[0]->id() == $CategoryID )
        {
            $t->set_var( "selected", "selected" );
        }
    }
    
    $t->parse( "value", "value_tpl", true );
}

$t->pparse( "output", "category_edit_tpl" );


?>

