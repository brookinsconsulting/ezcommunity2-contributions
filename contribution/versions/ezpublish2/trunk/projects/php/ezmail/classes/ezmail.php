<?
// 
// $Id: ezmail.php,v 1.13 2001/03/27 12:03:54 fh Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <fh@ez.no>
// Created on: <15-Mar-2001 20:40:06 fh>
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

\endcode
*/

include_once( "ezmail/classes/ezmailfolder.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZMail
{
/************* CONSTRUCTORS DESTRUCTORS (virtual) ************************/    
    /*!
      Constructs a new eZMail object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZMail( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        $this->parts = array();
        // default value
        $this->IsPublished = "false";
        
        if ( $id != "" )
        {

            $this->ID = $id;
            if ( $fetch == true )
            {                
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
                
            }
        }
        else
        {
            $this->State_ = "New";
            
        }
    }

    /*!
      Deletes a eZMail object from the database.
    */
    function delete( $id = -1 )
    {
        $db = eZDB::globalDatabase();

        // DELETE ALL ATTACHMENTS
        if ( $id == -1 )
        {
            $db->query( "DELETE FROM eZMail_Mail WHERE ID='$this->ID'" );
        }
        else
        {
            $db->query( "DELETE FROM eZMail_Mail WHERE ID='$id'" );
        }
        
        return true;
    }

/***************** Get / fetch from database *******************************/
    /*!
      Stores a mail to the database.
    */
    function store()
    {
        $this->dbInit();

        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZMail_Mail SET
		                         UserID='$this->UserID',
                                 ToField='$this->To',
                                 FromField='$this->From',
                                 Cc='$this->Cc',
                                 Bcc='$this->Bcc',
                                 MessageID='$this->MessageID',
                                 Reference='$this->References',
                                 ReplyTo='$this->ReplyTo',
                                 Subject='$this->Subject',
                                 BodyText='$this->BodyText',
                                 IsRead='$this->IsRead',
                                 Size='$this->Size'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZMail_Mail SET
		                         UserID='$this->UserID',
                                 ToField='$this->To',
                                 FromField='$this->From',
                                 Cc='$this->Cc',
                                 Bcc='$this->Bcc',
                                 MessageID='$this->MessageID',
                                 Reference='$this->References',
                                 ReplyTo='$this->ReplyTo',
                                 Subject='$this->Subject',
                                 BodyText='$this->BodyText',
                                 IsRead='$this->IsRead',
                                 Size='$this->Size'
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
        return true;
    }    

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $mail_array, "SELECT * FROM eZMail_Mail WHERE ID='$id'" );
            if ( count( $mail_array ) > 1 )
            {
                die( "Error: Mails with the same ID was found in the database. This should not happen." );
            }
            else if( count( $mail_array ) == 1 )
            {
                $this->ID = $mail_array[0][ "ID" ];
                $this->UserID = $mail_array[0][ "UserID" ];
                $this->To = $mail_array[0][ "ToField" ];
                $this->From = $mail_array[0][ "FromField" ];
                $this->FromName = $mail_array[0][ "FromName" ];
                $this->Cc = $mail_array[0][ "Cc" ];
                $this->Bcc = $mail_array[0][ "Bcc" ];
                $this->MessageID = $mail_array[0][ "MessageID" ];
                $this->References = $mail_array[0][ "Reference" ];
                $this->ReplyTo = $mail_array[0][ "ReplyTo" ];
                $this->Subject = $mail_array[0][ "Subject" ];
                $this->BodyText = $mail_array[0][ "BodyText" ];
                $this->IsRead = $mail_array[0][ "IsRead" ];
                $this->Size = $mail_array[0][ "Size" ];

                $this->State_ = "Coherent";
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }
    
/****************** BORING SET AND GET FUNCTIONS ***************************/    
    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the receiver address.
    */
    function to()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->To;
    }

    /*!
      Sets the receiver address.
    */
    function setTo( $newTo )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->To = $newTo;
    }


    /*!
      Returns the receiver address.
    */
    function replyTo()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ReplyTo;
    }

    /*!
      Sets the receiver address.
    */
    function setReplyTo( $newReplyTo )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ReplyTo = $newReplyTo;
    }

    
    /*! 
      Returns the receiver address. Wrapper function
    */
    function receiver()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->To;
    }

    /*!
      Sets the receiver address.  Wrapper function
    */
    function setReceiver( $newReceiver )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->To = $newReceiver;
    }

    
    /*!
      Returns the from address.
    */
    function from()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->From;
    }


    /*!
      Sets the from address.      
    */
    function setFrom( $newFrom )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->From = $newFrom;
    }

    function cc()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Cc;
    }

    function setCc( $newCc )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Cc = $newCc;
    }

    function bcc()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Bcc;
    }

    function setBcc( $newBcc )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Bcc = $newBcc;
    }
    
    function messageID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->MessageID;
    }

    function setMessageID( $newMessageID )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->MessageID = $newMessageID;
    }

    function references()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->References;
    }

    function setReferences( $newReference )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->References = $newReference;
    }

    /*!
      Returns the from name.
    */
    function fromName()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->FromName;
    }

    /*!
      Sets the from name.      
    */
    function setFromName( $newFrom )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->FromName = $newFrom;
    }

    /*!
      Returns the sender address.
    */
    function sender()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->From;
    }

    /*!
      Sets the sender address.      
    */
    function setSender( $newSender )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->From = $newSender;
    }
    
    /*!
      Returns the subject.
    */
    function subject()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Subject;
    }

    /*!
      Sets the subject of the mail.
    */
    function setSubject( $newSubject )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Subject = trim( $newSubject );
    }

    /*!
      returns the body.
    */
    function body()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->BodyText;
    }

    /*!
      Sets the body.
    */
    function setBodyText( $newBody )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->BodyText = $newBody;
    }

    /*!
      Returns the userID of the user that owns this object
    */
    function owner()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->UserID;;
    }

    /*!
      Sets the owner of this mail
    */
    function setOwner( $newOwner )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( get_class( $newOwner ) == "ezuser" )
            $this->UserID = $newOwner->id();
        else
            $this->UserID = $newOwner;
    }

    /*!
      Returns the size of this mail in bytes.
     */
    function size()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Size;
    }

    function siSize()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $units = array( "GB" => 10737741824,
                        "MB" => 1048576,
                        "KB" => 1024,
                        "B" => 0 );
        $decimals = 0;
        $size = $this->Size;
        $shortsize = $this->Size;

        while( list($unit_key,$val) = each( $units ) )
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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Size = $value;
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

    /*
      \static
      
      Returns true if the mail with the given identification is allready downloaded for the given user.
     Note: this is the header ID we are talking about.
    */
    function isDownloaded( $mailident, $userID )
    {
        $database =& eZDB::globalDatabase();
        $database->query_single( $res, "SELECT count( ID ) as Count FROM eZMail_Mail WHERE UserID='$userID' AND MessageID='$mailident'" );

        $ret = true;
        if( $res["Count"] == 0 )
            $ret = false;
        
        return $ret;    
    }

    /*
      Returns the first folder that this mail is a member of.
     */
    function folder( $AsObject = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $res = array();
        $this->Database->array_query( $res, "SELECT FolderID FROM eZMail_MailFolderLink WHERE MailID='$this->ID'" );

        if( count( $res ) > 0 )
        {
            if( $AsObject == true )
                return new eZMailFolder( $res[0]["FolderID"] );

            return $res[0]["FolderID"];
        }

        return false;
    }

    /*!
      \static
      Returns all mail that belongs to this user as an array of eZMail objects.
     */
    function getByUser( $user = false, $onlyUnread = false )
    {
        if( get_class( $user ) != "ezuser" )
            $user = eZUser::currentUser();

        $unreadOnlySQL = "";
        if( $onlyUnread == false )
        {
            $unreadOnlySQL = "AND IsRead='0'";
        }
        
        $return_array = array();
        $res = array();
        $userid = $user->id();
        $database = eZDB::globalDatabase();
        $query = "SELECT ID FROM eZMail_Mail WHERE UserID='$userid' $unreadOnlySQL";
        $database->array_query( $res, $query );

        for ( $i=0; $i < count($res); $i++ )
        {
            $return_array[$i] = new eZMail( $res[$i]["ID"] );
        }

        return $return_array;
    }

    /*
      Adds an attachment to this mail
     */
    function addFile( $file )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
        if ( get_class( $file ) == "ezvirtualfile" )
        {
            $this->dbInit();
 
            $fileID = $file->id();
 
            $this->Database->query( "INSERT INTO eZMail_MailAttachmentLink SET MailID='$this->ID', FileID='$fileID'" );
        }
    }
 
    /*!
      Deletes an attachment from the mail.
    */
    function deleteFile( $file )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
        if ( get_class( $file ) == "ezvirtualfile" )
        {
            $this->dbInit();
 
            $fileID = $file->id();
            $file->delete();
            $this->Database->query( "DELETE FROM eZMail_MailAttachmentLink WHERE MailID='$this->ID' AND FileID='$fileID'" );
        }
    }
 
    /*!
      Returns all attachments associatied with this mail.
    */
    function files()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
       $this->dbInit();
 
       $return_array = array();
       $file_array = array();
 
       $this->Database->array_query( $file_array, "SELECT FileID FROM eZMail_MailAttachmentLink WHERE MailID='$this->ID'" );
 
       for ( $i=0; $i<count($file_array); $i++ )
       {
           $return_array[$i] = new eZVirtualFile( $file_array[$i]["FileID"], false );
       }
 
       return $return_array;
    }

    /*
      Adds an image attachment.
     */
    function addImage( $image )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
        if( get_class( $image ) == "ezimage" )
        {
            $imageID = $image->id();
            $this->dbInit();
            $this->Database->query( "INSERT INTO eZMail_MailImageLink SET MailID='$this->ID', ImageID='$imageID'" );
        }
    }
 
    /*!
      Deletes an eZImage attachment from the mail.
     */
    function deleteImage( $image )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
        if( get_class( $image ) == "ezimage" )
        {
            $this->dbInit();
 
            $imageID = $image->id();
            $image->delete();
            $this->Database->query( "DELETE FROM eZMail_MailImageLink WHERE MailID='$this->ID' AND ImageID='$imageID'" );
        }
    }
 
    /*!
      Returns all the images associated with this mail.
     */
    function images()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
       $this->dbInit();
 
       $return_array = array();
       $image_array = array();
 
       $this->Database->array_query( $image_array, "SELECT ImageID FROM eZMail_MailImageLink WHERE MailID='$this->ID'" );
 
       for ( $i=0; $i<count($image_array); $i++ )
       {
           $return_array[$i] = new eZImage( $image_array[$i]["ImageID"], false );
       }
       return $return_array;
    } 

    /*!
      \static
      
      Returns true if the given account belongs to the given user.
     */
    function isOwner( $user, $mailID )
    {
        if( get_class( $user ) == "ezuser" )
            $user = $user->id();
        
        $database =& eZDB::globalDatabase();
        $database->query_single( $res, "SELECT UserID from eZMail_Mail WHERE ID='$mailID'" );
        if( $res["UserID"] == $user )
            return true;
        
        return false;
    }

    /*!
      Returns a new eZMail object with all fields set according to the parameter.
      Valid values are: "reply", "replyall", "forward". If no parameter is given
      it just returns a copy of the mail. If $attachments is set to true also the attachments are copied.
      NOTE: The returned mail is not member of any folders. Set a folder for this mail
      or it will be LOST.
     */
    function &copyMail( $copyType = "normal", $attachments = false )
    {
        $copy = new eZMail();

        if( $copyType == "normal" || $copyType == "forward" )
        {
            if( $copyType == "normal" )
            {
                $copy->To = $this->To;
                $copy->From = $this->From;
                $copy->FromName = $this->FromName;
                $copy->Cc = $this->Cc;
                $copy->Bcc = $this->Bcc;
                $copy->ReplyTo = $this->ReplyTo;
            }
            else
            {
                $copy->From = $this->To;
            }
            $copy->BodyText = $this->BodyText;
            $copy->MessageID = $this->MessageID;
            $copy->References = $this->References;
        }
        else if( $copyType == "reply" || $copyType == "replyall" )
        {
            $copy->To = $this->From;
            $copy->Subject = "Re: " . $this->Subject();
            $copy->References = $this->MessageID;
            $copy->ReplyTo = $this->To;
            $copy->UserID = $this->UserID;

            if( $copyType == "replyall" )
                $copy->Cc = $this->Cc;

            $sentnsArray = explode( "\n", $this->BodyText );
            $resultArray = array();

            foreach( $sentnsArray as $sentence )
                $resultArray[] = "> " . $sentence . "\n";

            $copy->BodyText = implode( "", $resultArray );
        }
        
        $copy->store();
        return $copy;
    }
    
    /***************** FUNCTIONS THAT ARE USED WHEN SENDING MAIL, IDEAS FROM:
                       Sascha Schumann <sascha@schumann.cx>
                       Tobias Ratschiller <tobias@dnet.it
                       extended and modified to fit eZPublish needs by
                       Frederik Holljen <fh@ez.no>
    *****************************/

    /*!
      Sends the mail with the values specified.
     */
    function send() 
    {
        $files = $this->files();
        foreach( $files as $file )
        {
            $filename = "ezfilemanager/files/" . $file->fileName();
            $attachment = fread( fopen( $filename, "r"), filesize( $filename ) );
            $this->add_attachment( $attachment, $file->originalFileName(), "image/jpeg" );
        }
        
        $mime = "";
        if( !empty( $this->From ) )
            $mime .= "From: " . $this->From . "\n";
        if( !empty( $this->Cc ) )
            $mime .= "Cc: " . $this->Cc . "\n";
        if( !empty( $this->Bcc ) )
            $mime .= "Bcc: " . $this->Bcc . "\n";
        if( !empty( $this->Bcc ) )
            $mime .= "Reply-To: " . $this->ReplyTo . "\n";
        if( !empty( $this->BodyText ) )
            $this->add_attachment( $this->BodyText, "", "text/plain");   

        $mime .= "MIME-Version: 1.0\n".$this->build_multipart();
        mail( $this->To, $this->Subject, "", $mime);
        $this->parts = array();
    }
    
     /*!
       \private
       
       void add_attachment(string message, [string name], [string ctype])
       Add an attachment to the mail object
     */
    function add_attachment($message, $name = "", $ctype = "application/octet-stream")
    {
        $this->parts[] = array (
            "ctype" => $ctype,
            "message" => $message,
            "encode" => $encode,
            "name" => $name
            );
    }

    
    /*!
      \private
      
      void build_message( array part )
      Build message parts of an multipart mail
    */
    function build_message($part)
    {
        $message = $part["message"];
        $message = chunk_split(base64_encode($message));
        $encoding = "base64";
        return "Content-Type: ".$part["ctype"].
            ($part["name"]?"; name = \"".$part["name"]."\"" : "").
            "\nContent-Transfer-Encoding: $encoding\n\n$message\n";
    }
    
    /*!
      \private
      
      void build_multipart()
      Build a multipart mail
    */
    function build_multipart() 
    {
        $boundary = "b".md5(uniqid(time()));
        $multipart = "Content-Type: multipart/mixed; boundary = $boundary\n\nThis is a MIME encoded message.\n\n--$boundary";
        
        for($i = count( $this->parts )-1; $i >= 0; $i--) 
        {
            $multipart .= "\n".$this->build_message($this->parts[$i])."--$boundary";
        }
        return $multipart.= "--\n";
    }


    /****************** END MAIL SENDING FUNCTIONS ***********************/



    /*!
      \private
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    // this variable is only used during the buildup of a mail that is beeing sent. NEVER access directly!!!
    var $parts;

    /* Mail specific variables */
    var $To;
    var $From; // email adress
    var $FromName; // users name
    var $Cc;
    var $Bcc;
    var $MessageID; // used with the reference.
    var $References; // used to thread mail, originally from News
    var $ReplyTo;
    
    var $Subject;
    var $BodyText;

    var $Size;
    // we need a state so we can store if this mail is replyed/forwarded...
    // I suggest
    // 0 - Unread
    // 1 - Read
    // 2 - Replied
    // 3 - Forwarded
    
    var $IsRead;
    /* database specific variables */
    var $ID;
    var $UserID;
    var $Database;
    var $IsConnected;
    var $State_;
}
/*
       The ADDRESS structure is a parsed form of a linked list of RFC 822
addresses.  It contains the following information:

char *personal;			personal name phrase
char *adl;			at-domain-list (also called "source
				 route")
char *mailbox;			mailbox name
char *host;			domain name of mailbox's host
char *error;			error in address from smtp_mail(); if
				 an error is returned from smtp_mail()
				 for one of the recipient addresses
				 the SMTP server's error text for that
				 recipient can be found here.  If it
				 is null then there was no error (or
				 an error was found with a prior
				 recipient
ADDRESS *next;			pointer to next address in list


     The ENVELOPE structure is a parsed form of the RFC 822 header.
Its member names correspond to the RFC 822 field names.  It contains
the following information:

char *remail;			remail header if any
ADDRESS *return_path;		error return address
char *date;			message composition date string
ADDRESS *from;			from address list
ADDRESS *sender;		sender address list
ADDRESS *reply_to;		reply address list
char *subject;			message subject string
ADDRESS *to;			primary recipient list
ADDRESS *cc;			secondary recipient list
ADDRESS *bcc;			blind secondary recipient list
char *in_reply_to;		replied message ID
char *message_id;		message ID
char *newsgroups;		USENET newsgroups
char *followup_to;		USENET reply newsgroups
char *references;		USENET references
*/

?>


