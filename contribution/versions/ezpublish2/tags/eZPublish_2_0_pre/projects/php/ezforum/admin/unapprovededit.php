<?
// $Id: unapprovededit.php,v 1.4 2001/03/01 14:06:25 jb Exp $
//
// Author: B�rd Farstad <bf@ez.no>
// Created on: <21-Jan-2001 13:34:48 bf>
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
include_once( "classes/ezhttptool.php" );
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "classes/ezmail.php" );

$locale = new eZLocale( $Language );

require( "ezuser/admin/admincheck.php" );

$message = new eZForumMessage();

for( $i=0; $i < count ( $ActionValueArray ); $i++ )
{
    
    $message = new eZForumMessage( $MessageID[$i] );

    if ( $ActionValueArray[$i] == "Approve" )
    {
        $message->setIsApproved( 1 );

        $message->store();
    }
    if ( $ActionValueArray[$i] == "Discard" )
    {
        $message->delete();
    }
    if ( $ActionValueArray[$i] == "Reject" )
    {
        $user =& $message->user();

        $mail = new eZMail();

        $mailTemplate = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "AdminTemplateDir" ),
                                        "ezforum/admin/intl", $Language, "mailreject.php" );

        $languageIni = new INIFIle( "ezforum/admin/intl/" . $Language . "/mailreject.php.ini", false );

        $mailTemplate->set_file( "mail_reject_tpl", "mailreject.tpl" );
        $mailTemplate->setAllStrings();

        $mailTemplate->set_var( "reason_for_reject", $RejectReason[$i] );

        $mailTemplate->set_var( "message_body", nl2br( $message->body() ) );
        $mailTemplate->set_var( "message_topic", $message->topic() );
        $t->set_var( "message_postingtime", $locale->format( $message->postingTime() ) );

        $body =& $mailTemplate->parse( "dummy", "mail_reject_tpl" );

        $mail->setSubject( $languageIni->read_var( "strings", "mail_subject" ) . " " . $message->topic() );

        $messageUser =& $message->user();

        if ( get_class ( $messageUser ) == "ezuser" )
        {
            $mail->setTo( $messageUser->email() );

            $forum = new eZForum( $message->forumID() );

            $forumUser =& $forum->moderator();

            if ( get_class ( $forumUser ) == "ezuser" )
            {
        
                $mail->setFrom( $forumUser->email() );
                $mail->setBody( $body );

                $mail->send();

                print( $messageUser->email() );
            }
        }

        $message->delete();
    }

    eZHTTPTool::header( "Location: /forum/unapprovedlist/" );
    exit();
}


?>
