<?

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezcc/classes/ezcclog.php" );
include_once( "classes/INIFile.php" );

$logFile = "checkout/log/checkout.log";

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZCCMain", "Language" );
$MaxItems = $ini->read_var( "eZCCMain", "MaxLogItems" );
$locale = new eZLocale( $Language );


$t = new eZTemplate( "ezcc/admin/" . $ini->read_var( "eZCCMain", "TemplateDir" ),
                     "ezcc/admin/intl/", $Language, "page.php" );

$t->set_file( "page_tpl", "page.tpl" );

$t->setAllStrings();

$t->set_block( "page_tpl", "log_item_tpl", "log_item" );

$t->set_block( "page_tpl", "message_list_tpl", "message_list" );
$t->set_block( "message_list_tpl", "error_msg_tpl", "error_msg" );
$t->set_block( "message_list_tpl", "cutover_success_tpl", "cutover_success" );
$t->set_block( "message_list_tpl", "cancel_success_tpl", "cancel_success" );
$t->set_block( "log_item_tpl", "button_item_tpl", "button_item" ); // SF
$t->set_block( "page_tpl", "action_item_tpl", "action_item" ); // SF
$t->set_var( "button_item", "" ); // SF
$t->set_var( "action_item", "" ); // SF
$t->set_var( "cancel_success", "" );
$t->set_var( "cutover_success", "" );
$t->set_var( "error_msg", "" );
$t->set_var( "message_list", "" );

if ( $LogSelect == "unhandled" )
    $t->parse( "action_item", "action_item_tpl" );


// Somebody please make a better version of this, if possible
//$fd = fopen( "checkout/log/checkout.log", "r" );
$logitems = array();
if ( $fd )
{
    while( !feof( $fd ) )
    {
        $line = fgets( $fd, 4096 );
        if ( strlen( $line ) > 0 and $line[0] != "#" )
        {
            $logitems[] = $line;
            if ( count( $logitems ) > $MaxItems )
                $logitems = array_slice( $logitems, count( $logitems ) - $MaxItems, $MaxItems );
        }
    }
}

$t->set_var( "log_item", "" );



$dateTime = new eZDateTime();
$date = $dateTime->year() . $dateTime->addZero( $dateTime->month() ) .  $dateTime->addZero( $dateTime->day() );
$time = $dateTime->addZero( $dateTime->hour() ) . $dateTime->addZero( $dateTime->minute() ) .  $dateTime->addZero( $dateTime->second() );

$taID = md5( microtime() );
$currency = "280";
$xml = "";
$RC_CODE = "";
$cutover_done = false;
$cancel_done = false;


// Cancel actions.
$CheckCancelArray = explode( ",", $CheckCancelArray );
if ( count ( $CheckCancelArray ) > 0 )
{
    for( $i=0; $i < count($CheckCancelArray); $i++ )
    {
        $check = ( "CancelButton" . $CheckCancelArray[$i] );
        if ( eZHTTPTool::getVar($check) )
        {
            $RefID = $TA_ID[$i];
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?> <ICMessage IC_TA_ID_REF=\"$RefID\" IC_SHOP_ID=\"65 019\" IC_SHOP_TA_ID=\"$taID\" IC_TA_TYPE=\"119\" IC_DATE=\"$date\" IC_TIME=\"$time\" IC_AMOUNT=\"$Amount[$i]\" IC_PROCESSING_CODE=\"1\" />";
            print( "Sending XML: " . htmlspecialchars($xml) . "<br><br>" );
            $execString = "checkout/socket.pl " . EscapeShellArg( $xml);
            $ret = system( $execString, $ret_var );
            $cancel_done = true;
            $CancelRefID = $RefID;
        }
    }
}

if ( isset ( $Cutover ) )
{
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <ICMessage IC_SHOP_ID=\"65 019\" IC_SHOP_TA_ID=\"$taID\" IC_TA_TYPE=\"910\" IC_DATE=\"$date\" IC_TIME=\"$time\" IC_PROCESSING_CODE=\"1\" />";

    $execString = "checkout/socket.pl " . EscapeShellArg( $xml);
    $ret = system( $execString, $ret_var );
    print( "Sending XML: " . htmlspecialchars($xml) . "<br><br>" );
    $cutover_done = true;
}


