<?
// 
// $Id: maillist.php,v 1.7 2001/03/25 12:35:31 fh Exp $
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

$user = eZUser::currentUser();
$accounts = eZMailAccount::getByUser( $user->id() );

//foreach( $accounts as $account )
//    $account->checkMail();

/*
$user = eZUser::currentUser();
$account = new eZMailAccount(1);
$account->setUserID( $user->id() );
$account->setName( addslashes( "Larson's mail" ) );
$account->setLoginName( "larson" );
$account->setPassword( "AcRXYJJA" );
$account->setDeleteFromServer( 1 );
$account->setIsActive( 1 );
$account->setServerType( "pop" );
$account->setServer( "zap.ez.no" );
$account->store();
*/

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

$t->set_block( "mail_list_page_tpl", "mail_item_tpl", "mail_item" );
$t->set_block( "mail_list_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_var( "mail_item", "" );

$folder = new eZMailFolder( $FolderID );
$t->set_var( "current_folder_id", $FolderID );
$mail = $folder->mail();

$i = 0;
foreach( $mail as $mailItem )
{
    $t->set_var( "mail_id", $mailItem->id() );
    $t->set_var( "mail_subject", htmlspecialchars( $mailItem->subject() ) );
    $t->set_var( "mail_sender", htmlspecialchars( $mailItem->sender() ) );
    $t->set_var( "mail_size" ,"" );
    $t->set_var( "mail_date", "" );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
    $t->parse( "mail_item", "mail_item_tpl", true );

    $i++;
}

$folders = eZMailFolder::getByUser();
foreach( $folders as $folderItem )
{
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", $folderItem->name() );
    $t->parse( "folder_item", "folder_item_tpl", true );
}

$t->pparse( "output", "mail_list_page_tpl" );
?>
