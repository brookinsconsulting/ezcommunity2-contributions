<?php
// 
// $Id: ezimapmail.php,v 1.7 2002/04/17 20:48:37 fh Exp $
//
// Definition of eZIMAPMail class
//
// Created on: <15-Mar-2001 20:40:06 fh>
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

//!! eZIMAPMail
//! eZIMAPMail
/*!

  This is a class used for receiving mail through an IMAP server. 
  If you want to send mail or read mail from the database, use eZMail.
  Example code:
  \code

  \endcode
*/

include_once( "ezmail/classes/ezmailfolder.php" );
include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezmail/classes/ezmaildefines.php" );
include_once( "ezmail/classes/imapfunctions.php" );

class eZIMAPMail
{
    /*!
      Constructs a new eZIMAPMail object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZIMAPMail( $id = -1  )
    {
        $this->FilesAttached = false;
        $this->Files = array();
        $this->UDate = time();
        if( $id != -1 )
        {
            $elements = $this->decodeMailID( $id );
            $this->Account = $elements["AccountID"];
            $this->ID = $elements["MailID"];
            $this->Folder = $elements["FolderName"];
            $this->get();
        }
            // default values
    }

        /*!
      \static
      Functions to encode more information into one url position. This allows us to use the same
      templates for remote and local mail.
    */
    function encodeMailID( $accountID = -1 , $mailID = -1, $folder="INBOX" )
    {
        if( $accountID == -1 || $mailID == -1 )
        {
            $accountID = $this->Account;
            $id = $this->ID;
            $folder = $this->Folder;
        }
//        echo "Was here: $accountID, $folderName";
        $folder = ereg_replace( "/", "#", $folder );
        return rawurlencode( $accountID . "-" . $id . "-" . $folder );
    }
    
    /*!
      \static
      Returns an array with the 
    */
    function decodeMailID( $codedString )
    {
        $elements = explode( "-", $codedString, 3 ); // max 1 split rest is foldername.
        $elements["AccountID"] = $elements[0];
        $elements["MailID"] = $elements[1];
        $elements["FolderName"] = ereg_replace( "#", "/", $elements[2] );
        return $elements;
    }

    /*!
      Deletes a eZMail object from the database.
    */
    function delete( $id = -1 )
    {
        return true;
    }

    /*!
      \static
      IMAPMail spesific. Deletes a mail from server.
     */
    function deleteMail( $account, $folder, $id )
    {
        if( get_class( $account ) != "ezmailaccount" )
            $account = new eZMailAccount( $account );

        $connections =& IMAPConnections::instance();
        $mbox = $connections->getConnection( $account, $folder );

//        $server = $account->server();
        $ok = imap_delete( $mbox, $id );

        if( !$ok )
            echo "imap_deletemail failed: " . imap_last_error() . "\n";

        imap_expunge( $mbox ); // really delete the mail.
        
        return $ok;
    }
    
    /*!
      Stores a mail to the database.
    */
    function store()
    {
        return true;
    }    

