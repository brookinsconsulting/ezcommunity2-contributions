<?
// Fetchbody er en av de viktigste funksjonene!
$mbox = imap_open("{zap.ez.no/pop3:110}","larson","AcRXYJJA",OP_HALFOPEN)
      or die("can't connect: ".imap_last_error());

//$headers = imap_headers( $mbox );
//print( "<pre>"); print_r( $headers );print( "</pre>");
//$body = imap_body( $mbox, 3 );

//$structure = imap_fetchstructure( $mbox, 3 );
//print( "<pre>"); print_r( $structure );print( "</pre>");

 
// get plain text 
$data = get_part($mbox, 3, "TEXT/PLAIN");
print( $data );

$header = imap_header( $mbox, 3 );
print( "<pre>"); print_r( $header );print( "</pre>");
print( "\n\n\n ############ REPLY ################" );
$header = imap_header( $mbox, 4 );
print( "<pre>"); print_r( $header );print( "</pre>");

//$data = get_part($mbox, 3, "IMAGE/GIF");
//print( $data );
// get HTML text 
//$data = get_part($stream, $msg_number, "TEXT/HTML"); 

//$body = imap_body( $mbox, 2 );
//print( $body );
//$test =  imap_check( $mbox );
//print( "<pre>"); print_r( $test );print( "</pre>");

/*
$list = imap_listmailbox($mbox,"{zap.ez.no/pop3:110}","*");
if(is_array($list)) {
  reset($list);
  while (list($key, $val) = each($list))
    print imap_utf7_decode($val)."<br>\n";
} else
print "imap_listmailbox failed: ".imap_last_error()."\n"; */
imap_close($mbox);


function get_mime_type(&$structure) 
{ 
$primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER"); 
if($structure->subtype) 
 { 
 return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype; 
 } 
return "TEXT/PLAIN"; 
} 
 
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
 

?>
