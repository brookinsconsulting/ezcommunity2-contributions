<?php
//
// $Id: folderlist.php,v 1.8 2002/04/17 10:45:53 fh Exp $
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezmail/classes/ezmailfolder.php" );
include_once( "ezmail/classes/ezimapmailfolder.php" );

/** If user wants to move folders **/
if( isset( $Move ) && count( $FolderArrayID ) > 0 && $FolderSelectID != -1)
{
    foreach( $FolderArrayID as $folderID )
    {
        $folder = new eZMailFolder( $folderID );
        if( $folder->folderType() == USER && $folder->id() != $FolderSelectID )
        {
            $folder->setParent( $FolderSelectID );
            $folder->store();
        }
    }
    eZHTTPTool::header( "Location: /mail/folderlist/" );
    exit();
}

/** If user wants to delete folders **/
if( isset( $Delete ) && count( $FolderArrayID ) > 0 )
{
    foreach( $FolderArrayID as $folderID )
    {
        if( strstr( $folderID, "-" ) ) // remote folder
        {
            // possible bug: Delete parent before children, children delete won't work...
            eZIMAPMailFolder::delete( $folderID );
        }
        else // local folder
        {
            eZMailFolder::delete( $folderID );
        }
    }
}

/** If user wants to create a folder **/
if( isset( $NewFolder ) )
{
    eZHTTPTool::header( "Location: /mail/folderedit/" );
    exit();
}

/** User pressed EmptyTrash **/
if( isset( $EmptyTrash ) )
{
    $trash = eZMailFolder::getSpecialFolder( TRASH );
    $trash->deleteAll();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "folderlist.php" );
$t->setAllStrings();

$t->set_file( array(
    "folder_list_page_tpl" => "folderlist.tpl"
    ) );

$t->set_block( "folder_list_page_tpl", "mailbox_item_tpl", "mailbox_item" );
$t->set_block( "folder_list_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_block( "mailbox_item_tpl", "folders_item_tpl", "folders_item" );
$t->set_block( "folders_item_tpl", "folders_item_edit_tpl", "folders_item_edit" );
$t->set_block( "folders_item_tpl", "edit_empty_tpl", "edit_empty" );
$t->set_var( "folders_item_edit", "" );

$t->set_var( "site_style", $SiteStyle );

/** insert the special folders **/
$i=0;
foreach( array( INBOX, SENT, DRAFTS, TRASH ) as $specialfolder )
{
    $folderItem = eZMailFolder::getSpecialFolder( $specialfolder );
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", htmlspecialchars( $folderItem->name() ) );
    $t->set_var( "folder_unread_mail_total", $folderItem->count( true ) );
    $t->set_var( "folder_mail_total", $folderItem->count() );
    $t->set_var( "indent", "");

    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "edit_empty", "edit_empty_tpl", false );
    $t->parse( "folders_item", "folders_item_tpl", true );
    $i++;
}
/** insert the local user folders **/
$userFolders = eZMailFolder::getTree( 0, -1 );
foreach( $userFolders as $folderItem )
{
    $t->set_var( "account_type", "local" );
    $t->set_var( "folder_id", $folderItem[0] );
    $t->set_var( "folder_name", htmlspecialchars( $folderItem[1] ) );
    $t->set_var( "folder_unread_mail_total", eZMailFolder::count( true, $folderItem[0] ) );
    $t->set_var( "folder_mail_total", eZMailFolder::count(false, $folderItem[0] ) );
    $t->set_var( "indent", str_repeat( "&nbsp;", 3 * $folderItem[2] ) );
    $t->set_var( "edit_empty", "" );

    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "folders_item_edit", "folders_item_edit_tpl", false );
    $t->parse( "folders_item", "folders_item_tpl", true );
    $i++;
}
$t->parse( "mailbox_item", "mailbox_item_tpl", true );

/** insert the remote user folders **/
$imapAccounts = eZMailAccount::getByUser( eZUser::currentUser(), IMAP );
if( count( $imapAccounts ) > 0 )
{
    foreach( $imapAccounts as $imapAccount ) // loop through all IMAP accounts
    {
        $t->set_var( "folders_item", "" ); // cleanup after last round.
        $folders = eZIMAPMailFolder::getImapTree( $imapAccount );
        if( count( $folders ) > 0 )
        {
            foreach( $folders as $folder ) // loop trough all folders in each IMAP account
            {
                $t->set_var( "account_type", "remote" );
                $t->set_var( "folder_id", $folder->encodeFolderID(-1, -1, false) );
                $t->set_var( "folder_name", $folder->name() );
                $t->set_var( "folder_unread_mail_total", "UA" );
                $t->set_var( "folder_mail_total", "UA" );
                $t->set_var( "indent", "" ); // TODO: DIFFERENT THAN FOR LOCAL FULL PATH IS GIVEN
                $t->set_var( "edit_empty", "" );
                ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
                $t->parse( "folders_item_edit", "folders_item_edit_tpl", false );
                $t->parse( "folders_item", "folders_item_tpl", true );
                $i++;
            }
            $t->parse( "mailbox_item", "mailbox_item_tpl", true );
        }
    }
}

/** insert folders into the move dialog **/
$folders = eZMailFolder::getByUser();
foreach( $folders as $folderItem )
{
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", $folderItem->name() );
    $t->parse( "folder_item", "folder_item_tpl", true );
}


$t->pparse( "output", "folder_list_page_tpl" );
?>
