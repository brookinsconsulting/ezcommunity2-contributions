<?php
//
// $Id: ezmailfunctions.php,v 1.15 2002/02/07 17:13:13 fh Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

include_once( "ezmail/classes/ezmail.php" );
include_once( "classes/ezfile.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );

/* This file contains a list of functions that are used by the various classes.
 They are put here because they are general and to keep the classes from beeing
crouded.*/

/* Ok, here follows functions that are used to create a new mail. The mail is returned as a string */

$internalMailParts = array(); // internal variable used to build the mail.

/*!
  Creates a mime encoded mail ready to send.
  If you don't use the mail() function to send the mail set withToAndSubject to true
  and the To and Subject header field will be included.
 */
function &createMimeMail( &$mail, $withToAndSubject = false )
{
    global $internalMailParts; 
    if ( $mail->FilesAttached == true )
    {
        $files = $mail->files();
        if( count( $files ) )
        {
            foreach ( $files as $file )
            {
//                    echo "Added attachment";
                $filename = "ezfilemanager/files/" . $file->fileName();
                $attachment = fread( eZFile::fopen( $filename, "r" ), eZFile::filesize( $filename ) );
                add_attachment( $attachment, $file->originalFileName(), "image/jpeg" );
            }
        }
    }
        
    $mime = "";
    if( $withToAndSubject )
    {
        $mime .= "To: " . $mail->To . "\n";
        $mime .= "Subject: " . $mail->Subject . "\n";
    }
    if ( !empty( $mail->From ) )
    {
        if ( !empty( $mail->FromName ) )
            $mime .= "From: " . $mail->FromName . " <" . $mail->From . ">\n";
        else
            $mime .= "From: "  . $mail->From . "\n";
    }
    if ( !empty( $mail->Cc ) )
        $mime .= "Cc: " . $mail->Cc . "\n";
    if ( !empty( $mail->Bcc ) )
        $mime .= "Bcc: " . $mail->Bcc . "\n";
    if ( !empty( $mail->Bcc ) )
        $mime .= "Reply-To: " . $mail->ReplyTo . "\n";
    if ( !empty( $mail->BodyText ) )
        add_attachment( $mail->BodyText, "", "text/plain");   

    $mime .= "MIME-Version: 1.0\n" . build_multipart();
    $internalMailParts = array();

//    echo "Built mail $mime <BR>";
    
    return $mime;
}

     /*!
       void add_attachment(string message, [string name], [string ctype])
       Add an attachment to the mail object
     */
    function add_attachment( $message, $name = "", $ctype = "application/octet-stream" )
    {
        global $internalMailParts; 

        $internalMailParts[] = array (
            "ctype" => $ctype,
            "message" => $message,
            "encode" => $encode,
            "name" => $name
            );
    }
    
    /*!
      void build_message( array part )
      Build message parts of an multipart mail
    */
    function build_message( &$part )
    {
        $message = $part["message"];
        $message = chunk_split( base64_encode( $message ) );
        $encoding = "base64";
        return "Content-Type: " . $part["ctype"] . 
            ( $part["name"] ? "; name = \"" . $part["name"] . "\"" : "" ) .
            "\nContent-Transfer-Encoding: $encoding\n\n$message\n";
    }
    
    /*!
      void build_multipart()
      Build a multipart mail
    */
    function build_multipart() 
    {
        global $internalMailParts; 
        
        $boundary = "b" . md5( uniqid( time() ) );
        $multipart = "Content-Type: multipart/mixed;\n   boundary=$boundary\n\nThis is a MIME encoded message.\n\n--$boundary";
        
        for ( $i = count( $internalMailParts ) - 1; $i >= 0; $i-- )
        {
            $multipart .= "\n" . build_message( $internalMailParts[$i] ) . "--$boundary";
        }
        return $multipart .= "--\n";
    }


/*!
  Gets all the headers from a mail, and puts them into an eZMail object.
  NOTE: Do we really need to fetch them again?!?
 */
function getHeaders( &$mail, $imap_stream, $msgno )
{
    $headers = imap_headerinfo( $imap_stream, $msgno );
//    echo "<pre>"; echo $headers; echo "</pre>";
//    print( "To: " . getDecodedHeader( $headers->toaddress ). "<br>" );
//    print( "From: " . getDecodedHeader( $headers->fromaddress ). "<br>"); // from NAME
//    print( "Reply: " . getDecodedHeader( $headers->reply_toaddress ) ."<br>");
//    print( "Subject: " . getDecodedHeader( $headers->subject )."<br>");
//    print( "MessageID: " . getDecodedHeader( $headers->message_id ). "<br>" );
//    print( "Date: " . getDecodedHeader( $headers->date ) . "<br>" );
//    print( "ReplyID: " . getDecodedHeader( $headers->in_reply_to ). "<br>" );
    $mail->setUDate( $headers->udate );
//    echo ": $headers->udate <br>";
    $mail->setTo( getDecodedHeader( $headers->toaddress )  );
    $mail->setFrom( getDecodedHeader( $headers->fromaddress ) ); // from NAME
    $mail->setReplyTo( getDecodedHeader( $headers->reply_toaddress ) );
    $mail->setSubject( getDecodedHeader( $headers->subject ) );
    $mail->setMessageID( getDecodedHeader( $headers->message_id ) );
//    print( "Date: " . getDecodedHeader( $headers->date ) . "<br>" );
    $mail->setReferences( getDecodedHeader( $headers->in_reply_to ) );
}

/*
  Decodes a header and returns it. (Support for non ASCII characters in mail header)
 */
function getDecodedHeader( $headervalue )
{
    $decode = imap_mime_header_decode( $headervalue );
    $ret = "";
    for( $i = 0; $i < count( $decode ); $i++ )
    {
        $ret = $i == 0 ? $decode[$i]->text : $ret . $decode[$i]->text;
    }
    return $ret;
}



//How to call this function:
//$msg_struct = imap_fetchstructure($mconn, $msg_no);
//
//disectThisPart($msg_struct, "");
//imap_fetchbody( $mbox, $msgnum, $partnum );
/*!
  This is the main function that fetches the body parts of a message.
  Basicly there can be two types of body parts. Normal parts and multiparts which can contain several underparts.
  In case we find a multipart we just call ourselves recursivly.
  If not this is either a text part which we add to the main text body, or it is something else possibly an attachment.
  In addition to this there is also a special disposition part where attachments and inlines CAN be stored.
  If imap is set, attachments are added only with the attachment info. This is because we can't save the data in the usual way since it can't be connected directly with a mail. However we may create som sort of caching for this to speed things up later.
  TODO:
  -If we get text/HTML we should strip the crappy html/header tags and show it as HTML.. for this we need some special function in eZMail
  either indication that the body of this mail is html or we need to store it completely seperatly from the usual body.
  -fetch inline images in html mail.
 */
function disectThisPart( $this_part, $part_no, $mbox, $msgnum, &$mail, $level=0, $imap = false )
{
    /** Check for disposition parts **/
	if ( $this_part->ifdisposition )
    {
		if ( $this_part->disposition == "ATTACHMENT" || $this_part->disposition == "INLINE" )
        {
            // First see if they sent a filename
			$att_name = "unknown";
            for ( $lcv = 0; $lcv < count( $this_part->parameters ); $lcv++ )
            {
                $param = $this_part->parameters[$lcv];

                if ( $param->attribute == "NAME" )
                {
                    $att_name = $param->value;
                    break;
	            }
	        }
            // If we are in IMAP mode, only get the part number and filename. Add it to the mail.
            if( $imap )
            {
                $file = array();
                $file["filename"] = $att_name;
                $file["part"] = $part_no;
                $file["encoding"] = $this_part->encoding;
//                echo "<PRE>";
//                print_r( $file );
//                echo "</PRE>";
                $mail->addFile( $file );
            }
            else
            {
                addAttachment( $mail, decode( $this_part->encoding, imap_fetchbody( $mbox, $msgnum, $part_no ) ), $att_name );
            }
        }
        else // INLINE should be here! For now just handle it as an attachment.
        {
			// disposition can also be used for images in HTML (Inline)
		}
	}
    else
    {
        /** Not a disposittion part, lets see what this is **/
		switch ( $this_part->type )
        {
            /** ooh, its a text part, lets add it to the main body TODO: Unless filename supplied.. attachment in that case..**/
            case TYPETEXT:
            {
                $mime_type = "text";
                $value = decode( $this_part->encoding, imap_fetchbody( $mbox, $msgnum, $part_no ) );
                $mail->setBodyText( $value );
            }
			break;
            /** Oh no.. multipart we must do it all over again. Lets call ourselves then **/
            case TYPEMULTIPART:
            {
                $mime_type = "multipart";
                for ( $i = 0; $i < count( $this_part->parts ); $i++ )
                {
                    if ( $level != 0 )
                        $part_no = $part_no.".";
                    else
                        $part_no = "";
                
                    for ( $i = 0; $i < count( $this_part->parts ); $i++ )
                    {
                        disectThisPart( $this_part->parts[$i], $part_no.( $i + 1 ), $mbox, $msgnum, $mail, 1, $imap );
                    }
                }
            }
			break;
            /** Since we are web mail nothing special can be done with this other stuff that people may attach.
                (Hm... autodownload and start executables?!? hehe!) **/
            case TYPEMESSAGE:
//                $mime_type = "message";
            case TYPEAPPLICATION:
//                $mime_type = "application";
            case TYPEAUDIO:
//                $mime_type = "audio";
            case TYPEIMAGE:
//                $mime_type = "image";

            case TYPEVIDEO:
//                $mime_type = "video";
            case TYPEMODEL:
                $mime_type = "model";
                $att_name = "unknown";
                for ( $lcv = 0; $lcv < count( $this_part->parameters ); $lcv++ )
                {
                    $param = $this_part->parameters[$lcv];

                    if ( $param->attribute == "NAME" )
                    {
                        $att_name = $param->value;
                        break;
                    }
                }
                // If we are in IMAP mode, only get the part number and filename. Add it to the mail.
                if( $imap )
                {
                    $file = array();
                    $file["filename"] = $att_name;
                    $file["part"] = $part_no;
//                    echo "<PRE>";
//                    print_r( $file );
//                    echo "</PRE>";
                    $mail->addFile( $file );
                }
                else
                {
                    addAttachment( $mail, decode( $this_part->encoding, imap_fetchbody( $mbox, $msgnum, $part_no ) ), $att_name );
                }

                /** Hm... someone sent us something and we don't know what is... lets leave it alone **/
            default:
                $mime_type = "unknown";
            break;
		}
		$full_mime_type = $mime_type."/".$this_part->subtype;
	}
}

/*!
  Convenience function for adding an attachment to a mail. Dumps the data to a file
  creates a virtual file and adds this to the mail.
 */
function addAttachment( &$mail, &$data , $fileName )
{
    $file = new eZFile();
    $file->dumpDataToFile( $data, $fileName );

    $uploadedFile = new eZVirtualFile();
    $uploadedFile->setName( $fileName );
    $uploadedFile->setDescription( "" );

    $uploadedFile->setFile( $file );
        
    $uploadedFile->store();

    $mail->addFile( $uploadedFile );
}

/*!
  Decodes a value encoded with enctype. Returns the decoded value.
 */
function &decode( $enctype, &$value )
{
    $ret = "";
    switch ($enctype)
    {
        case ENC7BIT:
            $ret = $value;
            break;
        case ENC8BIT:
            $ret = $value;
            break;
        case ENCBINARY:
            $ret = $value;
            break;
        case ENCBASE64:
            $ret = imap_base64( $value );
            break;
        case ENCQUOTEDPRINTABLE:
            $ret = imap_qprint( $value );
            break;
        case ENCOTHER:
            $ret = $value;
            // not sure if this needs decoding at all
            break;
        default: // what is this???
            $ret = $value;
    }
    return $ret;
}


/******* FUNCTIONS BELOW THIS POINT ARE NOT USED AT THE MOMENT ******************/

function get_mime_type(&$structure)
{
$primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
if($structure->subtype)
 {
 return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype;
 }
return "TEXT/PLAIN";
}


function getPart($stream, $msg_number, $mime_type, $structure = false, $part_number = false)
{
    if(!$structure)
    {
        $structure = imap_fetchstructure($stream, $msg_number);
    }
    if($structure)
    {
        if($mime_type == get_mime_type($structure))
        {
            if(!$part_number)
            {
                $part_number = "1";
            }
            $text = imap_fetchbody($stream, $msg_number, $part_number);
            if($structure->encoding == 3)
            {
                return imap_base64($text);
            }
            else if($structure->encoding == 4)
            {
                return imap_qprint($text);
            }
            else
            {
                return $text;
            }
        }
        if($structure->type == 1) /* multipart */
        {
            while(list($index, $sub_structure) = each($structure->parts))
            {
                if($part_number)
                {
                    $prefix = $part_number . '.';
                }
                $data = get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
                if($data)
                {
                    return $data;
                }
            }
        }
    }
    return false;
}           


?>
