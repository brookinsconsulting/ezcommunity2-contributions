<?
ob_end_clean();
ob_start();

define( "EZPUBLISH_SERVER_VERSION", 0.2 );

// Error codes
define( "EZERROR_BAD_LOGIN", 1 );
define( "EZERROR_INVALID_FUNCTION", 2 );
define( "EZERROR_CUSTOM", 3 );
define( "EZERROR_NO_RETURN_DATA", 4 );
define( "EZERROR_NONEXISTING_OBJECT", 5 );
define( "EZERROR_PHP_ERROR", 6 );
define( "EZERROR_BAD_REQUEST_DATA", 7 );

// include the server
include_once( "ezxmlrpc/classes/ezxmlrpcserver.php" );

// include the datatype(s) we need
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcresponse.php" );

// site information
include_once( "classes/INIFile.php" );
$ini =& INIFile::globalINI();
$GlobalSiteIni =& $ini;

include_once( "classes/ezlog.php" );
error_reporting(0);
$old_error_handler = set_error_handler("xmlrpcErrorHandler");

include_once( "classes/ezlocale.php" );



// eZ user
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezuser/classes/ezpermission.php" );



//  eZLog::writeNotice( "XML-RPC connect." );

$server = new eZXMLRPCServer( );


// Register server function
$server->registerFunction( "Call", array( new eZXMLRPCStruct( ) ) );

// process the server requests
$server->processRequest();

/*!
  Function to handle requests.
*/
function Call( $args )
{
//      eZLog::writeNotice( "XML-RPC call." );
    $call = $args[0]->value();

    $login = $call["User"]->value();
    $GLOBALS["login"] =& $login;
    $password = $call["Password"]->value();
    $GLOBALS["password"] =& $Password;

    $User = new eZUser();
    $User = $User->validateUser( $login, $password );
    $GLOBALS["User"] =& $User;
        
    if ( ( get_class( $User ) == "ezuser" ) and eZPermission::checkPermission( $User, "eZUser", "AdminLogin" ) )
    {
//          eZLog::writeNotice( "XML-RPC logged in." );
        $version = $call["Version"]->value();
        $GLOBALS["version"] =& $version;

        // Get caller ID if any
        $caller = false;
        if ( isset( $call["Caller" ] ) and is_object( $call["Caller"] ) )
            $caller = $call["Caller"];
        $GLOBALS["caller"] =& $caller;

        $RefID = false;
        if ( isset( $call["RefID" ] ) and is_object( $call["RefID"] ) )
            $RefID = $call["RefID"];
        $GLOBALS["RefID"] =& $RefID;

        // decode URL
        $REQUEST_URI = $call["URL"]->value();
        $Module = $REQUEST_URI["Module"]->value();
        $GLOBALS["Module"] =& $Module;
        $RequestType = $REQUEST_URI["Type"]->value();
        $GLOBALS["RequestType"] =& $RequestType;
        if( isset( $REQUEST_URI["ID"] ) and is_object( $REQUEST_URI["ID"] ) )
            $ID = $REQUEST_URI["ID"]->value();
        else
            $ID = 0;
        $GLOBALS["ID"] =& $ID;
        
        $Data = $call["Data"]->value();
        $GLOBALS["Data"] =& $Data;
        $Command = $call["Command"]->value();
        $GLOBALS["Command"] =& $Command;

        $ReturnData = array();

        $Error = false;
        $GLOBALS["XMLRPC_Error"] =& $Error;
        $datasupplier = $Module . "/xmlrpc/datasupplier.php";
        // check for module implementation
        if ( file_exists( $datasupplier )  || ( $Module == "ezpublish" && $RequestType == "modules" ) )
        {
            if ( $Module == "ezpublish" && $RequestType == "modules" )
            {
                // return the modules in the system
                $dir = dir( "." );
                $modules = array();
                while ( $entry = $dir->read() )
                {
                    if ( preg_match( "/ez.*/", $entry ) )
                    {
                        if ( file_exists( $entry . "/xmlrpc/datasupplier.php" ) )
                        {
                            $ReturnCatalogues = true;
                            include( $entry . "/xmlrpc/datasupplier.php" );
                            
                            $modules[] = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $entry ),
                                                                    "Catalogues" => new eZXMLRPCArray( $Catalogues )
                                                                    ) );
                            
                        }
                    }
                }

//                  eZLog::writeNotice( "XML-RPC returning modules." );
                $ReturnData = $modules;

            }
            else
            {

//                  eZLog::writeNotice( "XML-RPC returning standard data." );
                include( $datasupplier );

            }

            if ( !is_object( $RefID ) )
            {
                $RefID = new eZXMLRPCString( md5( microtime() ) );
            }

            if ( $Error )
            {
                $ret =& createErrorMessage( EZERROR_INVALID_FUNCTION );
            }
            else if ( isset( $ReturnData ) and is_object( $ReturnData ) )
            {
                // create the return struct...
                $ret_arr = array( "Version" => new eZXMLRPCDouble( EZPUBLISH_SERVER_VERSION ),
                                  "URL" => createURLStruct( $Module, $RequestType, $ID ),
                                  "Command" => new eZXMLRPCString( $Command ),
                                  "RefID" => $RefID,
                                  "Data" => $ReturnData
                                  );
                if ( is_object( $caller ) )
                    $ret_arr["Caller"] = $caller;
                $ret = new eZXMLRPCStruct( $ret_arr );
            }
            else
            {
                $ret =& createErrorMessage( EZERROR_NO_RETURN_DATA );
            }

            if ( get_class( $Error ) == "ezxmlrpcresponse" )
            {                
                $ret = $Error;
            }            
        }
        else
        {
            $ret =& createErrorMessage( EZERROR_INVALID_FUNCTION );
        }

