<?
// 
// $Id: ezmail.php,v 1.17 2001/03/21 12:34:17 bf Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
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


//!! eZCommon
//! The eZMail class is a wrapper class for the mail() function in php.
/*!
Example code:
\code
// Create a new eZMail object
$mail = new eZMail();

// set the sender and receiver
$mail->setFrom( "bf@ez.no" );
$mail->setFromName( "B. Farstad" );
$mail->setTo( "ce@ez.no" );

// set the subject and body
$mail->setSubject( "Ny ordre" );
$mail->setBody( "Ny ordre" );

// off you go
$mail->send();
\endcode
*/

/*!TODO
  Add support for file attachments
  See:
  http://www.phpwizard.net/resources/phpMisc/scripts/pretty/mail.php3
  and http://phpclasses.upperdesign.com/browse.html/package/32
  
  Check verification of email addresses.
  
*/

class eZMail
{
    /*!
      Constructs a new eZMail object.
    */
    function eZMail()
    {
        // normal priority. 1=highest, 5=lowest
        $this->Priority = 3;
    }

    /*!
      Returns the receiver address.
    */
    function to()
    {
        return $this->To;
    }

    /*!
      Sets the receiver address.
    */
    function setTo( $newTo )
    {
        $this->To = $newTo;
    }

    /*!
      Returns the receiver address.
    */
    function receiver()
    {
        return $this->To;
    }

    /*!
      Sets the receiver address.
    */
    function setReceiver( $newReceiver )
    {
        $this->To = $newReceiver;
    }
    
    /*!
      Returns the from address.
    */
    function from()
    {
        return $this->From;
    }

    /*!
      Returns the from name.
    */
    function fromName()
    {
        return $this->FromName;
    }

    /*!
      Sets the from name.      
    */
    function setFromName( $newFrom )
    {
        $this->FromName = $newFrom;
    }

    /*!
      Sets the from address.      
    */
    function setFrom( $newFrom )
    {
        $this->From = $newFrom;
    }

    /*!
      Returns the sender address.
    */
    function sender()
    {
        return $this->From;
    }

    /*!
      Sets the sender address.      
    */
    function setSender( $newSender )
    {
        $this->From = $newSender;
    }
    
    /*!
      Returns the subject.
    */
    function subject()
    {
        return $this->Subject;
    }

    /*!
      Sets the subject of the mail.
    */
    function setSubject( $newSubject )
    {
        $this->Subject = trim( $newSubject );
    }

    /*!
      returns the body.
    */
    function body()
    {
        return $this->Body;
    }

    /*!
      Sets the body.
    */
    function setBody( $newBody )
    {
        $this->Body = $newBody;
    }

    /*!
      Sends the mail.
    */
    function send()
    {
        //  $headers .= "cc:birthdayarchive@php.net\n"; // CC to
        //  $headers .= "bcc:birthdaycheck@php.net, birthdaygifts@php.net\n"; // BCCs to

        if ( empty( $this->FromName ) )
            $headers .= "From: $this->From\n";
        else
            $headers .= "From: $this->FromName <$this->From>\n";
        $headers .= "X-Sender: <$this->From>\n"; 
        $headers .= "X-Mailer: eZ publish PHP\n"; // mailer
        $headers .= "X-Priority: $this->Priority\n"; // Urgent message!
        $headers .= "Return-Path: <$this->From>\n";  // Return path for errors
        
        mail( $this->To, $this->Subject, $this->Body, $headers )
            or print( "Error: could not send email." );
    }

    /*!
      \static
      Splits a list of email addresses into an array where each entry is an email address.
    */
    function &splitList( $emails )
    {
        $emails =& preg_split( "/[,;]/", $emails );
        return $emails;
    }

    /*!
      \static
      Merges an array of email addresses into a list of email addresses.
    */
    function &mergeList( $emails )
    {
        if ( !is_array( $emails ) )
            return false;
        $emails =& implode( ",", $emails );
        return $emails;
    }

    /*!
      \static
      Static function for validating e-mail addresses.

      Returns true if successful, false if not.
    */
    function validate( $address )
    {
        
        $pos = ( ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address) );
        
        return $pos;
    } 
    
    var $To;
    var $From;
    var $FromName;
    var $Subject;
    var $Body;
    var $Priority;
    
}
?>
