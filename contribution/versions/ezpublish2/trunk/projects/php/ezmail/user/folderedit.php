<?php
// 
// $Id: folderedit.php,v 1.8 2002/04/09 14:19:03 fh Exp $
//
// Created on: <16-Feb-2001 14:33:48 fh>
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

include_once( "ezmail/classes/ezmailfolder.php" );
include_once( "ezmail/classes/ezimapmailfolder.php" );
include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );

// check that the folder beeing viewed is your folder
if ( $FolderID != 0 && !eZMailFolder::isOwner( eZUser::currentUser(), $FolderID ) )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

if( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /mail/folderlist" );
    exit();
}

if( isset( $Ok ) && $Name != "" )
{
    // we have the following cases
    // FolderID == 0, and there is given a local folder as parent
    // FolderID != 0, it is of remote type

    // FolderID == 0, and there is given a remote folder as parent
    // FolderID != 0, it is of local type

    if( is_numeric( $FolderID ) && is_numeric( $ParentID ) ) // local folder..
    {
        if( $FolderID == 0 )
        {
            $folder = new eZMailFolder();
        }
        else
        {
            $folder = new eZMailFolder( $FolderID );
            if( $folder->isChild( $ParentID, true ) )
            {
                eZHTTPTool::header( "Location: /mail/folderlist" );
                exit();
            }
        }
        $folder->setName( $Name );
        $folder->setParent( $ParentID );
        $folder->setUser( eZUser::currentUser() );
        $folder->store();
        $FolderID = $folder->id();
        eZHTTPTool::header( "Location: /mail/folder/local/$FolderID" );
        exit();
    }
    else if( strstr( $ParentID, "-" ) )
    {
        if( $FolderID == 0 ) // it's a new imap folder
        {
            $folderData = eZIMAPMailFolder::decodeFolderID( $ParentID );
            $folderName = trim( $folderData["FolderName"] ) != "" ? $folderData["FolderName"] . "/": ""; 
            $folderName .= $Name; // add the new name
//        echo "Creating new mailbox: " . $folderName . $Name;
            eZIMAPMailFolder::createMailBox( $folderData["AccountID"], $folderName );
            eZHTTPTool::header( "Location: /mail/folderlist" );
            exit();
        }
        else // it's a remote beeing renamed..
        {
        }
    }
   
}


$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "folderedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "folder_edit_page_tpl" => "folderedit.tpl"
    ) );

$t->set_block( "folder_edit_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_block( "folder_edit_page_tpl", "top_imap_item_tpl", "top_imap_item" );
$t->set_var( "folder_item", "" );
$t->set_var( "folder_name", "" );
$t->set_var( "top_imap_item", "" );
$t->set_var( "current_folder_id", $FolderID );

if( $FolderID != 0 )
{
    $folder = new eZMailFolder( $FolderID );
    $parentID = $folder->parentID();
    $t->set_var( "folder_name", $folder->name() );
}

//toplevel IMAP folders
$imapAccounts = eZMailAccount::getByUser( eZUser::currentUser(), IMAP );
if( count( $imapAccounts ) != 0 )
{
    foreach( $imapAccounts as $imapAccount )
    {
        $t->set_var( "account_id", $imapAccount->id() );
        $t->set_var( "imap_topfolder", $imapAccount->name() );
        $t->parse( "top_imap_item", "top_imap_item_tpl", true );
    }
}

// normal folders
$folders = eZMailFolder::getByUser();
$folders = array_merge( eZImapMailFolder::getAllImapFolders(), $folders ); // get imap folders
foreach( $folders as $folderItem )
{
    if( $folderItem->id() != $FolderID )
    {
        $t->set_var( "folder_parent_id", $folderItem->id() );
        $t->set_var( "folder_parent_name", $folderItem->name() );

        if( isset( $parentID ) && $folderItem->id() == $parentID )
            $t->set_var( "is_selected", "selected" );
        else
            $t->set_var( "is_selected", "" );

        $t->parse( "folder_item", "folder_item_tpl", true );
    }
}


$t->pparse( "output", "folder_edit_page_tpl" );
?>
