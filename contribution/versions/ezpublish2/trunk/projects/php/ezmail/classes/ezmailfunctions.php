<?
include_once( "ezmail/classes/ezmail.php" );

/* This file contains a list of functions that are used by the various classes.
 They are put here because they are generall and to keep the classes from beeing
crouded.*/

function getHeaders( &$mail, $imap_stream, $msgno )
{
    $headers = imap_headerinfo( $imap_stream, $msgno );
//    print( "To: " . getDecodedHeader( $headers->toaddress ). "<br>" );
//    print( "From: " . getDecodedHeader( $headers->fromaddress ). "<br>"); // from NAME
//    print( "Reply: " . getDecodedHeader( $headers->reply_toaddress ) ."<br>");
//    print( "Subject: " . getDecodedHeader( $headers->subject )."<br>");
//    print( "MessageID: " . getDecodedHeader( $headers->message_id ). "<br>" );
//    print( "Date: " . getDecodedHeader( $headers->date ) . "<br>" );
//    print( "ReplyID: " . getDecodedHeader( $headers->in_reply_to ). "<br>" );

    $mail->setTo( getDecodedHeader( $headers->toaddress )  );
    $mail->setFrom( getDecodedHeader( $headers->fromaddress ) ); // from NAME
    $mail->setReplyTo( getDecodedHeader( $headers->reply_toaddress ) );
    $mail->setSubject( getDecodedHeader( $headers->subject ) );
    $mail->setMessageID( getDecodedHeader( $headers->message_id ) );
//    print( "Date: " . getDecodedHeader( $headers->date ) . "<br>" );
    $mail->setReferences( getDecodedHeader( $headers->in_reply_to ) );
}

/*
  
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
function disectThisPart( $this_part, $part_no, $mbox, $msgnum, &$mail, $level=0 )
{
	if ($this_part->ifdisposition)
    {
		if ($this_part->disposition == "ATTACHMENT")
        {
            // First see if they sent a filename
			$att_name = "unknown";
            for ($lcv = 0; $lcv < count($this_part->parameters); $lcv++)
            {
                $param = $this_part->parameters[$lcv];

                if ($param->attribute == "NAME")
                {
                    $att_name = $param->value;
                    break;
	            }
	        }

			// You could give a link to download the attachment here....
//            print( "Mail has an attachment named: $att_name <br>" );
        }
        else
        {
			// disposition can also be used for images in HTML (Inline)
		}
	}
    else
    {
		// Not an attachment, lets see what this part is...
		switch ($this_part->type)
        {
            case TYPETEXT:
            {
                $mime_type = "text";
                $value = decode( $this_part->encoding, fetch_part( $part_no, $mbox, $msgnum ) );
                $mail->setBodyText( $value );
            }
			break;
            case TYPEMULTIPART:
            {
                $mime_type = "multipart";
                for ($i = 0; $i < count($this_part->parts); $i++)
                {
                    if ( $level != 0 )
                        $part_no = $part_no.".";
                    else
                        $part_no = "";
                
                    for ($i = 0; $i < count($this_part->parts); $i++)
                    {
                        disectThisPart($this_part->parts[$i], $part_no.($i + 1), $mbox, $msgnum, $mail, 1);
                    }
                }
            }
			break;
            case TYPEMESSAGE:
                $mime_type = "message";
			break;
            case TYPEAPPLICATION:
                $mime_type = "application";
			break;
            case TYPEAUDIO:
                $mime_type = "audio";
			break;
            case TYPEIMAGE:
                $mime_type = "image";
			break;
            case TYPEVIDEO:
                $mime_type = "video";
			break;
            case TYPEMODEL:
                $mime_type = "model";
			break;
            default:
                $mime_type = "unknown";
            break;
		}
		$full_mime_type = $mime_type."/".$this_part->subtype;

        /*
        if( $mime_type != "multipart"  )
        {
            echo "$full_mime_type in part $part_no<BR>";
            
            switch ($this_part->encoding)
            {
                case ENC7BIT:
                    break;
                case ENC8BIT:
                    break;
                case ENCBINARY:
                    break;
                case ENCBASE64:
                    // use imap_base64 to decode
                    break;
                case ENCQUOTEDPRINTABLE:
                    // use imap_qprint to decode
                    break;
                case ENCOTHER:
                    // not sure if this needs decoding at all
                    break;
                default:
                    // it is either not encoded or we don't know about it
            }
        }
        */
	}
}

function decode( $enctype, $value )
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

function fetch_part( $partnum, $mbox, $msgnum )
{
    $part = imap_fetchbody( $mbox, $msgnum, $partnum );
    return $part;
}

function get_mime_type(&$structure)
{
$primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
if($structure->subtype)
 {
 return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype;
 }
return "TEXT/PLAIN";
}


//Right now only the first part with the matching MIME type is returned.
//A more useful version would create an array and return all matching parts
//(for GIFs, for instance).
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
