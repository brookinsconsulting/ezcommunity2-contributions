<?
// 
// $Id: fileupload.php,v 1.3 2000/12/27 16:35:10 bf Exp $
//
// B�rd Farstad <bf@ez.no>
// Created on: <10-Dec-2000 15:49:57 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

//  $folder = new eZVirtualFolder();
//  $folder->setName( "Documentation" );
//  $folder->setDescription( "Documentation goes here" );
//  $folder->store();


if ( $Action == "Insert" )
{
    print( "uploading file" );

    $file = new eZFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $uploadedFile = new eZVirtualFile();
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );

        $uploadedFile->setFile( $file );
        
        $uploadedFile->store();

        $folder = new eZVirtualFolder( $FolderID );

        $folder->addFile( $uploadedFile );
        

        eZLog::writeNotice( "File added to file manager from IP: $REMOTE_ADDR" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

}

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZFileManagerMain", "Language" );


$t = new eZTemplate( "ezfilemanager/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezfilemanager/user/intl/", $Language, "fileupload.php" );

$t->set_file( "file_upload_tpl", "fileupload.tpl" );

$t->setAllStrings();

$t->set_block( "file_upload_tpl", "value_tpl", "value" );

$t->set_var( "action_value", "Insert" );
$t->set_var( "name_value", "" );
$t->set_var( "description_value", "" );

$folder = new eZVirtualFolder( $FolderID );

$folderList =& $folder->getByParent( $folder );

foreach ( $folderList as $folder )
{
    $t->set_var( "option_name", $folder->name() );
    $t->set_var( "option_value", $folder->id() );

    $t->set_var( "selected", "" );

    $t->parse( "value", "value_tpl", true );
}

$t->pparse( "output", "file_upload_tpl" );


?>

