<?
// 
// $Id: maillist.php,v 1.17 2001/05/02 11:32:13 fh Exp $
//
// Frederik Holljen <fh@ez.no>
// Created on: <19-Mar-2000 20:25:22 fh>
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
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezmail/classes/ezmailfolder.php" );

include_once( "classes/ezlist.php" );

$Limit = 50;

if( isset( $NewFolder ) )
{
    eZHTTPTool::header( "Location: /mail/folderedit/" );
    exit();
}

if( isset( $Move ) && $FolderSelectID != -1 && count( $MailArrayID ) > 0 ) // really move to other folder
{
    $folder = new eZMailFolder( $FolderSelectID );
    foreach( $MailArrayID as $mailitemID )
        $folder->addMail( $mailitemID );
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "maillist.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_list_page_tpl" => "maillist.tpl"
    ) );

$t->set_var( "site_style", $SiteStyle );
$t->set_block( "mail_list_page_tpl", "mail_item_tpl", "mail_item" );
$t->set_block( "mail_item_tpl", "mail_edit_item_tpl", "mail_edit_item" );
$t->set_block( "mail_list_page_tpl", "mail_item_unread_tpl", "mail_item_unread" );
$t->set_block( "mail_list_page_tpl", "mail_render_tpl", "mail_render" );
$t->set_block( "mail_list_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_var( "mail_edit_item", "" );
$t->set_var( "mail_item", "" );
$t->set_var( "mail_item_unread", "" );
$t->set_var( "mail_render", "" );

$folder = new eZMailFolder( $FolderID );
$isDraftsFolder = false;
if( $folder->folderType() == DRAFTS )
    $isDraftsFolder = true;

$t->set_var( "current_folder_id", $FolderID );
$t->set_var( "current_folder_name", htmlspecialchars( $folder->name() ) );

$mail = $folder->mail( "date_desc", $Offset, $Limit );
$mailCount = $folder->mailCount();
$i = 0;
foreach( $mail as $mailItem )
{
    $t->set_var( "mail_id", $mailItem->id() );
    $t->set_var( "mail_subject", htmlspecialchars( $mailItem->subject() ), "-" );
    $t->set_var( "mail_sender", htmlspecialchars( $mailItem->sender() ) );

    switch( $mailItem->status() )
    {
        case UNREAD : $t->set_var( "mail_status", 'U' ); break;
        case READ : $t->set_var( "mail_status", '-' ); break;
        case REPLIED : $t->set_var( "mail_status", 'R' ); break;
        case FORWARDED : $t->set_var( "mail_status", 'F' ); break;
        case MAIL_SENT : $t->set_var( "mail_status", 'S' ); break;
    }
    
    $siSize = $mailItem->siSize();
    $t->set_var( "mail_size" , $siSize["size-string"] . $siSize["unit"] );
    $t->set_var( "mail_date", date("D M d H:i Y ", $mailItem->uDate() ) );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
    if( $mailItem->status() == UNREAD )
        $t->parse( "mail_render", "mail_item_unread_tpl", true );
    else
    {
        $isDraftsFolder ? $t->parse( "mail_edit_item", "mail_edit_item_tpl", false ) : $t->set_var( "mail_edit_item", "&nbsp;" );
        $t->parse( "mail_render", "mail_item_tpl", true );
    }

    $i++;
}

/* insert the standard folders first */
foreach( array( INBOX, SENT, DRAFTS, TRASH ) as $specialfolder )
{
    $folderItem = eZMailFolder::getSpecialFolder( $specialfolder );
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", $folderItem->name() );
    $t->parse( "folder_item", "folder_item_tpl", true );
}

$folders = eZMailFolder::getByUser();
foreach( $folders as $folderItem )
{
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", $folderItem->name() );
    $t->parse( "folder_item", "folder_item_tpl", true );
}

eZList::drawNavigator( $t, $mailCount, $Limit, $Offset, "mail_list_page_tpl" );

$t->pparse( "output", "mail_list_page_tpl" );
?>
