<?
// $Id: unapprovedlist.php,v 1.3 2001/01/22 14:56:46 ce Exp $
//
// Author: Bård Farstad <bf@ez.no>
// Created on: <21-Jan-2001 13:34:48 bf>
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

$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "AdminTemplateDir" ),
                     "ezforum/admin/" . "/intl", $Language, "unapprovedlist.php" );
$t->setAllStrings();

$t->set_file( Array( "message_page" => "unapprovedlist.tpl" ) );

$t->set_block( "message_page", "message_item_tpl", "message_item" );


$locale = new eZLocale( $Language );

$message = new eZForumMessage();

$messages = $message->getAllNotApproved( );

$languageIni = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/unapprovedlist.php.ini", false );
$true =  $languageIni->read_var( "strings", "true" );
$false =  $languageIni->read_var( "strings", "false" );

if ( !$messages )
{
    $noitem = $languageIni->read_var( "strings", "noitem" );
    $t->set_var( "message_item", $noitem );
}
else
{
    $i = 0;
    foreach ( $messages as $message )
    {
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
    
    
        $t->set_var( "message_topic", $message->topic() );
        $t->set_var( "message_body", $message->body() );

        $t->set_var( "reject_reason", $languageIni->read_var( "strings", "reject_reason" ) );
        
        $t->set_var( "message_postingtime", $locale->format( $message->postingTime() ) );

        $t->set_var( "message_id", $message->id() );

        $user = $message->user();
    
        $t->set_var( "message_user", $user->firstName() . " " . $user->lastName() );

        $t->set_var( "i", $i );
        
        $t->parse( "message_item", "message_item_tpl", true );
        $i++;
    }
} 

$t->pparse( "output", "message_page" );
?>
