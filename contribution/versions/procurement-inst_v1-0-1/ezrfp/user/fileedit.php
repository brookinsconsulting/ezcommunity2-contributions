<?php
// 
// $Id: fileedit.php,v 1.2 2001/09/27 08:00:49 jhe Exp $
//
// Created on: <21-Dec-2000 18:01:48 bf>
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

include_once( "classes/ezfile.php" );

include_once( "ezfilemanager/classes/ezvirtualfile.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZRfpMain", "Language" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );

if ( isSet( $DeleteSelected ) )
    $Action = "Delete";

if ( $Action == "Insert" )
{
    $file = new eZFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $rfp = new eZRfp( $RfpID );

        $uploadedFile = new eZVirtualFile();
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );

        $uploadedFile->setFile( $file );
        
        $uploadedFile->store();

        $rfp->addFile( $uploadedFile );

        eZLog::writeNotice( "File added to rfp $RfpID from IP: $REMOTE_ADDR" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /rfp/rfpedit/filelist/" . $RfpID . "/" );
    exit();
}

if ( $Action == "Update" )
{
    $file = new eZFile();

    if ( $file->getUploadedFile( "userfile" ) )
    {
        $rfp = new eZRfp( $RfpID );

        $oldFile = new eZFile( $FileID );
        $rfp->deleteFile( $oldFile );

        $uploadedFile = new eZVirtualFile();
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );

        $uploadedFile->setFile( $file );

        $uploadedFile->store();

        $rfp->addFile( $uploadedFile );
    }
    else
    {
        $uploadedFile = new eZVirtualFile( $FileID );
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );
        $uploadedFile->store();
    }

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /rfp/rfpedit/filelist/" . $RfpID . "/" );
    exit();
}


if ( $Action == "Delete" )
{
    $rfp = new eZRfp( $RfpID );

    if ( count( $FileArrayID ) != 0 )
    {
        foreach ( $FileArrayID as $FileID )
        {
            $file = new eZVirtualFile( $FileID );
            $rfp->deleteFile( $file );
        }
    }

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /rfp/rfpedit/filelist/" . $RfpID . "/" );
    exit();    
}

$t = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                     "ezrfp/user/intl/", $Language, "fileedit.php" );

$t->setAllStrings();

$t->set_file( "file_edit_page", "fileedit.tpl" );


//default values
$t->set_var( "name_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );
$t->set_var( "file", "" );

if ( $Action == "Edit" )
{
    $rfp = new eZRfp( $RfpID );
    $file = new eZVirtualFile( $FileID );

    $t->set_var( "rfp_name", $rfp->name() );

    $t->set_var( "file_id", $file->id() );
    $t->set_var( "name_value", $file->name() );
    $t->set_var( "description_value", $file->description() );
    $t->set_var( "action_value", "Update" );
}

$rfp = new eZRfp( $RfpID );
    
$t->set_var( "rfp_name", $rfp->name() );
$t->set_var( "rfp_id", $rfp->id() );

$t->pparse( "output", "file_edit_page" );

?>
