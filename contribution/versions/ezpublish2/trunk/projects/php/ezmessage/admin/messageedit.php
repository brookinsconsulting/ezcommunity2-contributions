<?
// 
// $Id: messageedit.php,v 1.1 2001/06/06 09:52:43 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <05-Jun-2001 17:19:01 bf>
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

include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );

include_once( "ezuser/classes/ezuser.php" );

include_once( "ezmessage/classes/ezmessage.php" );

if ( isset( $SendMessage ) )
{
    $users = explode( ",", $Receiver );

    // check for valid users:
    $usersValid = true;
    foreach ( $users as $user )
    {
        $user = trim( $user );
        
        if ( !eZUser::exists( $user ) )            
            $usersValid = false;
    }
    
    if ( $usersValid == true )
    {
        foreach ( $users as $user )
        {
            $user = trim( $user );
            
            $message = new eZMessage( );
            $message->setSubject( $Subject );
            $message->setDescription( $Description );

            $toUser = eZUser::getUser( $user );
            $message->setToUser( $toUser );

            $fromUser = eZUser::currentUser();
            $message->setFromUser( $fromUser );

            $message->store();
            
        }
    }    
}

$t = new eZTemplate( "ezmessage/admin/" . $ini->read_var( "eZMessageMain", "AdminTemplateDir" ),
                     "ezmessage/admin/intl", $Language, "messageedit.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "message_page_tpl", "messageedit.tpl" );

$t->setAllStrings();


$t->pparse( "output", "message_page_tpl" );

?>

