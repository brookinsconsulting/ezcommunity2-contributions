<?
include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezmail/classes/ezmailfunctions.php" );

// ?TODO? convert this into a class that takes an account as a parameter, this way we only need to open
// a connection once. (what about menubox?!?, several imap accounts?!?)
// TODO: Create an error handling system.


/*!
  Returns the complete folder tree of an imap account. (folders come in correct order ).
  / entry
   ->Name
   ->FullName
   ->Level
  TODO:
  Don't know if this is working correctly yet as I can't create subfolders on the test account.
  Returns false if the function did not succeed.
 */
/*
function imapGetFolderTree( $account )
{
    echo "Was here";
    if( get_class( $account ) != "ezmailaccount" )
        $account = new eZMailAccount( $account );

    $mbox = imapConnect( $account );
    if( !$mbox ) // connection to server failed.
    {
        return false;
    }
        
    $server = $account->server();
    $mailBoxes = imap_getmailboxes( $mbox, "{" . $server . "}", "*" );
//    echo "<pre>"; print_r( $mailBoxes ); echo "</pre>";

    $resultArray = array();
    if( $mailBoxes  )
    {
        $i = 0;
        foreach( $mailBoxes as $mailBox )
        {
            $key = explode( "}", $mailBox->name );
            $resultArray[$i]->Name = $key[1];
            $resultArray[$i]->FullName = $mailBox->name;
//            echo "<pre>"; print_r( $resultArray ); echo "</pre>";
            $i++;
        }
    }
    else
    {
        echo "imap_getmailboxes failed: ".imap_last_error()."\n";
    }
    imapDisconnect( $mbox );
    return $resultArray;
}
*/

/*!
  Fetches all mail headers for an imap mailbox.
  Fetches data from INBOX for now.

  TODO:
  - fetch email address correctly. (not just name)
  - fetch email date.
  - offset, range
  Returns false if the function did not succeed.
 */
function imapGetMailList( $account )
{
    $mbox = imapConnect( $account );
    if( !$mbox )
    {
        return false;
    }
    
    $MC = imap_check($mbox); 
    $MN = $MC->Nmsgs; 
    $overview = imap_fetch_overview( $mbox, "1:$MN", 0 );
    foreach( $overview as $mailHeader )
    {
        $mailItem = new eZMail();
        $mailItem->setSize( $mailHeader->size );
        $mailItem->setSubject( $mailHeader->subject );
        $mailItem->setFrom( $mailHeader->from );
        $mailItem->setTo( $mailHeader->to );
        if( $mailHeader->answered )
            $mailItem->setStatus( REPLIED );
        else if( $mailHeader->seen )
            $mailItem->setStatus( READ );
        else
            $mailItem->setStatus( UNREAD );

        $mail[] = $mailItem;
//        echo "<pre>";print_r( $mailHeader ); echo "</pre>";
    }

    imapDisconnect( $mbox );
    return $mail;
}


/*!
  Fetch a mail from an imap server. Returns it as an eZMail object.
  TODO: Current version tries to extract attachments. We need to provide these as some sort of
  extra info.
  I propose adding array of structs attachmentsLinks to an eZMail object, only used when receiving imap mail.
*/
function imapGetMail( $account, $mailID )
{
    $mail = new eZMail();
    $mbox = imapConnect( $account );
    $header = imap_header( $mbox, $mailID );

    $mail->setUDate( $header->udate );
    getHeaders( $mail, $mbox, $mailID ); // fetch header information
    $mailstructure = imap_fetchstructure( $mbox, $mailID );
    disectThisPart( $mailstructure, "1", $mbox, $mailID, $mail );

//    echo "<pre>";print_r( $mail );echo "</pre>";
    
//    echo imap_body( $mbox, $mailID ); 

    imapDisconnect( $mbox );
}

/*!
  Fetch an attachment of a mail.
 */
function imapFetchAttachment()
{
}

/*!
  Functions to encode more information into one url position. This allows us to use the same
  templates for remote and local mail.
 */
//function encodeFolderID( $accountID, $folderName )
//{
//    return rawurlencode( $accountID . "-" . $folderName );
//}

/*!
  Returns an array with the 
 */
//function decodeFolderID( $codedString )
//{
//    $elements = explode( "-", $codedString, 2 ); // max 1 split rest is foldername.
//    return $elements;
//}
/*********** INTERAL HELPER FUNCTIONS ******************/

function createServerString( $server, $port, $mailbox )
{
    return "{" . "$server:$port" . "}$mailbox";
}

// why on earth does this fail from time to time?!?
/*!
  Connects to an IMAP server. Returns false in case of an error.
 */
function imapConnect( $account, $mailbox = "INBOX" )
{
    if( get_class( $account ) != "ezmailaccount" )
        $account = new eZMailAccount( $account );

    $server = $account->server();
//    $server = "194.248.148.68"; // temporary hack, I have a DNS fail here...
    $userName = $account->loginName();
    $password = $account->password();
    $port = $account->serverPort();

    $serverString = createServerString( $server, $port, $mailbox );
    $mbox = @imap_open( $serverString, $userName, $password );
//    if( $mbox == false )
//    {
        
        //
        //$mbox
        // or die( "imap_open failed: " . imap_last_error() . "\n" );
//    }

    return $mbox;
}

function imapDisconnect( $stream )
{
    imap_close( $stream );
}

?>
