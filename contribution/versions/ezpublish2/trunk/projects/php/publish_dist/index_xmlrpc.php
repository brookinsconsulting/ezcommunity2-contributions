<?
ob_end_clean();
ob_start();


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
$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;

define( "EZPUBLISH_SERVER_VERSION", 0.2 );

// Error codes
define( "EZERROR_BAD_LOGIN", 1 );
define( "EZERROR_INVALID_FUNCTION", 2 );
define( "EZERROR_CUSTOM", 3 );
define( "EZERROR_NO_RETURN_DATA", 4 );
define( "EZERROR_NONEXISTING_OBJECT", 5 );

include_once( "classes/ezlocale.php" );
include_once( "classes/ezlog.php" );



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

    $GLOBALS["login"] =& $login;
    $login = $call["User"]->value();
    $GLOBALS["password"] =& $Password;
    $password = $call["Password"]->value();

    $GLOBALS["User"] =& $User;
    $User = new eZUser();
    $User = $User->validateUser( $login, $password );
        
    if ( ( get_class( $User ) == "ezuser" ) and eZPermission::checkPermission( $User, "eZUser", "AdminLogin" ) )
    {
//          eZLog::writeNotice( "XML-RPC logged in." );
        $GLOBALS["version"] =& $version;
        $version = $call["Version"]->value();

        // Get caller ID if any
        $GLOBALS["caller"] =& $caller;
        $caller = false;
        if ( is_object( $call["Caller"] ) )
            $caller = $call["Caller"];

        $GLOBALS["RefID"] =& $RefID;
        $RefID = false;
        if ( is_object( $call["RefID"] ) )
            $RefID = $call["RefID"];

        // decode URL
        $REQUEST_URI = $call["URL"]->value();
        $GLOBALS["Module"] =& $Module;
        $Module = $REQUEST_URI["Module"]->value();
        $GLOBALS["RequestType"] =& $RequestType;
        $RequestType = $REQUEST_URI["Type"]->value();
        $GLOBALS["ID"] =& $ID;
        if( is_object( $REQUEST_URI["ID"] ) )
            $ID = $REQUEST_URI["ID"]->value();
        else
            $ID = 0;
        
        $GLOBALS["Data"] =& $Data;
        $Data = $call["Data"]->value();
        $GLOBALS["Command"] =& $Command;
        $Command = $call["Command"]->value();

        $ReturnData = array();

        $Error = false;
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

function &createErrorMessage( $error_id, $error_msg = false )
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
    $ret->setError( $error_id, $error_text );
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

ob_end_flush();
exit();
?>
