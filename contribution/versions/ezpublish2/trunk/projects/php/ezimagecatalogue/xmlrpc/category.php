<?php
//
// $Id: category.php,v 1.1 2001/09/25 08:10:32 jb Exp $
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
include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

// TODO: check permissions!!

if( $Command == "data" ) // Dump category info!
{
//      usleep( 5000000 );
    $writeGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_category", 'w', false );
    $readGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_category", 'r', false );
    $uploadGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_category", 'u', false );

    $rgp = array();
    $wgp = array();
    $ugp = array();

    foreach( $readGroups as $group )
        $rgp[] = new eZXMLRPCInt( $group );
    foreach( $writeGroups as $group )
        $wgp[] = new eZXMLRPCInt( $group );
    foreach( $uploadGroups as $group )
        $ugp[] = new eZXMLRPCInt( $group );

    $category = new eZImageCategory( $ID );
    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezimagecatalogue", "category", $category->id() ),
                                             "Name" => new eZXMLRPCString( $category->name( false ) ),
                                             "ParentID" => new eZXMLRPCInt( $category->parent( false ) ),
                                             "Description" => new eZXMLRPCString( $category->description( false ) ),
                                             "ReadGroups" => new eZXMLRPCArray( $rgp ),
                                             "WriteGroups" => new eZXMLRPCArray( $wgp ),
                                             "UploadGroups" => new eZXMLRPCArray( $ugp )
                                             )
                                      );
}
else if( $Command == "storedata" ) // save the category data!
{
//      eZLog::writeNotice( "image category $ID" );

    if( $ID == 0 )
    {
        $category = new eZImageCategory();
    }
    else
    {
        $category = new eZImageCategory( $ID );
        $category->setUser( $User );
    }
    $category->setName( $Data["Name"]->value() );
    $category->setDescription( $Data["Description"]->value() );

    $parent = new eZImageCategory();
    $parentid = $Data["ParentID"]->value();
    if ( $parentid == 0 or $parent->get( $parentid ) )
    {
        $category->setParent( $parent );
        $category->store();
        $ID = $category->id();

        eZObjectPermission::removePermissions( $ID, "imagecatalogue_category", 'r' );
        $readGroups = $Data["ReadGroups"]->value();
        foreach( $readGroups as $readGroup )
            eZObjectPermission::setPermission( $readGroup->value(), $ID, "imagecatalogue_category", 'r' );

        eZObjectPermission::removePermissions( $ID, "imagecatalogue_category", 'w' );
        foreach( $Data["WriteGroups"]->value() as $writeGroup )
            eZObjectPermission::setPermission( $writeGroup->value(), $ID, "imagecatalogue_category", 'w' );

        eZObjectPermission::removePermissions( $ID, "imagecatalogue_category", 'u' );
        foreach( $Data["UploadGroups"]->value() as $uploadGroup )
            eZObjectPermission::setPermission( $uploadGroup->value(), $ID, "imagecatalogue_category", 'u' );

        // create the path array
        $path =& $category->path();
        if ( $category->id() != 0 )
        {
            $par[] = createURLStruct( "ezimagecatalogue", "category", 0 );
        }
        else
        {
            $par[] = createURLStruct( "ezimagecatalogue", "" );
        }
        foreach( $path as $item )
        {
            if ( $item[0] != $category->id() )
                $par[] = createURLStruct( "ezimagecatalogue", "category", $item[0] );
        }

        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezimagecatalogue", "category", $ID ),
                                                 "Path" => new eZXMLRPCArray( $par ),
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                                 )
                                          );
        $Command = "update";
    }
    else
    {
        $Error = createErrorMessage( EZERROR_CUSTOM, "Parent ($parentid) of $Module:/$RequestType/$ID is not an existing parent",
                                     EZIMAGECATALOGUE_NONEXISTING_PARENT );
    }
}
else if( $Command == "delete" )
{
    // create the path array
    $category = new eZImageCategory( $ID );
    $path =& $category->path();
    if ( $category->id() != 0 )
    {
        $par[] = createURLStruct( "ezimage", "category", 0 );
    }
    else
    {
        $par[] = createURLStruct( "ezimage", "" );
    }
    foreach( $path as $item )
    {
        if ( $item[0] != $category->id() )
            $par[] = createURLStruct( "ezimage", "category", $item[0] );
    }

    
    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezimage", "category", $ID ),
                                             "Path" => new eZXMLRPCArray( $par ),
                                             "UpdateType" => new eZXMLRPCString( $Command )
                                             )
                                      );
    $Command = "update";
    eZImageCategory::delete( $ID ); // finally, delete the imagecategory..
}

?>