    // What to do if we have a temporary mail.. this does not look like a good idea to me.
    function removeContacts( $mailID )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZMail_MailContactLink WHERE MailID='$mailID'" );
        eZDB::finish( $res, $db );
    }
    
    function addContact( $mailID, $contactID, $companyEdit = true )
    {
        if ( $companyEdit )
            $contact = "CompanyID";
        else
            $contact = "PersonID";
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZMail_MailContactLink" );
        $nextID = $db->nextID( "eZMail_MailContactLink", "ID" );
        $res[] = $db->query( "INSERT INTO eZMail_MailContactLink
                              (ID, MailID, $contact)
                              VALUES
                              ('$nextID', '$mailID', '$contactID')" );
        $db->unlock();
        eZDB::finish( $res, $db );
    }
    
    /*!
      Fetches the object information from the database.
        TODO:
        - fetch email address correctly. (not just name) (done)
        - fetch email date.
        - offset, range

    */
    function get( $id = "" )
    {
        $account = new eZMailAccount( $this->Account );
        $connections =& IMAPConnections::instance();
        $mbox = $connections->getConnection( $account, $this->Folder );

//        $header = imap_header( $mbox, $this->ID );
        
//        $this->UDate( $header->udate );
        getHeaders( $this, $mbox, $this->ID ); // fetch header information
        $mailstructure = imap_fetchstructure( $mbox, $this->ID );
        disectThisPart( $mailstructure, "1", $mbox, $this->ID, $this,0, true );
        
//        echo "<pre>";print_r( $this );echo "</pre>";
        
//    echo imap_body( $mbox, $mailID ); 
    }
    
    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->encodeMailID( );
    }

    /*!
      IMAPMail only. Set the internal mail nr for this mail.
     */
    function setMailNr( $nr )
    {
        $this->ID = $nr;
    }

    /*!
      IMAPMail only, set the folder that this mail belongs to (full path)
     */
    function setPath( $path )
    {
        $this->Folder = $path;
    }

    /*!
      IMAPMail only. Set the internal mail nr for this mail.
     */
    function setAccount( $account )
    {
        if( get_class( $account ) == "ezmailaccount" )
        {
            $this->Account = $account->id();
        }
        else
        {
            $this->Account = $account;
        }
    }

    
    /*!
      Returns the receiver address.
    */
    function to( $asHTML = true )
    {
        if( $asHTML )
            return htmlspecialchars( $this->To );
        
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
    function replyTo( $asHTML = true )
    {
        if( $asHTML )
            return htmlspecialchars( $this->ReplyTo );
        return $this->ReplyTo;
    }

    /*!
      Sets the receiver address.
    */
    function setReplyTo( $newReplyTo )
    {
        $this->ReplyTo = $newReplyTo;
    }

    
    /*! 
      Returns the receiver address. Wrapper function
    */
    function receiver( $asHTML = true )
    {
        if( $asHTML )
            return htmlspecialchars( $this->To );

        return $this->To;
    }

    /*!
      Sets the receiver address.  Wrapper function
    */
    function setReceiver( $newReceiver )
    {
        $this->To = $newReceiver;
    }

    
    /*!
      Returns the from address.
    */
    function from( $asHTML = true )
    {
        if( $asHTML )
            return htmlspecialchars( $this->From );
        return $this->From;
    }


    /*!
      Sets the from address.      
    */
    function setFrom( $newFrom )
    {
        $this->From = $newFrom;
    }

    /*!
      Returns a string containing all cc adresses.
     */
    function cc( $asHTML = true )
    {
        if( $asHTML )
            return htmlspecialchars( $this->Cc );
        return $this->Cc;
    }

    /*!
      Sets the cc addresses. Use , separating (; and : and " " should also work )
     */
    function setCc( $newCc )
    {
        $this->Cc = $newCc;
    }

    /*!
      Returns a string containing all bcc adresses.
     */
    function bcc( $asHTML = true )
    {
        if( $asHTML )
            return htmlspecialchars( $this->Bcc );
        return $this->Bcc;
    }

    /*!
      Sets the bcc addresses. Use , separating (; and : and " " should also work )
     */
    function setBcc( $newBcc )
    {
        $this->Bcc = $newBcc;
    }

    /*!
      Returns the message ID format : <number@serverID>
      Read in the RFC's if you want to know more about it..
     */
    function messageID()
    {
        return $this->MessageID;
    }

    /*!
      Sets the message ID. This is a server setting only so BE CAREFULL WITH THIS.
     */
    function setMessageID( $newMessageID )
    {
        $this->MessageID = $newMessageID;
    }

    /*!
      Returns the messageID that this message is a reply to.
     */
    function references()
    {
        return $this->References;
    }

    /*!
      Sets the messageID that this message is a reply to.
     */
    function setReferences( $newReference )
    {
        $this->References = $newReference;
    }

    /*!
      Returns the from name.
    */
    function fromName( $asHTML = true )
    {
        if( $asHTML )
            return htmlspecialchars( $this->FromName );
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
      Returns the sender address.
    */
    function sender( $asHTML = true  )
    {
        if( $asHTML )
            return htmlspecialchars( $this->From );
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
    function subject( $asHTML = true )
    {
        if( $asHTML )
            return htmlspecialchars( $this->Subject );
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
    function body( $asHTML = true )
    {
        if( $asHTML )
            return nl2br( htmlspecialchars( $asHTML ) );
        return $this->BodyText;
    }

    /*!
      Sets the body.
    */
    function setBody( $newBody )
    {
        $this->BodyText = $newBody;
    }

    
    /*!
      Sets the body.
    */
    function setBodyText( $newBody )
    {
        $this->BodyText = $newBody;
    }

    /*!
      Returns the userID of the user that owns this object
    */
    function owner()
    {
        return $this->UserID;;
    }

    /*!
      Sets the owner of this mail
    */
    function setOwner( $newOwner )
    {

        if ( get_class( $newOwner ) == "ezuser" )
            $this->UserID = $newOwner->id();
        else
            $this->UserID = $newOwner;
    }

    /*!
      Returns the size of this mail in bytes.
     */
    function size()
    {
        return $this->Size;
    }

    /*!
      Returns the size of this object in a human readable fasion.
      An array is returned with entries:
      "size" - original size
      "size-string" short size
      "unit" GB, MB, KB or B
     */
    function siSize()
    {

        $units = array( "GB" => 10737741824,
                        "MB" => 1048576,
                        "KB" => 1024,
                        "B" => 0 );
        $decimals = 0;
        $size = $this->Size;
        $shortsize = $this->Size;

        while ( list( $unit_key, $val ) = each( $units ) )
        {
            if ( $size >= $val )
            {
                $unit = $unit_key;
                if ( $val > 0 )
                {
                    $decimals = 2;
                    $shortsize = $size / $val;
                }
                break;
            }
        }
        $shortsize = number_format( ( $shortsize ), $decimals);
        $size = array( "size" => $size,
                       "size-string" => $shortsize,
                       "unit" => $unit );
        return $size;
    }
    
    /*!
      Returns the size of this mail in bytes.
     */
    function setSize( $value )
    {
        $this->Size = $value;
    }

    /*!
      Returns the date of this mail in unix date format.
     */
    function uDate()
    {
        return $this->UDate;
    }

    /*!
      Sets the date of this mail in unix date time format.
     */
    function setUDate( $value )
    {
        $this->UDate = $value;
    }

    /*
      Returns the status of this mail.
      0 - UNREAD
      1 - READ
      2 - REPLIED
      3 - FORWARDED
      4 - MAIL_SENT
    */
    function status()
    {
        return $this->Status;
    }

    /*!
      Sets the status of this mail.
     0 - UNREAD
     1 - READ
     2 - REPLIED
     3 - FORWARDED
     4 - MAIL_SENT
     If direct write is set the data will be written directly to the database. No need for calling store() afterwords. In order to do this you must be sure that the object
     is allready in the database.
     */
    function setStatus( $status, $directWrite = false )
    {
        $this->Status = $status;
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
        $pos = ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address );
        
        return $pos;
    }

    /*!
      \static
      Static function for extracting an e-mail from text

      Returns the first valid e-mail in address, returns false if no e-mail addresses found
    */
    function stripEmail( $address )
    {
        $res = ereg( '[/0-9A-Za-z\.\?\-\_]+' . '@' .
                     '[/0-9A-Za-z\.\?\-\_]+', $address, $email );
        if ( $res )
            return $email[0];
        else
            return 0;
    }
    

    /*
      Returns the first folder that this mail is a member of.
      TODO: What to return here?!?
     */
    function folder( $as_object = true )
    {
    }

    /*!
      \static
      Returns all mail that belongs to this user as an array of eZMail objects.
      TODO: Don't think this is of any use either.
     */
    function getByUser( $user = false, $onlyUnread = false )
    {
    }

    /*!
      Returns all the images associated with this mail.
      TODO:
     */
    function images()
    {
    }

    /*!
      \static  
      
      Returns true if the given account belongs to the given user.
      TODO: Usable?
     */
    function isOwner( $user, $mailID )
    {
    }

    /*
    Adds an attachment
    With IMAP this is just an array containing pointers to the filename and to the part number where it can be found.
    $file["filename"] = "filename.txt";
    $file["part"] = 5;
    */
    function addFile( $param )
    {
        $this->Files[] = $param;
    }

    /*!
      Returns all attachments associatied with this mail.
      Returns an array of arrays. Where each subarray is an attachment, having a "filename" containing the filename and
      a "part" containg the full id of the attachment (account, message, partnr).
    */
    function files()
    {
        $returnArray = array();
        if( count( $this->Files ) > 0 )
        {
            foreach( $this->Files as $file )
            {
//                        return rawurlencode( $accountID . "-" . $id . "-" . $folder );
                $file["part"] = rawurlencode( $this->Account . "-". $this->ID. "-" . $file["part"] . "-" .$file["encoding"] . "-". ereg_replace( "/", "#", $this->Folder) );
                $returnArray[] = $file;
            }
        }
        return $returnArray;
    }

    /*!
      Fetches an attachment from the server and returns it.
      TODO: Put some sort of upper attachment limit here. (downloading 20mb+ files into memory doesn't seem like a good idea to me).
     */
    function &fetchAttachment( $accountID, $mailID, $partID, $encoding, $folderID )
    {
        $account = new eZMailAccount( $accountID );
        $folderID = ereg_replace( "#", "/", $folderID );
//        echo "$accountID, $mailID, $partID, $folderID";
//        exit();
        $connections =& IMAPConnections::instance();
        $mbox = $connections->getConnection( $account, $folderID );

//        $header = imap_header( $mbox, $this->ID );
        
        $file = decode( $encoding, imap_fetchbody( $mbox, $mailID, $partID ) );
//        echo "<pre>";print_r( $this );echo "</pre>";
        
        return $file;
    }

    /*!
      TODO: Implement this? Then we need addfile etc.
     */
    function send()
    {
    }
    
    /*!
      \static

      returns every mail that is containing the search string
    */
    function search( $text, $user = -1 )
    {
    }

    /* Mail specific variables */
    var $To;
    /// email adress
    var $From;
    /// users name
    var $FromName; 
    var $Cc;
    var $Bcc;
    /// used with the reference.
    var $MessageID;
    /// used to thread mail, originally from News
    var $References; 
    var $ReplyTo;
    
    var $Subject;
    var $BodyText;

    var $Size;
    var $UDate;
    
    var $Status;
    
    var $FilesAttached;
    var $Files;
    
    /* database specific variables */
    var $ID;
    var $Folder;

    // is this one needed here?
    var $UserID;
}

?>
