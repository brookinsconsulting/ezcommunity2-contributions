<?php
// 
// $Id: cron.php,v 1.6 2001/11/14 08:19:26 jhe Exp $
//
// Created on: <26-Oct-2001 15:57:39 jhe>
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
include_once( "classes/ezdatetime.php" );
include_once( "ezbug/classes/ezbug.php" );
include_once( "ezbug/classes/ezbugmodule.php" );
include_once( "ezbug/classes/ezbuglog.php" );
include_once( "ezbug/classes/ezbugsupport.php" );
include_once( "ezbug/classes/ezbugsupportcategory.php" );
include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmail.php" );

$ini = new INIFile( "site.ini", false );

$Language = $ini->read_var( "eZBugMain", "Language" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "confirmationmail.php" );

$t->setAllStrings();

$t->set_file( "confirmation_mail_tpl", "confirmationmail.tpl" );

$t->set_block( "confirmation_mail_tpl", "subject_tpl", "subject" );
$t->set_block( "confirmation_mail_tpl", "refuse_subject_tpl", "refuse_subject" );
$t->set_block( "confirmation_mail_tpl", "mail_body_tpl", "mail_body" );
$t->set_block( "confirmation_mail_tpl", "refuse_mail_body_tpl", "refuse_mail_body" );
$t->set_block( "confirmation_mail_tpl", "reply_body_tpl", "reply_body" );

$bugCategories = eZBugSupportCategory::getAll();

foreach ( $bugCategories as $bugCategory )
{
    $mailUser = $bugCategory->email();
    $mailAccount = substr( $mailUser, 0, strpos( $mailUser, "@" ) );
    $mailPassword = $bugCategory->password();
    $mailServer = $bugCategory->mailServer();
    $mailServerPort = $bugCategory->mailServerPort();

    $bugModule = new eZBugModule( $bugCategory->bugModuleID() );
    $mailArray = eZMailAccount::getNewMail( $mailAccount, $mailPassword, $mailServer, $mailServerPort );
    
    foreach ( $mailArray as $mail )
    {
        $validUser = false;
        $subject = $mail->subject();
        if ( $bugCategory->supportNo() )
        {
            if ( ereg( " #([0-9]+)", $subject, $list ) )
            {
                $support = new eZBugSupport( $list[1] );
                if ( $support->expiryDate() >= eZDateTime::timeStamp( true ) &&
                     strstr( $mail->from(), $support->userEmail() ) )
                {
                    $validUser = true;
                }
            }
        }
        else
        {
            $validUser = true;
        }
        
        $confmail = new eZMail();
        $confmail->setTo( $mail->replyTo() );
        $confmail->setFrom( $mailUser );
        $confmail->setReplyTo( $mailUser );
        
        if ( $validUser )
        {
            $makebug = true;
            if ( ereg( "B#([0-9]*)", $subject, $list ) )
            {
                if ( eZBug::bugExists( $list[1] ) )
                {
                    $bug = new eZBug( $list[1] );
                    $log = new eZBugLog();
                    $body = "";
                    foreach ( split( "\n", $mail->body() ) as $line )
                    {
                        if ( !ereg( "^[ ]*>", $line ) )
                        {
                            $body .= $line . "\n";
                        }
                    }
                    
                    $log->setDescription( $body );
                    $log->setBug( $bug );
                    $log->store();
                    $makebug = false;
                    $t->set_var( "prefix", $ini->read_var( "eZMailMain", "ReplyPrefix" ) );
                    $t->set_var( "bug_id", $bug->id() );
                    $mailSubject = $mail->subject();
                    $mailBody = $t->parse( "dummy", "reply_body_tpl" ) . $mail->body();
                }
            }
            
            if ( $makebug )
            {
                $bug = new eZBug();
                $bug->setUserEmail( $mail->replyTo() );
                $bug->setName( $mail->subject() );
                $bug->setDescription( $mail->body() );
                $bug->setIsHandled( false );
                $bug->setUserEmail( eZMail::stripEmail( $mail->from() ) );
                $bug->store();
                $bugModule->addBug( $bug );
                
                $t->set_var( "prefix", "" );
                $t->set_var( "subject", $mail->subject() );
                $t->set_var( "bug_id", $bug->id() );
                $mailSubject = $t->parse( "dummy", "subject_tpl" );
                $mailBody = $t->parse( "dummy", "mail_body_tpl" ) . $mail->body();
            }
        }
        else
        {
            $t->set_var( "prefix", $ini->read_var( "eZMailMain", "ReplyPrefix" ) );
            $t->set_var( "subject", $mail->subject() );
            $mailSubject = $t->parse( "dummy", "refuse_subject_tpl" );
            $mailBody = $t->parse( "dummy", "refuse_mail_body_tpl" ) . $mail->body();
        }
        
        $confmail->setSubject( $mailSubject );
        $confmail->setBody( $mailBody );
        $confmail->send();
        
        $mail->delete();
    }
}

?>
