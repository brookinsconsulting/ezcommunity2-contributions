<?php
//
// $Id: confirmation.php,v 1.20.2.7 2003/05/19 07:30:55 br Exp $
//
// Created on: <20-Sep-2000 13:32:11 ce>
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

//require( "ezuser/user/usercheck.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );


include_once( "ezuser/classes/ezconfirmation.php" );
include_once( "ezmail/classes/ezmail.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$headersInfo = getallheaders();

$languageIni = new INIFIle( "ezuser/user/intl/" . $Language . "/confirmation.php.ini", false );

// Get the user.
if ( $Login )
{
    $getUser = new eZUser();
    $user = $getUser->getUser( $Login );
}

//  if ( isSet( $ChangeButton ) && ( $user == false ) )
if ( ( $user == false ) )
{
    eZHTTPTool::header( "Location: /user/confirmation/unsuccessfull" );
    exit();
}

// Store the user with a unique hash and mail the hash variable to the user.

if ( $Action == "create" )
{

if ( $user )
{
    if ( eZMail::validate( $user->email() ) == false )
    {
        eZHTTPTool::header( "Location: /user/confirmation/missingemail" );
        exit();
    }

    $subjectText = ( $languageIni->read_var( "strings", "subject_text" ) . " " . $headersInfo["Host"] );
    $bodyText = $languageIni->read_var( "strings", "body_text" );

    $bodyFooter = $languageIni->read_var( "strings", "body_footer" );                      //SF
    $confirmationMailFromAddress = $ini->read_var( "eZUserMain", "ConfirmationMailFromAddress" );  //SF

    $confirmation = new eZConfirmation();
    $confirmation->setUserID( $user->id() );
    $userID = $user->id();
    $confirmation->store();

    $mailpassword = new eZMail();
    $mailpassword->setTo( $user->email() );
    $mailpassword->setSubject( $subjectText );
    $mailpassword->setFrom( $confirmationMailFromAddress  );                                               //SF

    $body = ( $bodyText . "\n");
	// line w/ hash!!!!!!!!
    $body .= ( "http://" . $headersInfo["Host"] . $ini->WWWDir . $ini->Index . "/user/confirmation/confirm/" . htmlspecialchars( $confirmation->Hash() ) . "\n" );
    $body .= ( $bodyFooter );                                                                      //SF

    $mailpassword->setBody( $body );
    $mailpassword->send();

    eZHTTPTool::header( "Location: /user/confirmation/successfull" );
    exit();
}

}

// before this point is the email send ing code

// below this point is the "front end for the clickable hash link"
// that links an email user to a change request, change confirmation / password / username lookup 
// to just email user a confirmation / user activate "switch"
// why write notes? because you are the sum of your nightmares

// if ( $Action == "change" )
if ( $Action == "confirm" )
{

// print($Action);

    $change = new eZConfirmation();
    $change->get( $change->check( $Hash ) );

// print($change->check($Hash));

// print( $change->check( $Hash ) );

    // more hash stashed away
//      if ( $Action == "confirm" )

    if ( $change->check( $Hash ) )
    {
        $subjectNewPassword = $languageIni->read_var( "strings", "subject_text_password" );

        $confirmationMailFromAddress = $ini->read_var( "eZUserMain", "ConfirmationMailFromAddress" );  //SF
//        $bodyFooter = $languageIni->read_var( "strings", "body_footer" );                      //SF

//        $bodyNewPassword = $languageIni->read_var( "strings", "body_text_password" );
  //      $passwordText = $languageIni->read_var( "strings", "password" );
 
       $userID = $change->userID();
       $user = new eZUser( $userID );
	 $user->setAccountActive(true);

        $password = substr( md5( microtime() ), 0, 7 );
//        $user->setPassword( $password );
        $user->store();
		
		//just stored new password!
		
        $mail = new eZMail();
        $mail->setTo( $user->email() );
        $mail->setSubject( $subjectNewPassword . " " . $headersInfo["Host"] );

        $mail->setFrom( $confirmationMailFromAddress );                                            //SF

        $body = ( $bodyNewPassword . "\nhttp://" . $headersInfo["Host"] . $ini->WWWDir . $ini->Index . "/user/login/.\n" ); //SF


        $body .= ( $passwordText . ": "  .  $password );

        $body .= ( $bodyFooter . "\n");                                                              //SF

//        $mail->setBody( $body );
        $mail->setBody( 'n/a' );

//	$mail->setBody( 'generated mail' . "\n");
//        $mail->send();
//	exit();

        // Cleanup
        $change->get( $change->check( $Hash ) );
        $change->delete();
//	exit();
    }
    eZHTTPTool::header( "Location: /user/confirmation/generated/" );
    exit();
}

// Template
$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ), "ezuser/user/intl", $Language, "confirmation.php" );
$t->setAllStrings();

$t->set_file( array( "login" => "confirmation.tpl" ) );

$t->pparse( "output", "login" );

?>