// Send the XML to the server.
if ( $xml )
{
    $domtree =& qdom_tree( $ret );

    // Get the answer.
    if ( $domtree and $ret != "" )
    {
        foreach ( $domtree->children as $child )
        {
            if ( count( $child->attributes )> 0 )
                foreach ( $child->attributes as $attribute )
                {
                    if ( $attribute->name == "IC_RC_CODE" )
                    {
                        $RC_CODE = $attribute->content;
                    }
                    
                    if ( $attribute->name == "IC_RC_TEXT" )
                    {
                        $RC_TEXT = $attribute->content;
                    }
                }
        }
    }
    else
    {
        if ( $ret_var != 0 )
            $RC_TEXT = "Error when executing checkout/socket.pl";
        else
            $RC_TEXT = "Could not parse received xml";
    }
    if ( $RC_CODE == "00" )
    {
        $PaymentSuccess = "true";
        $ClearingError = false;
    }
    else
    {
        $PaymentSuccess = "false";
        $ClearingError = true;
    }
    $ClearingError = false;

    // Set the messages.
    $t->set_var( "success", "" );
    $t->set_var( "error", "" );
    $t->set_var( "ref_id", $RefID[$i] );
    if ( $ClearingError == true )
    {
        $t->set_var( "error_text", $RC_TEXT );
        $t->set_var( "error_code", $RC_CODE );
        $t->parse( "error_msg", "error_msg_tpl", true );
    }
    else if ( $cutover_done )
    {
        for( $i=0; $i < count ( $CheckCancelArray ); $i++ )
        {
            if ( $CardTypeID[$i] == "0" )
            {
                $log = new eZCCLog();
                $log->getByRefID( $RefID[$i] );
                $log->setStatus( 1 );
                $log->store();
            }
        }
        $t->parse( "cutover_success", "cutover_success_tpl", true );
    }
    else if ( $cancel_done )
    {
        $log = new eZCCLog();
        $log->getByRefID( $CancelRefID );
        $log->setStatus( 2 );
        $log->store();
        $t->parse( "cancel_success", "cancel_success_tpl", true );
    }
    $t->parse( "message_list", "message_list_tpl" );
}

// List all the entries.
if ( $Show )
    $logList = eZCCLog::getAll( $LogSelect );
else
$logList = eZCCLog::getAll( );

$elvCount = array();
$masterCount = array();
$visaCount = array();
foreach( $logList as $log )
{
    switch( $log->type() )
    {
        case "ELV":
            $elvCount[] = 1;
        break;
        case "MCARD":
            $masterCount[] = 1;
        break;
        case "AMERCIANEXPRESS":
            $amCount[] = 1;
        break;
        case "VISA":
        {
            $visaCount[] = 1;
        }
        break;
    }
}

$elvCount = count($elvCount);
$visaCount = count($visaCount);
$masterCount = count($masterCount);

foreach( $logList as $log )
{
    $t->set_var( "number", "0" );
    $t->set_var( "log_type", $log->type() );
    $t->set_var( "log_order", $log->preOrderID() );
    $t->set_var( "log_id", $log->taID() );
    $t->set_var( "log_date", $locale->format( $log->date() ) );
    $t->set_var( "log_time", $locale->format( $log->time() ) );
    $t->set_var( "log_amount", $log->amount() );
    $t->set_var( "log_rc_code", $log->rcCode() );
    $t->set_var( "log_rc_text", $log->rcText() );
    $t->set_var( "log_blz", $log->blz() );
    $t->set_var( "log_accountnr", $log->acctNr() );    
    
    if ( $LogSelect == "unhandled" )
    	$t->parse( "button_item", "button_item_tpl");

    $t->set_var( "cancel_id", ereg_replace( " ", "_", $log->taID() ) );
    $t->set_var( "cutover_id", ereg_replace( " ", "_", $log->taID() ) );

    $t->set_var( "amount", $log->amount() );

    switch( $log->type() )
    {
        case "ELV":
        {
            $t->set_var( "card_type", "0" );
            $t->set_var( "number", $elvCount );
        }
        break;
        case "MCARD":
        {
            $t->set_var( "card_type", "1" );
            $t->set_var( "number", $masterCount );
        }
        break;
        case "AMERCIANEXPRESS":
        {
            $t->set_var( "card_type", "2" );
            $t->set_var( "number", $amCount );
        }
        break;
        case "VISA":
        {
            $t->set_var( "card_type", "3" );
            $t->set_var( "number", $visaCount );
        }
        break;

        default:
        {
            $t->set_var( "card_type", "999" );
            $t->set_var( "card_type", "0" );
        }
    }
    $i++;
    $arrayCount .= ereg_replace( " ", "_", $log->taID() ) . ",";
    $t->parse( "log_item", "log_item_tpl", true );
}
$length = strlen( $arrayCount );
$arrayCount = substr( $arrayCount, 0, $length-1 );
$t->set_var( "check_cancel_array", $arrayCount );


$t->pparse( "output", "page_tpl" );

?>
