<?php
// 
// $Id: image.php,v 1.2 2001/06/28 10:31:50 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <14-Jun-2001 13:18:27 amos>
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

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezimagecatalogue/classes/ezimagevariation.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

if( $Command == "data" ) // Dump image info!
{
    unset( $width );
    unset( $height );
    if ( is_object( $Data["Size"] ) )
    {
        $size = $Data["Size"]->value();
        if ( is_object( $size["Width"] ) and is_object( $size["Height"] ) )
        {
            $width = $size["Width"]->value();
            $height = $size["Height"]->value();
        }
    }
    
    if ( isset( $width ) and isset( $height ) )
    {
        $writeGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_image", 'w', false );
        $readGroups = eZObjectPermission::getGroups( $ID, "imagecatalogue_image", 'r', false );

        foreach( $readGroups as $group )
            $rgp[] = new eZXMLRPCInt( $group );
        foreach( $writeGroups as $group )
            $wgp[] = new eZXMLRPCInt( $group );

        $image = new eZImage( $ID );
        $variation = $image->requestImageVariation( $width, $height, false, true );
        if ( !is_bool( $variation ) )
        {
            $size = 0;
            if ( $image->fileExists( true ) )
            {
                $imagePath =& $image->filePath( true );
                $size = filesize( $imagePath );
                $user = $image->user();
                $user_id = get_class( $user ) == "ezuser" ? $user->id() : 0;

                $ret = array( 
                    "Name" => new eZXMLRPCString( $image->name() ),
                    "Caption" => new eZXMLRPCString( $image->caption() ),
                    "Description" => new eZXMLRPCString( $image->description() ),
                    "FileName" => new eZXMLRPCString( $image->fileName() ),
                    "OriginalFileName" => new eZXMLRPCString( $image->originalFileName() ),
                    "FileSize" => new eZXMLRPCInt( $size ),
                    "UserID" => new eZXMLRPCInt( $user_id ),
                    "ReadGroups" => new eZXMLRPCArray( $rgp ),
                    "WriteGroups" => new eZXMLRPCArray( $wgp ),
                    "WebURL" => new eZXMLRPCString( "/" . $variation->imagePath() ),
                    "Size" => createSizeStruct( $variation->width(), $variation->height() )
                    );
                $ReturnData = new eZXMLRPCStruct( $ret );
            }
            else
                $Error = createErrorMessage( EZERROR_CUSTOM, "Image $ID does not exist on disk" );
        }
        else
            $Error = createErrorMessage( EZERROR_CUSTOM, "Couldn't convert image $ID" );
    }
    else
    {
        $Error = createErrorMessage( EZERROR_CUSTOM, "Missing width and height in image request" );
    }
}

?>
