<?
// 
// $Id: menubox.php,v 1.5 2001/03/27 09:32:59 fh Exp $
//
// Frederik Holljen <fh@ez.no>
// Created on: <23-Mar-2001 10:57:04 fh>
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

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZMailMain", "Language" );

    
include_once( "classes/eztemplate.php" );
include_once( "classes/ezdb.php" );
include_once( "ezmail/classes/ezmailfolder.php" );

if( eZUser::currentUser() )
{
    $t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                         "ezmail/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->set_block( "menu_box_tpl", "mail_folder_tpl", "mail_folder" );

    foreach( array( INBOX, SENT, DRAFTS, TRASH ) as $specialfolder )
    {
        $folderItem = eZMailFolder::getSpecialFolder( $specialfolder );
        $t->set_var( "folder_id", $folderItem->id() );
        $t->set_var( "folder_name", $folderItem->name() );
        $t->parse( "mail_folder", "mail_folder_tpl", true );
    }
    
    $folders = eZMailFolder::getByUser();
    foreach( $folders as $folderItem )
    {
        $t->set_var( "folder_id", $folderItem->id() );
        $t->set_var( "folder_name", $folderItem->name() );
        $t->parse( "mail_folder", "mail_folder_tpl", true );
    }
    
    $t->pparse( "output", "menu_box_tpl" );
}

?>
