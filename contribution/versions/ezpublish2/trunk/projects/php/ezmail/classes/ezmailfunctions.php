<?
/* This file contains a list of functions that are used by the various classes.
 They are put here because they are generall and to keep the classes from beeing
crouded.*/

function getHeaders( &$Mail, $imap_stream, $msgno )
{
    $headers = imap_headerinfo( $imap_stream, $msgno );
//    echo "<pre>"; print_r( $headers ); echo "</pre>";
    print( "To " . $headers->toaddress . "<br>");
    print( "From " . $headers->fromaddress . "<br>"); // from NAME
    print( "Reply " . $headers->reply_toaddress ."<br>");
    print( "Subject " . $headers->subject ."<br>");
    print( "MessageID " . $headers->message_id . "<br>" );
    print( "Date " . $headers->date . "<br>" );
    print( "ReplyID " . $headers->in_reply_to . "<br>" );
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


//Right now only the first part with the matching MIME type is returned. A more useful version would create an array and return all matching parts (for GIFs, for instance).
function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false)
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


//How to call this function:
//$msg_struct = imap_fetchstructure($mconn, $msg_no);
//
//DisectThisPart($msg_struct, "");

function DisectThisPart($this_part, $part_no)
{
	if ($this_part->ifdisposition)
    {
		// See if it has a disposition
		// The only thing I know of that this
		// would be used for would be an attachment
		// Lets check anyway
		if ($this_part->disposition == "ATTACHMENT")
        {
			// If it is an attachment, then we let people download it
			
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
                $mime_type = "text";
			break;
            case TYPEMULTIPART:
                $mime_type = "multipart";
			// Hey, why not use this function to deal with all the parts
			// of this multipart part :)
			for ($i = 0; $i < count($this_part->parts); $i++)
            {
				if ($part_no != "")
                {
					$part_no = $part_no.".";
				}
				for ($i = 0; $i < count($this_part->parts); $i++)
                {
					DisectThisPart($this_part->parts[$i], $part_no.($i + 1));
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
			// hmmm....
		}
		$full_mime_type = $mime_type."/".$this_part->subtype;
		
		// Decide what you what to do with this part
		// If you want to show it, figure out the encoding and echo away
		switch ($this_part->encoding)
        {
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
}



?>
