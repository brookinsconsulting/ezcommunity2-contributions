<?
include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezmail/classes/ezmailfunctions.php" );

// ?TODO? convert this into a class that takes an account as a parameter, this way we only need to open
// a connection once. (what about menubox?!?, several imap accounts?!?)
// TODO: Create an error handling system.

/*!
  Internal class used to keep only one IMAP connection open for each account.
  This saves a lot of connecting time.
  This class uses the singleton pattern.
 */
$IMAPConnectionInstance = 0;
class IMAPConnections
{
    /*!
      Constructur, initialize
     */
    function IMAPConnections()
    {
        $this->Connections = array();
        $this->CurrentMailBoxes = array(); // keeps track of what folder each mailbox is in.
        $this->HostByName = array(); //holds ip's of all hosts.
    }

    /*!
      Returns a valid IMAPConnection object.
      NOTE: ALWAYS use =& when calling this function... if not you will get a copy
      and the effect of this class is zeroed out..
     */
    function &instance()
    {
        global $IMAPConnectionInstance;
        if( get_class( $IMAPConnectionInstance ) != "imapconnections" )
        {
            $IMAPConnectionInstance = new IMAPConnections();
        }
        return $IMAPConnectionInstance;
    }
    
    /*!
      Returns an open connection to the given account and the given mailbox.
      If mailbox is not given, the current connection is returned.
     */
    function getConnection( $account, $mailbox=-1 )
    {
        if( !is_object( $account ) )
            $account = new eZMailAccount( $account );

        $accountID = $account->id();
        
        // check if account is there at all
        if( !key_exists( $accountID, $this->Connections ) )
        {
            // fetch the IP of the host
            $ip = $this->fetchIP( $account->server() );
            $userName = $account->loginName();
            $password = $account->password();
            $port = $account->serverPort();
            if( $mailbox == -1 )
                $mailbox = "INBOX";
            
            $serverString = createServerString( $ip, $port, $mailbox );
            $mbox = @imap_open( $serverString, $userName, $password );
            $this->Connections[$accountID] = $mbox;
            $this->CurrentMailboxes[$accountID] = $mailbox;
            return $mbox;
        }

        // correct mailbox
        if( $this->CurrentMailboxes[$accountID] == $mailbox || $mailbox == -1 )
            return $this->Connections[$accountID];

        // uncorrect mailbox.. change folder
        $ip = $this->fetchIP( $account->server() );
        $userName = $account->loginName();
        $password = $account->password();
        $port = $account->serverPort();
        $stream = $this->Connections[$accountID];
        
        $serverString = createServerString( $ip, $port, $mailbox );
        @imap_reopen( $stream, $serverString );
        return $this->Connections[$accountID];
    }
    
    /*!
      \static
      Closes all open connections.
     */
    function closeAll()
    {
        if( is_object( $IMAPConnectionInstance ) )
        {
            foreach( $IMAPConnectionInstance->Connections as $id => $stream )
            {
                imap_close( $stream );
            }
            // clear members in case someone desides to use it again...
            $IMAPConnectionInstance->Connections = array();
            $IMAPConnectionInstance->CurrentMailBoxes = array(); 
        }
    }

    /*!
      Fetches the right IP address for any hostname.
     */
    function fetchIP( $host )
    {
        $host = trim( $host );
        if( !key_exists( $host, $this->HostByName ) )
        {
            $this->HostByName[$host] = gethostbyname( $host );
        }
        return $this->HostByName[$host];
    }

    var $Connections;
    var $CurrentMailboxes;
    var $HostByName;
}


/*!
  Fetches all mail headers for an imap mailbox.
  Fetches data from INBOX for now.

  TODO:
  - fetch email address correctly. (not just name)
  - fetch email date.
  - offset, range
  Returns false if the function did not succeed.
 */
/*
function imapGetMailList( $account )
{
    if( get_class( $account ) != "ezmailaccount" )
        $account = new eZMailAccount( $account );
    
    $connections =& IMAPConnections::instance();
    $mbox = $connections->getConnection( $account );

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

    return $mail;
}
*/

/*!
  Fetch a mail from an imap server. Returns it as an eZMail object.
  TODO: Current version tries to extract attachments. We need to provide these as some sort of
  extra info.
  I propose adding array of structs attachmentsLinks to an eZMail object, only used when receiving imap mail.
*/
/*
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
*/

/*********** INTERAL HELPER FUNCTIONS ******************/

function createServerString( $server, $port, $mailbox="" )
{
    return "{" . "$server:$port" . "}$mailbox";
}

function createServerStringFromAccount( $account, $mailbox="" )
{
    $connections = IMAPConnections::instance();
    $ip = $connections->fetchIP( $account->server() );
    $port = $account->serverPort();
    return "{" . "$ip:$port" . "}$mailbox";
}

// why on earth does this fail from time to time?!?
/*!
  Connects to an IMAP server. Returns false in case of an error.
 */
/*
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
//    echo "Using serverstring: " . $serverString;
    $mbox = @imap_open( $serverString, $userName, $password );
//    if( $mbox == false )
//    {
        
        //
        //$mbox
        // or die( "imap_open failed: " . imap_last_error() . "\n" );
//    }

    return $mbox;
}
*/
?>
