<?php
//
// $Id: category.php,v 1.15 2001/10/26 13:42:04 jb Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

// eZ article complete data
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

// TODO: check permissions!!

if( $Command == "info" )
{
    $category = new eZArticleCategory();
    if ( !$category->get( $ID ) )
    {
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
    else
    {
        $ret = array( "Name" => new eZXMLRPCString( $category->name( false ) ) );
        $ReturnData = new eZXMLRPCStruct( $ret );
    }
}
else if( $Command == "data" ) // Dump category info!
{
//      usleep( 5000000 );
    $writeGroups = eZObjectPermission::getGroups( $ID, "article_category", 'w', false );
    $readGroups = eZObjectPermission::getGroups( $ID, "article_category", 'r', false );

    foreach( $readGroups as $group )
        $rgp[] = new eZXMLRPCInt( $group );
    foreach( $writeGroups as $group )
        $wgp[] = new eZXMLRPCInt( $group );

    $category = new eZArticleCategory( $ID );
    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "category", $category->id() ),
                                             "Name" => new eZXMLRPCString( $category->name( false ) ),
                                             "ParentID" => new eZXMLRPCInt( $category->parent( false ) ),
                                             "Description" => new eZXMLRPCString( $category->description( false ) ),
                                             "ExcludeFromSearch" => new eZXMLRPCBool( $category->excludeFromSearch() ),
                                             "SortMode" => new eZXMLRPCInt( $category->sortMode( true ) ),
                                             "OwnerID" => new eZXMLRPCInt( $category->owner( false ) ),
                                             "SectionID" => new eZXMLRPCInt( $category->sectionIDStatic( $ID ) ),
                                             "ImageID" => new eZXMLRPCInt( $category->image( false ) ),
                                             "BulkMailID" => new eZXMLRPCInt( $category->bulkMailCategory(false ) ),
                                             "ReadGroups" => new eZXMLRPCArray( $rgp ),
                                             "WriteGroups" => new eZXMLRPCArray( $wgp )
                                             )
                                      );
}
else if( $Command == "storedata" ) // save the category data!
{
//      eZLog::writeNotice( "article category $ID" );

    if( $ID == 0 )
        $category = new eZArticleCategory();
    else
    {
        $category = new eZArticleCategory( $ID );
        $category->setOwner( $User );
    }
    $category->setName( $Data["Name"]->value() );
    $category->setDescription( $Data["Description"]->value() );

    $parent = new eZArticleCategory();
    $parentid = $Data["ParentID"]->value();
    if ( $parentid == 0 or $parent->get( $parentid ) )
    {
        $old_category = $category->parent();
        $old_category_arr = is_object( $old_category ) ? array( $old_category->id() ) : array();
        $category->setParent( $parent );
        $category->setExcludeFromSearch( $Data["ExcludeFromSearch"]->value() );

        $category->setBulkMailCategory( $Data["BulkMailID"]->value() );
        $category->setSortMode( $Data["SortMode"]->value() );
        $category->setSectionID( $Data["SectionID"]->value() );
        $category->setImage( $Data["ImageID"]->value() );
        $category->store();
        $ID = $category->id();

        eZObjectPermission::removePermissions( $ID, "article_category", 'r' );
        $readGroups = $Data["ReadGroups"]->value();
        foreach( $readGroups as $readGroup )
        {
            eZObjectPermission::setPermission( $readGroup->value(), $ID, "article_category", 'r' );
        }

        eZObjectPermission::removePermissions( $ID, "article_category", 'w' );
        foreach( $Data["WriteGroups"]->value() as $writeGroup )
            eZObjectPermission::setPermission( $writeGroup->value(), $ID, "article_category", 'w' );

        $par =& createPath( $category, "ezarticle", "category", false );

        $add_categories = array();
        $cur_categories = array();
        $remove_categories = array();
        $add_categories = array_diff( array( $parent->id() ), $old_category_arr );
        $remove_categories = array_diff( $old_category_arr, array( $parent->id() ) );
        $cur_categories = array_intersect( array( $parent->id() ), $old_category_arr );

        $add_locs =& createURLArray( $add_categories, "ezarticle", "category" );
        $cur_locs =& createURLArray( $cur_categories, "ezarticle", "category" );
        $old_locs =& createURLArray( $remove_categories, "ezarticle", "category" );

        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "category", $ID ),
                                                 "Name" => new eZXMLRPCString( $category->name( false ) ),
                                                 "Path" => new eZXMLRPCArray( $par ),
                                                 "NewLocations" => $add_locs,
                                                 "ChangedLocations" => $cur_locs,
                                                 "RemovedLocations" => $old_locs,
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                                 )
                                          );
        $Command = "update";
    }
    else
    {
        $Error = createErrorMessage( EZERROR_CUSTOM, "Parent ($parentid) of $Module:/$RequestType/$ID is not an existing parent",
                                     EZARTICLE_NONEXISTING_PARENT );
    }
}
else if( $Command == "delete" )
{
    // create the path array
    $category = new eZArticleCategory( $ID );
    $path =& $category->path();
    if ( $category->id() != 0 )
    {
        $par[] = createURLStruct( "ezarticle", "category", 0 );
    }
    else
    {
        $par[] = createURLStruct( "ezarticle", "" );
    }
    foreach( $path as $item )
    {
        if ( $item[0] != $category->id() )
            $par[] = createURLStruct( "ezarticle", "category", $item[0] );
    }


    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "category", $ID ),
                                             "Path" => new eZXMLRPCArray( $par ),
                                             "UpdateType" => new eZXMLRPCString( $Command )
                                             )
                                      );
    $Command = "update";
    eZArticleCategory::delete( $ID ); // finally, delete the articlecategory..
}

?>