//          eZLog::writeNotice( "XML-RPC returning  data." );
        return $ret;
    }
    else
    {
        $ret =& createErrorMessage( EZERROR_BAD_LOGIN );
    }
}

function xmlrpcErrorHandler ($errno, $errmsg, $filename, $linenum, $vars)
{
    global $XMLRPC_Error;
// timestamp for the error entry
    $dt = date("Y-m-d H:i:s (T)");

    // define an assoc array of error string
    // in reality the only entries we should
    // consider are 2,8,256,512 and 1024
    $errortype = array (
        1   =>  "Error",
        2   =>  "Warning",
        4   =>  "Parsing Error",
        8   =>  "Notice",
        16  =>  "Core Error",
        32  =>  "Core Warning",
        64  =>  "Compile Error",
        128 =>  "Compile Warning",
        256 =>  "User Error",
        512 =>  "User Warning",
        1024=>  "User Notice"
        );
    // set of errors for which a var trace will be saved
    $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
    
    $err = "<errorentry>\n";
    $err .= "\t<datetime>".$dt."</datetime>\n";
    $err .= "\t<errornum>".$errno."</errnumber>\n";
    $err .= "\t<errortype>".$errortype[$errno]."</errortype>\n";
    $err .= "\t<errormsg>".$errmsg."</errormsg>\n";
    $err .= "\t<scriptname>".$filename."</scriptname>\n";
    $err .= "\t<scriptlinenum>".$linenum."</scriptlinenum>\n";

    if (in_array($errno, $user_errors))
        $err .= "\t<vartrace>".wddx_serialize_value($vars,"Variables")."</vartrace>\n";
    $err .= "</errorentry>\n\n";
    
    // for testing
    // echo $err;

    // save to the error log, and e-mail me if there is a critical user error
//      error_log($err, 3, "/usr/local/php4/error.log");
    if ( $errno != 8 and $errno != 2 )
    {
        eZLog::writeError( $err );
//          $XMLRPC_Error = createErrorMessage( EZ_ERROR_PHP_ERROR, $err );
    }
//      if ($errno == E_USER_ERROR)
//          mail("phpdev@mydomain.com","Critical User Error",$err);
}

function &createErrorMessage( $error_id, $error_msg = false, $error_sub_id = false )
{
    global $ID;
    global $Command;
    global $Module;
    global $RequestType;
    $ret = new eZXMLRPCResponse( );
    switch( $error_id )
    {
        case EZERROR_BAD_LOGIN:
        {
            $error_text = "Login denied, please try again.";
            break;
        }
        case EZERROR_INVALID_FUNCTION:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Server function \"$Command\" for URL \"$Module:/$RequestType/$id_text\" not found.";
            break;
        }
        case EZERROR_NO_RETURN_DATA:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "No return data while processing \"$Command\" for URL \"$Module:/$RequestType/$id_text\".";
            break;
        }
        case EZERROR_NONEXISTING_OBJECT:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Object does not exist, used command \"$Command\" for URL \"$Module:/$RequestType/$id_text\".";
            break;
        }
        case EZERROR_PHP_ERROR:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "PHP error for command \"$Command\" for URL \"$Module:/$RequestType/$id_text\".\n";
            $error_text .= "Error was: $error_msg";
            break;
        }
        case EZERROR_BAD_REQUEST_DATA:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Bad request data for command \"$Command\" for URL \"$Module:/$RequestType/$id_text\".\n";
            break;
        }
        case EZERROR_CUSTOM:
        {
            $error_text = $error_msg;
            break;
        }
        default:
        {
            $error_text = "Unknown error";
        }
    }
    $ret->setError( $error_id, $error_text, $error_sub_id );
    return $ret;
}

function createURLStruct( $module, $type , $id = 0 )
{
    if( $id != 0 )
    {
        $ret = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $id ),
                                          "Type" => new eZXMLRPCString( $type ),
                                          "Module" => new eZXMLRPCString( $module ) )
                            );
    }
    else
    {
        $ret = new eZXMLRPCStruct( array( "Type" => new eZXMLRPCString( $type ),
                                          "Module" => new eZXMLRPCString( $module ) )
                            );
    }
    return $ret;
}

function createSizeStruct( $width, $height )
{
    $ret = new eZXMLRPCStruct( array( "Width" => new eZXMLRPCInt( $width ),
                                      "Height" => new eZXMLRPCInt( $height ) ) );
    return $ret;
}

function createDateStruct( $date )
{
    $ret = new eZXMLRPCStruct( array( "Year" => new eZXMLRPCInt( $date->year() ),
                                      "Month" => new eZXMLRPCInt( $date->month() ),
                                      "Day" => new eZXMLRPCInt( $date->day() ) ) );
    return $ret;
}

function createDateTimeStruct( $datetime )
{
    $ret = new eZXMLRPCStruct( array( "Year" => new eZXMLRPCInt( $datetime->year() ),
                                      "Month" => new eZXMLRPCInt( $datetime->month() ),
                                      "Day" => new eZXMLRPCInt( $datetime->day() ),
                                      "Hour" => new eZXMLRPCInt( $datetime->hour() ),
                                      "Minute" => new eZXMLRPCInt( $datetime->minute() ),
                                      "Second" => new eZXMLRPCInt( $datetime->second() ) ) );
    return $ret;
}

function &createTreeStruct( $tree, $module, $type )
{
    $id = $tree["ID"];
    $name = $tree["Name"];
    $children = $tree["Children"];
    $child_arr = array();
    foreach( $children as $child )
    {
        $child_arr[] =& createTreeStruct( $child, $module, $type );
    }
    $item_arr = array( "Location" => createURLStruct( $module, $type, $id ),
                       "Name" => new eZXMLRPCString( $name ) );
    if ( count( $child_arr ) > 0 )
        $item_arr["Children"] = $child_arr;
    $item = new eZXMLRPCStruct( $item_arr );
    return $item;
}

function createDateTime( $struct )
{
    $datetime = new eZDateTime();
    $datetime->setYear( $struct["Year"]->value() );
    $datetime->setMonth( $struct["Month"]->value() );
    $datetime->setDay( $struct["Day"]->value() );
    $datetime->setHour( $struct["Hour"]->value() );
    $datetime->setMinute( $struct["Minute"]->value() );
    $datetime->setSecond( $struct["Second"]->value() );
    return $datetime;
}

ob_end_flush();
exit();
?>
